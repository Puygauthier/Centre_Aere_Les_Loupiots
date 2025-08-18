<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Activités</title>
<body>
<?php $isStaff = !empty($_SESSION['is_staff']); ?>

<h1>Activités</h1>

<?php if ($isStaff): ?>
  <form method="post" action="<?= BASE_PATH ?>/logout" style="margin:10px 0;">
    <button type="submit">Se déconnecter (staff)</button>
  </form>
  <p style="margin:10px 0;">
    <a href="<?= BASE_PATH ?>/staff/certificats">Valider les certificats</a>
  </p>
<?php else: ?>
  <p style="margin:10px 0;"><a href="<?= BASE_PATH ?>/login">Espace staff</a></p>
<?php endif; ?>

<p><a href="<?= BASE_PATH ?>/inscriptions/create">+ Nouvelle inscription</a></p>

<table border="1" cellpadding="6">
  <tr>
    <th>Titre</th>
    <th>Catégorie</th>
    <th>Début</th>
    <th>Fin</th>
    <th>Capacité</th>
    <th>Restantes</th>
    <?php if ($isStaff): ?>
      <th>Inscriptions</th>
    <?php endif; ?>
  </tr>

  <?php foreach ($activites as $a): ?>
    <?php $rest = \App\Models\Activite::placesRestantes((int)($a['id'] ?? 0)); ?>
    <tr>
      <td><?= htmlspecialchars((string)($a['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '-' ?></td>
      <td><?= htmlspecialchars((string)($a['categorie'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '-' ?></td>
      <td><?= htmlspecialchars((string)($a['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '-' ?></td>
      <td><?= htmlspecialchars((string)($a['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '-' ?></td>
      <td><?= isset($a['capacity']) ? (int)$a['capacity'] : 0 ?></td>
      <td><?= (int)$rest ?></td>
      <?php if ($isStaff): ?>
        <td>
          <a href="<?= BASE_PATH ?>/activites/<?= (int)$a['id'] ?>/inscriptions">Voir inscriptions</a>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
