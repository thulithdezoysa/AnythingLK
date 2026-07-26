<?php
/**
 * Reusable admin pagination partial.
 * Requires: $pagData, $total, $itemLabel (e.g. 'reviews'), $perPage
 * Optional: $searchFields (array of [name, value, placeholder] for search form)
 *           $filterFields (array of select fields: [name, value, options=[val=>label]])
 *           $extraButtons (raw HTML string for top-right area)
 */
$_label   = $itemLabel ?? 'items';
$_perPage = $perPage   ?? 25;
?>

<!-- Search + filter toolbar -->
<form method="GET" class="admin-filter-bar mb-3">
  <?php if (!empty($searchFields)): foreach ($searchFields as $_sf): ?>
  <div class="afb-search">
    <input type="text" name="<?= e($_sf['name']) ?>" value="<?= e($_sf['value']) ?>"
           placeholder="<?= e($_sf['placeholder'] ?? 'Search…') ?>"
           class="form-control form-control-sm afb-input">
  </div>
  <?php endforeach; endif; ?>

  <?php if (!empty($filterFields)): foreach ($filterFields as $_ff): ?>
  <div class="afb-select">
    <select name="<?= e($_ff['name']) ?>" class="form-select form-select-sm afb-input">
      <?php foreach ($_ff['options'] as $_fv => $_fl): ?>
      <option value="<?= e($_fv) ?>" <?= ($_ff['value'] == $_fv) ? 'selected' : '' ?>><?= e($_fl) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endforeach; endif; ?>

  <div class="afb-per-page">
    <select name="per_page" class="form-select form-select-sm afb-input" onchange="this.form.submit()" aria-label="Per page">
      <?php foreach ([10,25,50,100] as $_n): ?>
      <option value="<?= $_n ?>" <?= $_perPage == $_n ? 'selected' : '' ?>><?= $_n ?> / page</option>
      <?php endforeach; ?>
    </select>
  </div>

  <button type="submit" class="btn btn-primary btn-sm">Search</button>
  <a href="?" class="btn btn-outline-secondary btn-sm">Reset</a>

  <?php if (!empty($extraButtons)): ?>
    <?= $extraButtons ?>
  <?php endif; ?>

  <!-- preserve GET params that are NOT already rendered as form inputs -->
  <?php
  $_formFields = array_merge(
    array_column($searchFields ?? [], 'name'),
    array_column($filterFields ?? [], 'name'),
    ['per_page', 'page']
  );
  foreach ($_GET as $_gk => $_gv): if (in_array($_gk, $_formFields, true)) continue; ?>
    <input type="hidden" name="<?= e($_gk) ?>" value="<?= e($_gv) ?>">
  <?php endforeach; ?>
</form>

<?php include __DIR__ . '/_pagnav.php'; ?>
