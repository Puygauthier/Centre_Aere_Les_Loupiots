<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Inscriptions</title>
<body>

<?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div style="padding:10px;margin:10px 0;border:1px solid #ccc;background:#f6f6f6;">
    <?= htmlspecialchars((string)$f['text'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<h1>Inscriptions</h1>

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

<p>
  <a href="<?= BASE_PATH ?>/inscriptions/create">+ Nouvelle inscription</a>
  |
  <a href="<?= BASE_PATH ?>/">← Retour activités</a>
</p>

<table border="1" cellpadding="6">
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
</body>
</html>
