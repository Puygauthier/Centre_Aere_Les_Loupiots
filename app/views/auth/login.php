<!doctype html><html lang="fr"><meta charset="utf-8">
<title>Connexion staff</title>
<body>
<?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div style="padding:10px;margin:10px 0;border:1px solid #ccc;background:#f6f6f6;">
    <?= htmlspecialchars((string)$f['text'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<h1>Connexion personnel</h1>
<form method="post" action="<?= BASE_PATH ?>/login">
  <label>Mot de passe staff</label>
  <input type="password" name="password" required>
  <button type="submit">Se connecter</button>
</form>

<p><a href="<?= BASE_PATH ?>/">← Retour</a></p>
</body></html>
