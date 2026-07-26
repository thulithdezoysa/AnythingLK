<?php /* admin/views/brands/index.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   BRANDS PAGE — RESPONSIVE
   Scoped to #brandsTable / #brandModal to avoid bleed.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Wrap enables horizontal scroll on tablets ──────────
   (the outer wrapper scrolls; .admin-table keeps its border-
   radius / overflow:hidden for clipping the rounded corners) */
.brands-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  /* thin custom scrollbar so it doesn't look broken */
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.15) transparent;
}
.brands-table-wrap::-webkit-scrollbar { height: 4px; }
.brands-table-wrap::-webkit-scrollbar-track { background: transparent; }
.brands-table-wrap::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,.15);
  border-radius: 4px;
}

/* Give the table a minimum width so columns never collapse
   on tablets (768-991 px) before card-layout takes over */
@media (min-width: 768px) {
  #brandsTable { min-width: 540px; }
}

/* ── 2. Mobile: table → stacked-card layout (< 768 px) ──── */
@media (max-width: 767px) {

  /* Let the container open up so cards breathe */
  .brands-table-wrap .admin-table { overflow: visible !important; }

  /* Hide column header row */
  #brandsTable thead { display: none; }

  /* ── Each <tr> becomes a 3-column grid card ─────────────
     Col 1 (52 px) : Logo
     Col 2 (1fr)   : Name · Slug · product count
     Col 3 (auto)  : Status badge + action buttons           */
  #brandsTable tbody tr {
    display: grid;
    grid-template-columns: 52px 1fr auto;
    grid-template-rows: auto auto auto;
    column-gap: 12px;
    row-gap: 4px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
  }
  #brandsTable tbody tr:last-child { border-bottom: none; }
  #brandsTable tbody tr:hover      { background: var(--surface2); }

  /* Reset every <td> to a plain block (remove table defaults) */
  #brandsTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }
  /* Keep row hover bg on tr, not individual cells */
  #brandsTable tbody tr:hover td { background: transparent !important; }

  /* ── td 1 : Logo — col 1, spans all 3 rows ── */
  #brandsTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1 / 4;
    align-self: center;
  }

  /* ── td 2 : Name — col 2, row 1 ── */
  #brandsTable tbody td:nth-child(2) {
    grid-column: 2;
    grid-row: 1;
    align-self: end;
  }

  /* ── td 3 : Slug — col 2, row 2 ── */
  #brandsTable tbody td:nth-child(3) {
    grid-column: 2;
    grid-row: 2;
    align-self: center;
  }

  /* ── td 4 : Product count — col 2, row 3 ──
     Adds "· N products" label inline via pseudo-elements    */
  #brandsTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 3;
    align-self: start;
    text-align: left !important;
    font-size: 11px;
    color: var(--text-muted);
  }
  #brandsTable tbody td:nth-child(4)::before { content: '· '; }
  #brandsTable tbody td:nth-child(4)::after  { content: ' products'; }

  /* ── td 5 : Status badge — col 3, row 1 ── */
  #brandsTable tbody td:nth-child(5) {
    grid-column: 3;
    grid-row: 1;
    align-self: center;
    text-align: right;
  }

  /* ── td 6 : Actions — col 3, rows 2-3 ── */
  #brandsTable tbody td:nth-child(6) {
    grid-column: 3;
    grid-row: 2 / 4;
    align-self: center;
    text-align: right;
  }
  /* Stack Edit / Delete buttons vertically */
  #brandsTable tbody td:nth-child(6) > div {
    flex-direction: column !important;
    gap: 4px !important;
  }

  /* ── Empty-state row: bypass grid, show centred message ── */
  #brandsTable tbody tr#emptyRow {
    display: block !important;
    border-bottom: none;
  }
  #brandsTable tbody tr#emptyRow td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── Very small phones (< 360 px): shrink logo column ────── */
@media (max-width: 359px) {
  #brandsTable tbody tr {
    grid-template-columns: 42px 1fr auto;
    column-gap: 8px;
  }
  #brandsTable tbody td:nth-child(1) img,
  #brandsTable tbody td:nth-child(1) > div {
    width: 32px !important;
    height: 32px !important;
  }
}

/* ── 3. Modal: bottom-sheet on phones (< 576 px) ─────────── */
@media (max-width: 575px) {
  #brandModal.modal-overlay {
    align-items: flex-end !important;
    padding: 0 !important;
  }
  #brandModal .modal-box {
    width: 100% !important;
    max-width: 100% !important;
    border-radius: var(--radius) var(--radius) 0 0 !important;
    max-height: 92vh;
    margin: 0 !important;
  }
}

/* ── 4. Page header: stack on very small screens ─────────── */
@media (max-width: 420px) {
  .brands-page-header { flex-direction: column; align-items: flex-start !important; }
  .brands-page-header .btn { width: 100%; justify-content: center; }
}
</style>

<!-- ── Page header ──────────────────────────────────────── -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 brands-page-header">
  <div>
    <h4>Brands
      <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span>
    </h4>
    <p style="margin:0;font-size:12px;color:var(--text-muted);">Manage product brands, slugs and logos.</p>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openBrandModal()">
    <i class="fa fa-plus me-1"></i>Add Brand
  </button>
</div>

<!-- ── Filter / search bar ─────────────────────────────── -->
<?php
$searchFields = [['name' => 'search', 'value' => $search, 'placeholder' => 'Search brand name or slug…']];
$filterFields = [];
$extraButtons = '';   // "Add Brand" lives in the page-header above
include __DIR__ . '/../layouts/_pagination.php';
?>

<!-- ── Brands table ─────────────────────────────────────── -->
<!-- Outer wrapper enables horizontal scroll on tablets      -->
<div class="brands-table-wrap">
  <div class="admin-table">
    <table class="table table-sm align-middle mb-0" id="brandsTable">
      <thead>
        <tr>
          <th style="width:48px;">Logo</th>
          <th>Name</th>
          <th>Slug</th>
          <th style="width:90px;text-align:center;">Products</th>
          <th style="width:84px;">Status</th>
          <th style="width:100px;">Actions</th>
        </tr>
      </thead>
      <tbody id="brandsBody">

      <?php foreach ($brands as $b): ?>
      <tr id="brand-row-<?= $b['id'] ?>" data-name="<?= strtolower(e($b['name'])) ?>">

        <!-- Logo -->
        <td>
          <?php if (!empty($b['logo'])): ?>
          <img src="<?= url('uploads/brands/' . $b['logo']) ?>"
               style="width:36px;height:36px;object-fit:contain;border-radius:7px;background:var(--surface2);padding:4px;display:block;">
          <?php else: ?>
          <div style="width:36px;height:36px;border-radius:7px;background:var(--surface2);display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-certificate" style="color:var(--text-muted);font-size:13px;"></i>
          </div>
          <?php endif; ?>
        </td>

        <!-- Name -->
        <td><span style="font-weight:600;font-size:13px;"><?= e($b['name']) ?></span></td>

        <!-- Slug -->
        <td>
          <code style="font-size:11px;background:var(--surface2);color:var(--cyan);padding:2px 7px;border-radius:5px;letter-spacing:.01em;white-space:nowrap;"><?= e($b['slug']) ?></code>
        </td>

        <!-- Product count -->
        <td style="text-align:center;font-size:13px;"><?= (int)$b['product_count'] ?></td>

        <!-- Status -->
        <td>
          <?php if ($b['status']): ?>
          <span class="badge" style="background:var(--green-dim);color:var(--green);">Active</span>
          <?php else: ?>
          <span class="badge" style="background:var(--red-dim);color:var(--red);">Inactive</span>
          <?php endif; ?>
        </td>

        <!-- Actions -->
        <td>
          <div style="display:flex;gap:5px;flex-wrap:nowrap;">
            <button title="Edit"
                    style="background:var(--cyan-dim);color:var(--cyan);border:none;padding:4px 10px;border-radius:6px;font-size:12px;cursor:pointer;white-space:nowrap;"
                    onclick="editBrand(<?= $b['id'] ?>, '<?= e(addslashes($b['name'])) ?>', '<?= e(addslashes($b['slug'])) ?>', <?= (int)$b['status'] ?>)">
              <i class="fa fa-pencil"></i>
            </button>
            <button class="btn-del-brand" data-id="<?= $b['id'] ?>" title="Disable"
                    style="background:var(--red-dim);color:var(--red);border:none;padding:4px 10px;border-radius:6px;font-size:12px;cursor:pointer;white-space:nowrap;">
              <i class="fa fa-trash"></i>
            </button>
          </div>
        </td>

      </tr>
      <?php endforeach; ?>

      <?php if (empty($brands)): ?>
      <tr id="emptyRow">
        <td colspan="6" class="text-center py-5" style="color:var(--text-muted);">No brands found.</td>
      </tr>
      <?php endif; ?>

      </tbody>
    </table>
  </div><!-- /.admin-table -->
</div><!-- /.brands-table-wrap -->

<!-- ── Pagination nav ───────────────────────────────────── -->
<?php $itemLabel = 'brands'; include __DIR__ . '/../layouts/_pagnav.php'; ?>

<!-- ═══════════════════════════════════════════════════════
     Add / Edit Modal
     ═══════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="brandModal" style="display:none;">
  <div class="modal-box" style="max-width:460px;width:90%;">

    <div class="modal-header">
      <span class="modal-title" id="brandModalTitle">Add Brand</span>
      <button class="modal-close" onclick="closeBrandModal()">×</button>
    </div>

    <form id="brandForm" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" id="brandId" name="id" value="">

      <div class="modal-body" style="display:flex;flex-direction:column;gap:16px;">

        <!-- Name -->
        <div>
          <label class="form-label">Brand Name *</label>
          <input type="text" name="name" id="brandName" class="form-control"
                 required placeholder="e.g. Samsung"
                 oninput="onBrandNameInput(this.value)">
        </div>

        <!-- Slug -->
        <div>
          <label class="form-label" style="display:flex;justify-content:space-between;align-items:center;">
            Slug
            <button type="button" onclick="regenBrandSlug()"
                    style="font-size:11px;background:none;border:none;color:var(--cyan);cursor:pointer;padding:0;">
              ↻ regenerate
            </button>
          </label>
          <input type="text" name="slug" id="brandSlug" class="form-control"
                 placeholder="auto-generated"
                 style="font-family:monospace;font-size:13px;">
          <small style="color:var(--text-muted);">Used in URLs. Must be unique.</small>
        </div>

        <!-- Logo upload -->
        <div>
          <label class="form-label">
            Logo <small style="color:var(--text-muted);font-weight:400;">(optional)</small>
          </label>
          <input type="file" name="logo" id="brandLogoInput" class="form-control" accept="image/*">
          <div id="brandLogoPreview" style="margin-top:8px;display:none;">
            <img id="brandLogoImg"
                 style="height:52px;object-fit:contain;border-radius:7px;background:var(--surface2);padding:5px;">
          </div>
        </div>

        <!-- Status -->
        <div>
          <label class="form-label">Status</label>
          <select name="status" id="brandStatus" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

      </div><!-- /.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeBrandModal()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBrandBtn">
          <i class="fa fa-save me-1"></i>Save Brand
        </button>
      </div>
    </form>

  </div><!-- /.modal-box -->
</div><!-- /#brandModal -->

<?php $extraScript = <<<'JS'
<script>
/* ── Slug helpers ──────────────────────────────────────── */
function makeSlug(v) {
  return v.toLowerCase().trim()
    .replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-')
    .replace(/-+/g, '-').replace(/^-|-$/g, '');
}
var slugLocked = false;
function onBrandNameInput(val) {
  if (!slugLocked) document.getElementById('brandSlug').value = makeSlug(val);
}
function regenBrandSlug() {
  document.getElementById('brandSlug').value = makeSlug(document.getElementById('brandName').value);
  slugLocked = false;
}
document.getElementById('brandSlug').addEventListener('input', () => { slugLocked = true; });

/* ── Modal open / close ────────────────────────────────── */
function openBrandModal() {
  slugLocked = false;
  document.getElementById('brandModal').style.display = 'flex';
  document.getElementById('brandName').focus();
}
function closeBrandModal() {
  document.getElementById('brandModal').style.display = 'none';
  document.getElementById('brandForm').reset();
  document.getElementById('brandId').value = '';
  document.getElementById('brandModalTitle').textContent = 'Add Brand';
  document.getElementById('brandLogoPreview').style.display = 'none';
  slugLocked = false;
}
function editBrand(id, name, slug, status) {
  document.getElementById('brandId').value     = id;
  document.getElementById('brandName').value   = name;
  document.getElementById('brandSlug').value   = slug;
  document.getElementById('brandStatus').value = status;
  document.getElementById('brandModalTitle').textContent = 'Edit Brand';
  slugLocked = true;
  openBrandModal();
}
document.getElementById('brandModal').addEventListener('click', function(e) {
  if (e.target === this) closeBrandModal();
});

/* ── Logo preview ──────────────────────────────────────── */
document.getElementById('brandLogoInput').addEventListener('change', function() {
  if (!this.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('brandLogoImg').src = e.target.result;
    document.getElementById('brandLogoPreview').style.display = '';
  };
  reader.readAsDataURL(this.files[0]);
});

/* ── Form submit ───────────────────────────────────────── */
document.getElementById('brandForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const id  = document.getElementById('brandId').value;
  const url = id ? 'admin/brands/update' : 'admin/brands/store';
  const btn = document.getElementById('saveBrandBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving…';
  const fd = new FormData(this);
  fd.append('_csrf', CSRF_TOKEN);
  $.ajax({
    url: SITE_URL + '/' + url, type: 'POST',
    data: fd, processData: false, contentType: false, dataType: 'json',
    success: res => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-1"></i>Save Brand';
      if (res.success) {
        Swal.fire({ icon: 'success', title: res.message, timer: 1200, showConfirmButton: false })
          .then(() => location.reload());
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    },
    error: () => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-1"></i>Save Brand';
      Swal.fire('Error', 'Request failed.', 'error');
    }
  });
});

/* ── Delete / disable ──────────────────────────────────── */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-del-brand');
  if (!btn) return;
  const id = btn.dataset.id;
  Swal.fire({
    title: 'Disable brand?',
    text: "Products won't be affected.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Disable'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/brands/delete', { id }, res => {
      if (res.success) {
        document.getElementById('brand-row-' + id)?.remove();
        Swal.fire({ icon: 'success', title: 'Brand disabled.', timer: 1200, showConfirmButton: false });
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    });
  });
});
</script>
JS;
?>
