<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Inscriptions — Les Loupiots</title>
  <link rel="stylesheet" href="<?= BASE_PATH ?>/style.css">
</head>
<body>

<div class="container">

  <h1 class="rainbow-title">
    <span>I</span><span>n</span><span>s</span><span>c</span><span>r</span><span>i</span><span>p</span><span>t</span><span>i</span><span>o</span><span>n</span><span>s</span>
  </h1>

  <form method="get" action="<?= BASE_PATH ?>/inscriptions" style="margin-bottom:12px;">
    <label>Filtrer par enfant :</label>
    <select name="enfant_id" onchange="this.form.submit()">
      <option value="0">Tous</option>
      <?php foreach ($enfants as $e): ?>
        <option value="<?= (int)$e['id'] ?>" <?= (isset($_GET['enfant_id']) && (int)$_GET['enfant_id'] === (int)$e['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($e['nom'].' '.$e['prenom'], ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    </select>
    <noscript><button type="submit">Filtrer</button></noscript>
  </form>

  <div class="actions-bar">
    <a class="btn btn-secondary" href="<?= BASE_PATH ?>/inscriptions/create">+ Nouvelle inscription</a>
    <a class="btn" href="<?= BASE_PATH ?>/">← Retour activités</a>
  </div>

  <table>
    <tr>
      <th>Enfant</th>
      <th>Activité</th>
      <th>Catégorie</th>
      <th>Date</th>
      <th>Statut</th>
      <th>Créée le</th>
    </tr>
    <?php if (!empty($inscriptions)): ?>
      <?php foreach ($inscriptions as $i): ?>
        <tr>
          <td><?= htmlspecialchars($i['enfant_nom'].' '.$i['enfant_prenom'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['titre'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['categorie'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['date_debut'] ?? '-') . ' → ' . (string)($i['date_fin'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['statut'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['created_at'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Aucune inscription.</td></tr>
    <?php endif; ?>
  </table>

  <?php
  // Message flash sous le tableau (centré, style rainbow)
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
