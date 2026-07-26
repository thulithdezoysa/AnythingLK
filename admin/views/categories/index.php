<?php /* admin/views/categories/index.php */
$totalCats = count($allCats);
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Categories <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= $totalCats ?></span></h4>
    <p class="page-subtitle">Unlimited nested categories. Sub-categories inherit depth automatically.</p>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openCatModal()">
    <i class="fa fa-plus me-1"></i>Add Category
  </button>
</div>

<!-- Category tree -->
<div class="admin-table">
  <?php
  function renderCatTree(array $cats, int $depth = 0): void {
    foreach ($cats as $cat):
      $hasChildren = !empty($cat['_children']);
      $indent      = $depth * 20;
  ?>
  <div class="d-flex align-items-center px-3 py-2 border-bottom cat-tree-row" id="cat-<?= $cat['id'] ?>"
       style="background:<?= $depth > 0 ? 'rgba(0,212,255,0.015)' : 'transparent' ?>;">

    <div class="flex-grow-1 d-flex align-items-center gap-2" style="padding-left:<?= $indent ?>px;">
      <i class="fa <?= $hasChildren ? 'fa-folder' : 'fa-tag' ?>"
         style="font-size:12px;color:<?= $hasChildren ? 'var(--amber)' : 'var(--text-muted)' ?>;flex-shrink:0;width:14px;"></i>
      <span class="fw-semibold" style="font-size:13px;"><?= e($cat['name']) ?></span>
      <span style="font-size:11px;color:var(--text-muted);">/ <?= e($cat['slug']) ?></span>
      <span class="badge" style="background:var(--cyan-dim);color:var(--cyan);font-size:10px;padding:2px 7px;border-radius:6px;">
        <?= $cat['_depth'] > 0 ? 'L'.$cat['_depth'] : 'Root' ?>
      </span>
      <?php if (!$cat['status']): ?>
      <span class="badge" style="background:var(--red-dim);color:var(--red);font-size:10px;padding:2px 7px;border-radius:6px;">Inactive</span>
      <?php endif; ?>
    </div>

    <div style="display:flex;gap:6px;flex-shrink:0;">
      <button style="background:var(--cyan-dim);color:var(--cyan);border:none;padding:3px 10px;border-radius:6px;font-size:12px;cursor:pointer;"
              onclick="editCat(<?= $cat['id'] ?>, '<?= e(addslashes($cat['name'])) ?>', <?= (int)($cat['parent_id'] ?? 0) ?>, '<?= e(addslashes($cat['icon'] ?? '')) ?>', <?= (int)$cat['sort_order'] ?>, <?= (int)$cat['status'] ?>)">
        <i class="fa fa-pencil"></i>
      </button>
      <?php if (!$hasChildren): ?>
      <button class="btn-del-cat" data-id="<?= $cat['id'] ?>"
              style="background:var(--red-dim);color:var(--red);border:none;padding:3px 10px;border-radius:6px;font-size:12px;cursor:pointer;"
              title="Disable">
        <i class="fa fa-trash"></i>
      </button>
      <?php else: ?>
      <button disabled title="Has sub-categories"
              style="background:var(--surface2);color:var(--text-muted);border:none;padding:3px 10px;border-radius:6px;font-size:12px;cursor:not-allowed;opacity:.4;">
        <i class="fa fa-trash"></i>
      </button>
      <?php endif; ?>
    </div>
  </div>
  <?php
      if ($hasChildren) renderCatTree($cat['_children'], $depth + 1);
    endforeach;
  }
  renderCatTree($tree);
  if (empty($tree)):
  ?>
  <div class="text-center text-muted py-5">No categories yet. Click "Add Category" to create one.</div>
  <?php endif; ?>
</div>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="catModal">
  <div class="modal-box">
    <div class="modal-header">
      <span class="modal-title" id="catModalTitle">Add Category</span>
      <button class="modal-close" onclick="closeCatModal()">×</button>
    </div>
    <form id="catForm" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" id="catId" name="id" value="">
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">

        <div>
          <label class="form-label">Category Name *</label>
          <input type="text" name="name" id="catName" class="form-control" required placeholder="e.g. Electronics">
        </div>

        <div>
          <label class="form-label">Parent Category</label>
          <select name="parent_id" id="catParent" class="form-control">
            <option value="">— None (Root Level)</option>
            <?php foreach ($flatList as $fc): ?>
            <option value="<?= $fc['id'] ?>"><?= e($fc['_label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label">Icon <small style="color:var(--text-muted);font-weight:400;">(<a href="https://fontawesome.com/icons" target="_blank" style="color:var(--cyan);">FA class</a>)</small></label>
            <input type="text" name="icon" id="catIcon" class="form-control" placeholder="fa-tag">
          </div>
          <div>
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" id="catSort" class="form-control" value="0" min="0">
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
          </div>
          <div>
            <label class="form-label">Status</label>
            <select name="status" id="catStatus" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeCatModal()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveCatBtn">Save Category</button>
      </div>
    </form>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
function openCatModal() {
  document.getElementById('catModal').style.display = 'flex';
}
function closeCatModal() {
  document.getElementById('catModal').style.display = 'none';
  document.getElementById('catForm').reset();
  document.getElementById('catId').value = '';
  document.getElementById('catModalTitle').textContent = 'Add Category';
}
function editCat(id, name, parentId, icon, sort, status) {
  document.getElementById('catId').value     = id;
  document.getElementById('catName').value   = name;
  document.getElementById('catParent').value = parentId || '';
  document.getElementById('catIcon').value   = icon;
  document.getElementById('catSort').value   = sort;
  document.getElementById('catStatus').value = status;
  document.getElementById('catModalTitle').textContent = 'Edit Category';
  openCatModal();
}

// Form submit
document.getElementById('catForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const id  = document.getElementById('catId').value;
  const url = id ? 'admin/categories/update' : 'admin/categories/store';
  const btn = document.getElementById('saveCatBtn');
  btn.disabled = true; btn.textContent = 'Saving...';
  const fd = new FormData(this); fd.append('_csrf', CSRF_TOKEN);
  $.ajax({
    url: SITE_URL + '/' + url, type: 'POST',
    data: fd, processData: false, contentType: false, dataType: 'json',
    success: res => {
      btn.disabled = false; btn.textContent = 'Save Category';
      if (res.success) {
        Swal.fire({ icon: 'success', title: res.message, timer: 1200, showConfirmButton: false })
          .then(() => location.reload());
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    },
    error: () => { btn.disabled = false; btn.textContent = 'Save Category'; Swal.fire('Error', 'Request failed.', 'error'); }
  });
});

// Delete
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-del-cat');
  if (!btn) return;
  const id = btn.dataset.id;
  Swal.fire({ title: 'Disable category?', text: 'Products won\'t be affected.',
    icon: 'warning', showCancelButton: true,
    confirmButtonColor: '#ef4444', confirmButtonText: 'Disable' })
  .then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/categories/delete', { id }, res => {
      if (res.success) {
        document.getElementById('cat-' + id)?.remove();
        Swal.fire({ icon: 'success', title: 'Category disabled.', timer: 1200, showConfirmButton: false });
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    });
  });
});

// Close on backdrop click
document.getElementById('catModal').addEventListener('click', function(e) {
  if (e.target === this) closeCatModal();
});
</script>
JS;
?>
