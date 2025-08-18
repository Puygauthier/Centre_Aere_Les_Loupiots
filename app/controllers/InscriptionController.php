<?php
namespace App\Controllers;

use App\Models\Enfant;
use App\Models\Activite;
use App\Models\Inscription;
use App\Core\Database;
use PDO;

class InscriptionController
{
  // GET /inscriptions : liste
  public function index(): void {
    $pdo = Database::getPdo();
    $enfants = Enfant::all();
    $enfantId = (int)($_GET['enfant_id'] ?? 0);

    $sql = "SELECT i.id, i.statut, i.created_at,
                   e.id AS enfant_id, e.nom AS enfant_nom, e.prenom AS enfant_prenom,
                   a.id AS activite_id, a.titre, a.categorie, a.date_debut, a.date_fin
            FROM inscriptions i
            JOIN enfants e   ON e.id = i.enfant_id
            JOIN activites a ON a.id = i.activite_id
            WHERE (? = 0 OR i.enfant_id = ?)
            ORDER BY a.date_debut DESC, i.id DESC";
    $st = $pdo->prepare($sql);
    $st->execute([$enfantId, $enfantId]);
    $inscriptions = $st->fetchAll();

    include ROOT . '/app/views/inscriptions/index.php';
  }

  // GET /inscriptions/create
  public function create(): void {
    $enfants   = Enfant::all();
    $activites = method_exists(Activite::class, 'allOpen') ? Activite::allOpen() : Activite::all();
    include ROOT . '/app/views/inscriptions/create.php';
  }

  // POST /inscriptions
  public function store(): void {
    $enfantId   = (int)($_POST['enfant_id'] ?? 0);
    $activiteId = (int)($_POST['activite_id'] ?? 0);

    if ($enfantId <= 0 || $activiteId <= 0) {
      $_SESSION['flash'] = ['type'=>'error','text'=>"Paramètres invalides."];
      header('Location: /inscriptions/create'); return;
    }

    $pdo = Database::getPdo();

    // 1) Liste des certificats requis pour l'activité
    $requis = Activite::certificatsRequis($activiteId); // [] si aucun
    $manquants = [];
    $uploads = []; // certificats fournis maintenant

    if (!empty($requis)) {
      foreach ($requis as $r) {
        // déjà validé ?
        $st = $pdo->prepare("SELECT 1 FROM enfant_certificats
                             WHERE enfant_id=? AND certificat_type_id=? AND statut='valide' LIMIT 1");
        $st->execute([$enfantId, (int)$r['id']]);
        $dejaValide = (bool)$st->fetchColumn();

        if ($dejaValide) continue;

        $input = 'certif_'.$r['code'];
        if (isset($_FILES[$input]) && $_FILES[$input]['error'] === UPLOAD_ERR_OK) {
          $uploads[] = ['row'=>$r, 'file'=>$_FILES[$input]];
        } else {
          $manquants[] = $r['libelle'];
        }
      }

      // Si au moins un requis manque et aucun upload correspondant → BLOQUER (pas d'inscription)
      if (!empty($manquants)) {
        $liste = implode(', ', $manquants);
        $_SESSION['flash'] = ['type'=>'error','text' =>
          "Certificat(s) obligatoire(s) manquant(s) : {$liste}. "
          ."Sans pièce jointe, la réservation ne peut pas être prise. "
          ."Après vérification du service, la structure se réserve le droit de refuser la réservation. "
          ."En cas de force majeure, la structure peut modifier le programme et annuler la sortie, sans obligation de reprogrammation."
        ];
        header('Location: /inscriptions/create'); return;
      }
    }

    try {
      $pdo->beginTransaction();

      // 2) Lock activité + anti-doublon
      $st = $pdo->prepare("SELECT id, capacity FROM activites WHERE id=? FOR UPDATE");
      $st->execute([$activiteId]);
      $act = $st->fetch(PDO::FETCH_ASSOC);
      if (!$act) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type'=>'error','text'=>"Activité introuvable."];
        header('Location: /inscriptions/create'); return;
      }

      $st = $pdo->prepare("SELECT id FROM inscriptions WHERE enfant_id=? AND activite_id=? FOR UPDATE");
      $st->execute([$enfantId, $activiteId]);
      if ($st->fetch()) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type'=>'warning','text'=>"Cet enfant est déjà inscrit à cette activité."];
        header('Location: /inscriptions?enfant_id='.$enfantId); return;
      }

      // 3) Si des certificats ont été uploadés maintenant → enregistrer en 'en_attente'
      if (!empty($uploads)) {
        $destDir = ROOT.'/public/uploads/certifs';
        if (!is_dir($destDir)) @mkdir($destDir, 0775, true);

        foreach ($uploads as $u) {
          $r   = $u['row'];
          $f   = $u['file'];
          $tmp = $f['tmp_name'];
          $safe = time().'_'.preg_replace('/[^a-zA-Z0-9_\.-]/','', $f['name']);
          $dest = $destDir.'/'.$safe;
          if (!move_uploaded_file($tmp, $dest)) {
            throw new \RuntimeException("Échec upload: ".$r['libelle']);
          }
          $web = '/uploads/certifs/'.$safe;

          // upsert en 'en_attente'
          $st = $pdo->prepare("INSERT INTO enfant_certificats (enfant_id, certificat_type_id, fichier_path, statut)
                               VALUES (?,?,?,'en_attente')
                               ON DUPLICATE KEY UPDATE fichier_path=VALUES(fichier_path), statut='en_attente', verified_at=NULL, verified_by=NULL");
          $st->execute([$enfantId, (int)$r['id'], $web]);
        }

        // Inscription en attente (la capacité sera vérifiée au moment de la promotion)
        $st = $pdo->prepare("INSERT INTO inscriptions (enfant_id, activite_id, statut) VALUES (?,?, 'en_attente')");
        $st->execute([$enfantId, $activiteId]);

        $pdo->commit();
        $_SESSION['flash'] = ['type'=>'info','text'=>"Certificat(s) envoyé(s). Inscription enregistrée en attente de validation du service."];
        header('Location: /inscriptions?enfant_id='.$enfantId);
        return;
      }

      // 4) Sinon (aucun requis OU tous déjà valides) → valider d'office si places
      $st = $pdo->prepare("SELECT id FROM inscriptions WHERE activite_id=? AND statut='valide' FOR UPDATE");
      $st->execute([$activiteId]);
      $valides = $st->rowCount();

      if ($valides >= (int)$act['capacity']) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type'=>'error','text'=>"Capacité atteinte : inscription refusée."];
        header('Location: /inscriptions/create'); return;
      }

      $st = $pdo->prepare("INSERT INTO inscriptions (enfant_id, activite_id, statut) VALUES (?,?, 'valide')");
      $st->execute([$enfantId, $activiteId]);

      $pdo->commit();
      $_SESSION['flash'] = ['type'=>'success','text'=>"Inscription validée."];
      header('Location: /inscriptions?enfant_id='.$enfantId);
    } catch (\Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      http_response_code(500);
      echo "Erreur lors de l'inscription: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
  }
}
