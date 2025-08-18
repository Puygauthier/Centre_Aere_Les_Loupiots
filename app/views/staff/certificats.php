<!doctype html><html lang="fr"><meta charset="utf-8">
<title>Validation des certificats (staff)</title>
<body>
<h1>Validation des certificats</h1>

<?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div style="padding:10px;margin:10px 0;border:1px solid #ccc;background:#f6f6f6;">
    <?= htmlspecialchars((string)$f['text'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<p>
  <a href="<?= BASE_PATH ?>/">← Retour activités</a>
</p>

<table border="1" cellpadding="6">
  <tr>
    <th>Enfant</th>
    <th>Certificat</th>
    <th>Fichier</th>
    <th>Statut</th>
    <th>Action</th>
  </tr>
  <?php if (!empty($certificats)): ?>
    <?php foreach ($certificats as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['enfant_nom'].' '.$c['enfant_prenom'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($c['libelle'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          <?php if (!empty($c['fichier_path'])): ?>
            <a href="<?= BASE_PATH . $c['fichier_path'] ?>" target="_blank" rel="noopener">Voir le fichier</a>
          <?php else: ?>
            -
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
</table>
</body></html>
