<?php
namespace App\Controllers;

use App\Core\Database;
use PDO;

class CertificatController
{
  private function requireStaff(): bool {
    if (empty($_SESSION['is_staff'])) {
      $_SESSION['flash'] = ['type'=>'error','text'=>"Espace réservé au personnel. Connectez-vous."];
      header('Location: /login');
      return false;
    }
    return true;
  }

  /** GET /staff/certificats : liste des certificats en attente (staff) */
  public function index(): void {
    if (!$this->requireStaff()) return;

    $pdo = Database::getPdo();
    $sql = "SELECT ec.id, ec.enfant_id, ec.certificat_type_id, ec.fichier_path, ec.statut, ec.created_at,
                   e.nom AS enfant_nom, e.prenom AS enfant_prenom,
                   ct.code, ct.libelle
            FROM enfant_certificats ec
            JOIN enfants e ON e.id = ec.enfant_id
            JOIN certificat_types ct ON ct.id = ec.certificat_type_id
            WHERE ec.statut = 'en_attente'
            ORDER BY ec.created_at DESC, ec.id DESC";
    $certificats = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    include ROOT . '/app/views/staff/certificats.php';
  }

  /** POST /certificats/valider : valide un certificat (staff) + tente de promouvoir les inscriptions en attente */
  public function valider(): void {
    if (!$this->requireStaff()) return;

    $id = (int)($_POST['enfant_certificat_id'] ?? 0);
    if ($id <= 0) {
      $_SESSION['flash'] = ['type'=>'error','text'=>"ID de certificat invalide."];
      header('Location: /staff/certificats'); return;
    }

    $pdo = Database::getPdo();
    try {
      $pdo->beginTransaction();

      // Lock la ligne du certificat et récupère l’enfant
      $st = $pdo->prepare("SELECT enfant_id, certificat_type_id FROM enfant_certificats WHERE id=? FOR UPDATE");
      $st->execute([$id]);
      $row = $st->fetch(PDO::FETCH_ASSOC);
      if (!$row) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type'=>'error','text'=>"Certificat introuvable."];
        header('Location: /staff/certificats'); return;
      }

      // Valide le certificat
      $verifierId = 1; // pas de comptes staff individuels pour l’instant
      $st = $pdo->prepare("UPDATE enfant_certificats SET statut='valide', verified_at=NOW(), verified_by=? WHERE id=?");
      $st->execute([$verifierId, $id]);
      $enfantId = (int)$row['enfant_id'];

      $pdo->commit();
    } catch (\Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $_SESSION['flash'] = ['type'=>'error','text'=>"Erreur validation: ".$e->getMessage()];
      header('Location: /staff/certificats'); return;
    }

    // Après validation, essayer de promouvoir les inscriptions en attente de cet enfant
    $this->promotePendingInscriptions($enfantId);

    $_SESSION['flash'] = ['type'=>'success','text'=>"Certificat validé. Les inscriptions éligibles ont été mises à jour."];
    header('Location: /staff/certificats');
  }

  /** Essaie de passer en 'valide' toutes les inscriptions en attente de l'enfant si tous les certifs requis sont valides et s'il reste de la place */
  private function promotePendingInscriptions(int $enfantId): void {
    $pdo = Database::getPdo();

    // Récupère les inscriptions en attente pour cet enfant
    $st = $pdo->prepare("SELECT i.id, i.activite_id FROM inscriptions i WHERE i.enfant_id=? AND i.statut='en_attente'");
    $st->execute([$enfantId]);
    $pendings = $st->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pendings as $ins) {
      $insId = (int)$ins['id'];
      $actId = (int)$ins['activite_id'];

      // Vérifie s'il reste des certificats requis manquants
      $st2 = $pdo->prepare("
        SELECT COUNT(*)
        FROM activite_certificats ac
        WHERE ac.activite_id = ?
          AND NOT EXISTS (
            SELECT 1 FROM enfant_certificats ec
            WHERE ec.enfant_id = ?
              AND ec.certificat_type_id = ac.certificat_type_id
              AND ec.statut = 'valide'
          )
      ");
      $st2->execute([$actId, $enfantId]);
      $missing = (int)$st2->fetchColumn();
      if ($missing > 0) continue; // toujours des certifs manquants → on passe

      // Si tous les certifs requis sont valides, tenter la promotion avec contrôle de capacité
      try {
        $pdo->beginTransaction();

        // Lock activité
        $st3 = $pdo->prepare("SELECT id, capacity FROM activites WHERE id=? FOR UPDATE");
        $st3->execute([$actId]);
        $act = $st3->fetch(PDO::FETCH_ASSOC);
        if (!$act) { $pdo->rollBack(); continue; }

        // Lock les inscriptions valides pour compter
        $st4 = $pdo->prepare("SELECT id FROM inscriptions WHERE activite_id=? AND statut='valide' FOR UPDATE");
        $st4->execute([$actId]);
        $valides = $st4->rowCount();

        if ($valides >= (int)$act['capacity']) {
          // plus de place, on laisse en attente
          $pdo->rollBack();
          continue;
        }

        // Promote
        $st5 = $pdo->prepare("UPDATE inscriptions SET statut='valide' WHERE id=?");
        $st5->execute([$insId]);

        $pdo->commit();
      } catch (\Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // on ignore l’erreur et on continue
      }
    }
  }
}

