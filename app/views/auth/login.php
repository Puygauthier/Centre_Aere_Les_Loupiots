<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion staff — Les Loupiots</title>
  <link rel="stylesheet" href="<?= BASE_PATH ?>/style.css">
  <style>
    /* Titre grand (centré via .rainbow-title du CSS global) */
    .rainbow-title{ font-size:56px; }

    /* Champ mot de passe + œil aligné */
    .pw-wrap{ position:relative; width:100%; }
    .pw-wrap input[type="password"],
    .pw-wrap input[type="text"]{
      width:100%;
      padding-right:44px; /* place pour l’œil */
      box-sizing:border-box;
    }
    .pw-toggle{
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      width:28px; height:28px; display:flex; align-items:center; justify-content:center;
      cursor:pointer; user-select:none; font-size:18px; line-height:1; opacity:.85;
    }
    .pw-toggle:hover{ opacity:1; }

    /* >>> Boutons identiques ET même largeur */
    .actions-buttons{
      display:flex; gap:12px; margin-top:14px;
    }
    .actions-buttons .btn{
      flex:1;                    /* <-- même largeur */
      display:inline-flex; align-items:center; justify-content:center;
      height:44px; padding:0 16px; box-sizing:border-box;
      font-weight:700; font-size:16px; line-height:1; font-family:inherit;
      border-radius:12px; border:1px solid transparent;
      background:var(--brand); color:#fff; text-decoration:none; cursor:pointer;
      white-space:nowrap; text-align:center;
    }
    .actions-buttons .btn:hover{ background:var(--brand-2); }

    /* Carte étroite */
    .card--narrow{ max-width:420px; margin:0 auto; }
  </style>
</head>
<body>

<div class="container">

  <!-- Titre multicolore, centré et grand -->
  <h1 class="rainbow-title" aria-label="Connexion staff">
    <span>C</span><span>o</span><span>n</span><span>n</span><span>e</span><span>x</span><span>i</span><span>o</span><span>n</span>
    &nbsp;<span>s</span><span>t</span><span>a</span><span>f</span><span>f</span>
  </h1>

  <?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert alert-info">
      <?= htmlspecialchars((string)$f['text'], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <div class="card card--narrow">
    <form method="post" action="<?= BASE_PATH ?>/login" autocomplete="off">
      <label for="password">Mot de passe staff</label>
      <div class="pw-wrap">
        <input type="password" id="password" name="password" required>
        <span class="pw-toggle" id="pwToggle" aria-label="Afficher / masquer le mot de passe" title="Afficher / masquer">👁️</span>
      </div>

      <div class="actions-buttons">
        <a class="btn" href="<?= BASE_PATH ?>/">← Retour</a>
        <button type="submit" class="btn">Se connecter</button>
      </div>
    </form>
  </div>

</div>

<script>
  (function(){
    const input = document.getElementById('password');
    const toggle = document.getElementById('pwToggle');
    if (input && toggle){
      toggle.addEventListener('click', () => {
        const isPwd = input.type === 'password';
        input.type = isPwd ? 'text' : 'password';
        toggle.textContent = isPwd ? '🙈' : '👁️';
      });
    }
  })();
</script>

</body>
</html>
