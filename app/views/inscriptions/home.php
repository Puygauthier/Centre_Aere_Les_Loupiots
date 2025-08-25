<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accueil — Les Loupiots</title>
  <link rel="stylesheet" href="<?= BASE_PATH ?>/style.css">
  <style>
    /* Encadré flash aligné et centré comme une carte */
    .flash-box {
      max-width: 420px;
      margin: 16px auto 0;
      text-align: center;
      padding: 10px 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      background: #f6f6f6;
      font-weight: bold;
      font-size: 18px;
    }
    /* Texte multicolore lettre par lettre */
    .rainbow-text span { display:inline-block; font-weight:bold; font-size:20px; }
    .rainbow-text span:nth-child(1){color:#e11d48}
    .rainbow-text span:nth-child(2){color:#f97316}
    .rainbow-text span:nth-child(3){color:#eab308}
    .rainbow-text span:nth-child(4){color:#10b981}
    .rainbow-text span:nth-child(5){color:#3b82f6}
    .rainbow-text span:nth-child(6){color:#6366f1}
    .rainbow-text span:nth-child(7){color:#a855f7}
    .rainbow-text span:nth-child(8){color:#ec4899}
    .rainbow-text span:nth-child(9){color:#14b8a6}
  </style>
</head>
<body>

  <div class="container">
    <h1 class="rainbow-title">
      <span>A</span><span>c</span><span>t</span><span>i</span><span>v</span><span>i</span><span>t</span><span>é</span><span>s</span>
    </h1>

    <div class="actions-bar">
      <a class="btn btn-secondary" href="<?= BASE_PATH ?>/inscriptions/create">+ Nouvelle inscription</a>
      <a class="btn" href="<?= BASE_PATH ?>/login">Espace staff</a>
    </div>

    <table>
      <tr>
        <th>Titre</th>
        <th>Catégorie</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Capacité</th>
        <th>Restantes</th>
      </tr>

      <?php
      $h = static function ($v): string {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
      };
      ?>

      <?php foreach ($activites as $a): ?>
        <?php
          $titre      = $a['titre']       ?? $a['title']      ?? '';
          $categorie  = $a['categorie']   ?? $a['category']   ?? '';
          $dateDebut  = $a['date_debut']  ?? $a['debut']      ?? $a['start_at'] ?? '';
          $dateFin    = $a['date_fin']    ?? $a['fin']        ?? $a['end_at']   ?? '';
          $capacite   = $a['capacite']    ?? $a['capacity']   ?? $a['cap'] ?? null;
          $inscrits   = $a['inscrits']    ?? $a['booked']     ?? null;

          if (!isset($a['restantes'])) {
            $restantes = (is_numeric($capacite) && is_numeric($inscrits))
              ? max(0, (int)$capacite - (int)$inscrits)
              : null;
          } else {
            $restantes = $a['restantes'];
          }
        ?>
        <tr>
          <td><?= $h($titre) ?></td>
          <td><?= $h($categorie) ?></td>
          <td><?= $h($dateDebut) ?></td>
          <td><?= $h($dateFin) ?></td>
          <td><?= $h($capacite ?? '—') ?></td>
          <td><?= $h($restantes ?? '—') ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <?php
    // --- Affichage d'un flash message sous le tableau ---
    if (!empty($_SESSION['flash'])) {
      $f = $_SESSION['flash'];
      unset($_SESSION['flash']);
      $txt = (string)($f['text'] ?? '');
      if (function_exists('mb_str_split')) {
        $chars = mb_str_split($txt);
      } else {
        $chars = preg_split('//u', $txt, -1, PREG_SPLIT_NO_EMPTY);
      }
      echo '<div class="flash-box rainbow-text">';
      foreach ($chars as $ch) {
        echo '<span>'.htmlspecialchars($ch, ENT_QUOTES, 'UTF-8').'</span>';
      }
      echo '</div>';
    }
    ?>

  </div>

</body>
</html>
