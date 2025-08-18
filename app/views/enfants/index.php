<div class="card shadow-sm">
  <div class="card-body">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="h4 mb-0">Enfants</h2>
      <a class="btn btn-primary btn-sm" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') . '/enfants/ajouter' ?>">
        + Ajouter un enfant
      </a>
    </div>

    <?php if (empty($enfants)): ?>
      <div class="alert alert-info d-flex align-items-center justify-content-between">
        <div>Aucun enfant pour le moment.</div>
        <a class="btn btn-sm btn-outline-primary" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') . '/enfants/ajouter' ?>">
          Ajouter le premier
        </a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th style="width: 70px;">ID</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th style="width: 170px;">Date de naissance</th>
              <th>Responsable</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($enfants as $e): ?>
            <tr>
              <td><?= (int)$e['id'] ?></td>
              <td><?= htmlspecialchars($e['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($e['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($e['date_naissance'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(($e['resp_nom'] ?? '') . ' ' . ($e['resp_prenom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
