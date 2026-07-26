<?php
/**
 * Bottom pagination nav only (no search form).
 * Requires: $pagData, $total, $itemLabel, $perPage
 */
$_label = $itemLabel ?? 'items';
?>
<?php if (($pagData['total_pages'] ?? 1) > 1 || $total > 10): ?>
<nav class="admin-pag-nav d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
  <small class="text-muted">
    <?php if ($total > 0): ?>
      Showing <?= number_format($pagData['offset'] + 1) ?>–<?= number_format(min($pagData['offset'] + $pagData['per_page'], $total)) ?>
      of <?= number_format($total) ?> <?= $_label ?>
    <?php else: ?>
      No <?= $_label ?> found
    <?php endif; ?>
  </small>
  <?php if (($pagData['total_pages'] ?? 1) > 1): ?>
  <ul class="pagination pagination-sm mb-0">
    <li class="page-item <?= $pagData['has_prev'] ? '' : 'disabled' ?>">
      <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagData['current_page'] - 1])) ?>">‹</a>
    </li>
    <?php
    $__s = max(1, $pagData['current_page'] - 2);
    $__e = min($pagData['total_pages'], $__s + 4);
    if ($__s > 1): ?>
      <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>1])) ?>">1</a></li>
      <?php if ($__s > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
    <?php endif;
    for ($__i = $__s; $__i <= $__e; $__i++): ?>
    <li class="page-item <?= $__i === $pagData['current_page'] ? 'active' : '' ?>">
      <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $__i])) ?>"><?= $__i ?></a>
    </li>
    <?php endfor;
    if ($__e < $pagData['total_pages']): ?>
      <?php if ($__e < $pagData['total_pages'] - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
      <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$pagData['total_pages']])) ?>"><?= $pagData['total_pages'] ?></a></li>
    <?php endif; ?>
    <li class="page-item <?= $pagData['has_next'] ? '' : 'disabled' ?>">
      <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagData['current_page'] + 1])) ?>">›</a>
    </li>
  </ul>
  <?php endif; ?>
</nav>
<?php endif; ?>
