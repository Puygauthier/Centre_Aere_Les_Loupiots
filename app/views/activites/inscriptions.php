<?php
// Vue: app/views/activites/inscriptions.php
// Objectif: même look que l'accueil, sans passer par le layout.

// Titre activité (array ou objet)
$titreActivite = 'Activité';
if (isset($activite)) {
    if (is_array($activite) && isset($activite['titre'])) $titreActivite = $activite['titre'];
    if (is_object($activite) && isset($activite->titre))   $titreActivite = $activite->titre;
}

// Données d'inscriptions
$rows = is_array($inscriptions ?? null) ? $inscriptions : [];

// Helper: enrobe chaque caractère dans <span> pour rainbow-title
$toSpans = static function (string $txt): string {
    // gestion UTF-8
    if (function_exists('mb_str_split')) {
        $chars = mb_str_split($txt);
    } else {
        $chars = preg_split('//u', $txt, -1, PREG_SPLIT_NO_EMPTY);
    }
    $out = '';
    foreach ($chars as $ch) {
        $out .= '<span>' . htmlspecialchars($ch, ENT_QUOTES, 'UTF-8') . '</span>';
    }
    return $out;
};
?>
<link rel="stylesheet" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/style.css?v=3">

<div class="container">
  <h1 class="rainbow-title">
    <?= $toSpans('Inscriptions — ' . (string)$titreActivite) ?>
  </h1>

  <div class="actions-bar">
    <a class="btn btn-secondary" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/inscriptions/create">+ Nouvelle inscription</a>
    <a class="btn" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') ?>/staff/certificats">Espace staff</a>
  </div>

  <?php if (!empty($rows)): ?>
    <table>
      <tr>
        <th>Enfant</th>
        <th>Statut</th>
        <th>Créée le</th>
      </tr>
      <?php foreach ($rows as $i): ?>
        <tr>
          <td>
            <?php
              $prenom = $i['prenom'] ?? ($i['enfant_prenom'] ?? '');
              $nom    = $i['nom']    ?? ($i['enfant_nom']    ?? '');
              echo htmlspecialchars(trim($prenom . ' ' . $nom), ENT_QUOTES, 'UTF-8');
            ?>
          </td>
          <td><?= htmlspecialchars((string)($i['statut'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string)($i['created_at'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <div class="card">Aucune inscription pour le moment.</div>
  <?php endif; ?>
</div>
