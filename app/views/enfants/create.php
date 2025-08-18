<div class="card shadow-sm">
  <div class="card-body">
    <h2 class="h4 mb-3">Ajouter un enfant</h2>

    <?php if (!empty($formData['errors'] ?? [])): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($formData['errors'] as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= (defined('BASE_PATH') ? BASE_PATH : '') . '/enfants/ajouter' ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input name="nom" class="form-control" value="<?= htmlspecialchars($formData['nom'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Prénom</label>
          <input name="prenom" class="form-control" value="<?= htmlspecialchars($formData['prenom'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date de naissance</label>
          <input type="date" name="date_naissance" class="form-control"
                 value="<?= htmlspecialchars($formData['date_naissance'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Responsable</label>
          <select name="responsable_princ" class="form-select" required>
            <option value="">— choisir —</option>
            <?php foreach ($responsables as $r): ?>
              <?php $sel = (isset($formData['responsable_princ']) && (int)$formData['responsable_princ'] === (int)$r['id']) ? 'selected' : ''; ?>
              <option value="<?= (int)$r['id'] ?>" <?= $sel ?>>
                <?= htmlspecialchars($r['nom'] . ' ' . $r['prenom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-success" type="submit">Enregistrer</button>
        <a class="btn btn-outline-secondary" href="<?= (defined('BASE_PATH') ? BASE_PATH : '') . '/enfants' ?>">Annuler</a>
      </div>
    </form>
  </div>
</div>
