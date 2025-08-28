<?php /* Layout Bootstrap */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Centre Aéré' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS global Loupiots -->
  <link rel="stylesheet" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/style.css?v=3">
</head>
<body class="bg-light">
<?php
  // Base d’URL de l’app (définie dans public/index.php)
  $base = defined('BASE_PATH') ? BASE_PATH : '';
  // URL actuelles (pour activer l’onglet courant)
  $current   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
  $homeUrl   = $base !== '' ? $base . '/' : '/';
  $enfantsUrl= $base . '/enfants';
  $seancesUrl= $base . '/seances';
  $isHome    = ($current === $homeUrl) || ($current === $base) || ($current === $base . '/index.php');
  $isEnfants = ($current === $enfantsUrl);
  $isSeances = ($current === $seancesUrl);
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="<?= $homeUrl ?>">Centre Aéré</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link<?= $isHome ? ' active' : '' ?>" href="<?= $homeUrl ?>">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= $isEnfants ? ' active' : '' ?>" href="<?= $enfantsUrl ?>">Enfants</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= $isSeances ? ' active' : '' ?>" href="<?= $seancesUrl ?>">Séances</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if (isset($contentView)) require $contentView; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
