<!doctype html> 
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nouvelle inscription — Les Loupiots</title>
  <link rel="stylesheet" href="<?= BASE_PATH ?>/style.css">
  <style>
    /* Encadré info très visible (rouge) */
    .info-strong{
      max-width: 640px;
      margin: 0 auto 16px;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid #fca5a5;
      background: #fee2e2; /* rouge clair */
      color: #991b1b;      /* rouge foncé */
      font-weight: 800;
      text-align: left; /* pour une liste lisible */
    }
    /* Libellés multicolores (petit arc-en-ciel) */
    .rainbow-label span{ display:inline-block; font-weight:800; }
    .rainbow-label span:nth-child(1){color:#e11d48}
    .rainbow-label span:nth-child(2){color:#f97316}
    .rainbow-label span:nth-child(3){color:#eab308}
    .rainbow-label span:nth-child(4){color:#10b981}
    .rainbow-label span:nth-child(5){color:#3b82f6}
    .rainbow-label span:nth-child(6){color:#6366f1}
    .rainbow-label span:nth-child(7){color:#a855f7}
    .rainbow-label span:nth-child(8){color:#ec4899}
    .rainbow-label span:nth-child(9){color:#14b8a6}
    .form-grid{ max-width:640px; margin:0 auto; }
  </style>
</head>
<body>

<div class="container">

  <h1 class="rainbow-title">
    <span>N</span><span>o</span><span>u</span><span>v</span><span>e</span><span>l</span><span>l</span><span>e</span>
    &nbsp;<span>i</span><span>n</span><span>s</span><span>c</span><span>r</span><span>i</span><span>p</span><span>t</span><span>i</span><span>o</span><span>n</span>
  </h1>

  <!-- Avertissements globaux pour les parents (dans le cadre rouge) -->
  <div class="info-strong">
    <h2 style="margin:0 0 8px 0;font-size:18px;">Informations pour les parents</h2>
    <ul style="margin:0;padding-left:18px;font-weight:600;">
      <li>Pour certaines activités, une <strong>pièce jointe</strong> (certificat) est <strong>obligatoire</strong>. Sans cette pièce jointe, <strong>la réservation ne peut pas être prise</strong>.</li>
      <li>Après vérification par le service, la structure se réserve le droit de <strong>refuser</strong> la réservation si la pièce n’est pas conforme.</li>
      <li>En cas de <strong>force majeure</strong>, la structure peut <strong>modifier le programme</strong> et <strong>annuler</strong> une sortie, sans obligation de reprogrammation.</li>
    </ul>
  </div>

  <div class="card form-grid">
    <form method="post" action="<?= BASE_PATH ?>/inscriptions">

      <label for="enfant" class="rainbow-label" aria-label="Enfant">
        <span>E</span><span>n</span><span>f</span><span>a</span><span>n</span><span>t</span>
      </label>
      <select id="enfant" name="enfant_id" required>
        <?php if (!empty($enfants)) foreach ($enfants as $e): ?>
          <option value="<?= (int)$e['id'] ?>"><?= htmlspecialchars($e['nom'].' '.$e['prenom'], ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>

      <label for="activite" class="rainbow-label" aria-label="Activité" style="margin-top:12px;">
        <span>A</span><span>c</span><span>t</span><span>i</span><span>v</span><span>i</span><span>t</span><span>é</span>
      </label>
      <select id="activite" name="activite_id" required>
        <?php if (!empty($activites)) foreach ($activites as $a): ?>
          <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre'] ?? '—', ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>

      <div style="display:flex;justify-content:space-between;gap:12px;margin-top:14px;">
        <a class="btn btn-secondary" href="<?= BASE_PATH ?>/inscriptions">← Retour</a>
        <button type="submit">Valider l’inscription</button>
      </div>

    </form>
  </div>

</div>

</body>
</html>
