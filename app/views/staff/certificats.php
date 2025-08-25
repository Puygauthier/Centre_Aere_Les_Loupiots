<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Validation des certificats — Staff</title>
  <link rel="stylesheet" href="<?= BASE_PATH ?>/style.css">
  <style>
    /* On garde juste la taille du titre, on retire TOUTES les surcharges de tableau */
    .rainbow-title{ font-size:56px; }
  </style>
</head>
<body>

<div class="container">

  <h1 class="rainbow-title">
    <span>C</span><span>e</span><span>r</span><span>t</span><span>i</span><span>f</span><span>i</span><span>c</span><span>a</span><span>t</span><span>s</span>
  </h1>

  <div class="actions-bar">
    <div></div>
    <a class="btn" href="<?= BASE_PATH ?>/">← Retour activités</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Enfant</th>
        <th>Certificat</th>
        <th>Fichier</th>
        <th>Statut</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($certificats)): ?>
        <?php foreach ($certificats as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['enfant_nom'].' '.$c['enfant_prenom'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($c['libelle'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <?php if (!empty($c['fichier_path'])): ?>
                <a href="<?= BASE_PATH . $c['fichier_path'] ?>" target="_blank" rel="noopener">Voir le fichier</a>
              <?php else: ?> —
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($c['statut'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <form method="post" action="<?= BASE_PATH ?>/certificats/valider" onsubmit="return confirm('Valider ce certificat ?');">
                <input type="hidden" name="enfant_certificat_id" value="<?= (int)$c['id'] ?>">
                <button type="submit">Valider</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">Aucun certificat en attente.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php
  // Flash sous le tableau (centré, style rainbow si défini dans ton CSS)
  if (!empty($_SESSION['flash'])) {
    $f = $_SESSION['flash']; unset($_SESSION['flash']);
    $txt = (string)($f['text'] ?? '');
    $chars = function_exists('mb_str_split') ? mb_str_split($txt) : preg_split('//u', $txt, -1, PREG_SPLIT_NO_EMPTY);
    echo '<div class="flash-box rainbow-text">';
    foreach ($chars as $ch) echo '<span>'.htmlspecialchars($ch, ENT_QUOTES, 'UTF-8').'</span>';
    echo '</div>';
  }
  ?>

</div>

</body>
</html>
