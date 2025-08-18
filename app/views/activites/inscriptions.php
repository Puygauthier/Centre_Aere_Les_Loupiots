<!doctype html><html lang="fr"><meta charset="utf-8">
<title>Inscriptions par activité</title>
<body>
<h1>Inscriptions – <?= htmlspecialchars((string)($activite['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
<p>
  <a href="<?= BASE_PATH ?>/">← Retour activités</a> |
  <a href="<?= BASE_PATH ?>/inscriptions/create">+ Nouvelle inscription</a>
</p>
<table border="1" cellpadding="6">
  <tr>
    <th>Enfant</th>
    <th>Statut</th>
    <th>Créée le</th>
  </tr>
  <?php if (!empty($inscriptions)): ?>
    <?php foreach ($inscriptions as $i): ?>
      <tr>
        <td><?= htmlspecialchars($i['nom'].' '.$i['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars((string)$i['statut'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars((string)$i['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="3">Aucune inscription pour cette activité.</td></tr>
  <?php endif; ?>
</table>
</body></html>
