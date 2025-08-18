<!doctype html>
<html lang="fr">
<meta charset="utf-8">
<title>Nouvelle inscription</title>
<body>

<?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div style="padding:10px;margin:10px 0;border:1px solid #e00;background:#ffecec;color:#900;">
    <?= htmlspecialchars((string)$f['text'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<h1>Nouvelle inscription</h1>

<!-- Avertissements globaux pour les parents -->
<div style="margin:10px 0;padding:10px;border:1px dashed #999;background:#fafafa;">
  <ul style="margin:0 0 0 16px;">
    <li>Pour certaines activités, une <strong>pièce jointe</strong> (certificat) est <strong>obligatoire</strong>. Sans cette pièce jointe, <strong>la réservation ne peut pas être prise</strong>.</li>
    <li>Après vérification par le service, la structure se réserve le droit de <strong>refuser</strong> la réservation si la pièce n’est pas conforme.</li>
    <li>En cas de <strong>force majeure</strong>, la structure peut <strong>modifier le programme</strong> et <strong>annuler</strong> une sortie, sans obligation de reprogrammation.</li>
  </ul>
</div>

<form action="<?= BASE_PATH ?>/inscriptions" method="post" enctype="multipart/form-data" id="formInscription">
  <!-- ENFANT -->
  <label for="enfant_id">Enfant</label>
  <select name="enfant_id" id="enfant_id" required>
    <option value="">-- choisir --</option>
    <?php if (!empty($enfants)): ?>
      <?php foreach ($enfants as $e): ?>
        <option value="<?= (int)$e['id'] ?>">
          <?= htmlspecialchars((string)$e['nom'].' '.(string)$e['prenom'], ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    <?php endif; ?>
  </select>

  <!-- ACTIVITÉ -->
  <label for="activite">Activité</label>
  <select name="activite_id" id="activite" required>
    <option value="">-- choisir --</option>
    <?php if (!empty($activites)): ?>
      <?php foreach ($activites as $a): ?>
        <option value="<?= (int)$a['id'] ?>">
          <?= htmlspecialchars((string)($a['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
          (<?= htmlspecialchars((string)($a['categorie'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)
        </option>
      <?php endforeach; ?>
    <?php endif; ?>
  </select>

  <!-- CERTIFICATS REQUIS (affiché dynamiquement) -->
  <div id="zoneCertifs" style="margin-top:12px; display:none;">
    <p><strong>Certificats requis – pièces jointes obligatoires :</strong></p>
    <div id="inputsCertifs"></div>
  </div>

  <p style="margin-top:12px;">
    <button type="submit">Valider l'inscription</button>
    <a href="<?= BASE_PATH ?>/">Annuler</a>
  </p>
</form>

<script>
const BASE = "<?= BASE_PATH ?>";
const sel = document.getElementById('activite');
const zone = document.getElementById('zoneCertifs');
const inputs = document.getElementById('inputsCertifs');

async function majCertifs() {
  inputs.innerHTML = '';
  zone.style.display = 'none';
  const id = sel.value;
  if (!id) return;
  try {
    const r = await fetch(`${BASE}/activites/${id}/certifs`);
    if (!r.ok) return;
    const data = await r.json(); // [{id,code,libelle}]
    if (Array.isArray(data) && data.length) {
      zone.style.display = 'block';
      data.forEach(c => {
        const d = document.createElement('div');
        d.style.marginBottom = '8px';
        d.innerHTML = `
          <label>${c.libelle}</label><br>
          <input type="file" name="certif_${c.code}" accept=".pdf,.jpg,.jpeg,.png" />
        `;
        inputs.appendChild(d);
      });
    }
  } catch(e) { /* silencieux */ }
}
sel.addEventListener('change', majCertifs);
</script>
</body>
</html>
