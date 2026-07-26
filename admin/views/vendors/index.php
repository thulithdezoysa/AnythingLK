<?php /* admin/views/vendors/index.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   VENDORS PAGE — RESPONSIVE
   All selectors are scoped to #vendorsTable / #vendorModal.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Wrap enables horizontal scroll at all widths ───────
   The outer wrapper scrolls; .admin-table keeps its border-
   radius / overflow:hidden for rounded-corner clipping.     */
.vendors-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.15) transparent;
}
.vendors-table-wrap::-webkit-scrollbar { height: 4px; }
.vendors-table-wrap::-webkit-scrollbar-track { background: transparent; }
.vendors-table-wrap::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,.15);
  border-radius: 4px;
}

/* Ensure columns have a minimum width on tablets */
@media (min-width: 768px) {
  #vendorsTable { min-width: 640px; }
}

/* ── 2. Mobile (< 768 px): table → stacked card layout ──── */
@media (max-width: 767px) {

  /* Let the container open up so cards breathe */
  .vendors-table-wrap .admin-table { overflow: visible !important; }

  /* Kill the table's own min-width so it doesn't force scroll */
  #vendorsTable { min-width: 0 !important; }

  /* Hide the column header row */
  #vendorsTable thead { display: none; }

  /* ── Each <tr> becomes a 3-column CSS grid card ──────────
       Col A (1fr)  : primary text content (name, contact…)
       Col B (1fr)  : secondary content   (email, counts…)
       Col C (auto) : action buttons (always right-edge)      */
  #vendorsTable tbody tr {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    grid-template-rows: auto auto auto auto;
    column-gap: 10px;
    row-gap: 5px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
  }
  #vendorsTable tbody tr:last-child { border-bottom: none; }
  #vendorsTable tbody tr:hover      { background: var(--surface2); }

  /* Strip every <td> back to a plain block */
  #vendorsTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }
  #vendorsTable tbody tr:hover td { background: transparent !important; }

  /* ── td 1: Company name + phone ─────────────────────────
     Spans cols A+B (full-width minus action col)            */
  #vendorsTable tbody td:nth-child(1) {
    grid-column: 1 / 3;
    grid-row: 1;
    align-self: center;
  }

  /* ── td 2: Type badge — col A, row 2 ─────────────────── */
  #vendorsTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
    align-self: center;
  }

  /* ── td 3: Contact name — col A, row 3 ───────────────── */
  #vendorsTable tbody td:nth-child(3) {
    grid-column: 1;
    grid-row: 3;
    font-size: 12px;
    color: var(--text-dim);
  }

  /* ── td 4: Email address — col B, row 3 ──────────────── */
  #vendorsTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 3;
    font-size: 12px;
    color: var(--text-dim);
    /* Truncate long emails gracefully */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-width: 0;
  }

  /* ── td 5: Products count — col A, row 4 ─────────────── */
  #vendorsTable tbody td:nth-child(5) {
    grid-column: 1;
    grid-row: 4;
    font-size: 11px;
    color: var(--text-muted);
    text-align: left !important;
  }
  #vendorsTable tbody td:nth-child(5)::after { content: ' products'; }

  /* ── td 6: Purchase-order count — col B, row 4 ───────── */
  #vendorsTable tbody td:nth-child(6) {
    grid-column: 2;
    grid-row: 4;
    font-size: 11px;
    color: var(--text-muted);
    text-align: left !important;
  }
  #vendorsTable tbody td:nth-child(6)::after { content: ' POs'; }

  /* ── td 7: Status badge — col B, row 2 ───────────────── */
  #vendorsTable tbody td:nth-child(7) {
    grid-column: 2;
    grid-row: 2;
    align-self: center;
    text-align: right;
  }

  /* ── td 8: Actions — col C, spans all 4 rows ─────────── */
  #vendorsTable tbody td:nth-child(8) {
    grid-column: 3;
    grid-row: 1 / 5;
    align-self: center;
  }
  /* keep buttons side-by-side (they're small icon-only btns) */
  #vendorsTable tbody td:nth-child(8) > div {
    flex-direction: column !important;
    gap: 4px !important;
  }

  /* ── Empty-state row: bypass grid, show centred message ── */
  #vendorsTable tbody tr.vnd-empty-row {
    display: block !important;
    border-bottom: none;
  }
  #vendorsTable tbody tr.vnd-empty-row td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── Very small phones (< 360 px): tighten gaps ────────── */
@media (max-width: 359px) {
  #vendorsTable tbody tr {
    grid-template-columns: 1fr 1fr 52px;
    column-gap: 6px;
    padding: 10px 12px;
  }
}

/* ── 3. Modal form: 2-col grids → 1-col on phones ──────── */
@media (max-width: 575px) {
  .modal-row-2col { grid-template-columns: 1fr !important; }
}

/* ── 4. Modal: bottom-sheet on small phones ────────────── */
@media (max-width: 575px) {
  #vendorModal.modal-overlay {
    align-items: flex-end !important;
    padding: 0 !important;
  }
  #vendorModal .modal-box {
    width: 100% !important;
    max-width: 100% !important;
    border-radius: var(--radius) var(--radius) 0 0 !important;
    max-height: 92vh;
    margin: 0 !important;
  }
}

/* ── 5. Page header stacks on very small phones ─────────── */
@media (max-width: 420px) {
  .vendors-page-header {
    flex-direction: column;
    align-items: flex-start !important;
  }
  .vendors-page-header > .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<!-- ── Page header ──────────────────────────────────────── -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 vendors-page-header">
  <div>
    <h4>Vendors
      <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span>
    </h4>
    <p style="margin:0;font-size:12px;color:var(--text-muted);">Manage vendors and suppliers linked to products and purchase orders.</p>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openVendorModal()">
    <i class="fa fa-plus me-1"></i>Add Vendor
  </button>
</div>

<!-- ── Search / filter bar ─────────────────────────────── -->
<?php
$searchFields = [['name' => 'search', 'value' => $search, 'placeholder' => 'Search company, contact, email…']];
$filterFields = [];
$extraButtons = '';   // "Add Vendor" is in the page-header above
include __DIR__ . '/../layouts/_pagination.php';
?>

<!-- ── Vendors table ─────────────────────────────────────
     .vendors-table-wrap handles scroll; on mobile we switch
     to a card layout via CSS grid.                          -->
<div class="vendors-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="vendorsTable">
    <thead>
      <tr>
        <th>Company</th>
        <th style="width:90px;">Type</th>
        <th style="width:140px;">Contact</th>
        <th style="width:180px;">Email</th>
        <th style="width:74px;text-align:center;">Products</th>
        <th style="width:54px;text-align:center;">POs</th>
        <th style="width:80px;">Status</th>
        <th style="width:96px;">Actions</th>
      </tr>
    </thead>
    <tbody>

    <?php foreach ($vendors as $v): ?>
    <tr id="vendor-row-<?= $v['id'] ?>">

      <!-- Company + phone -->
      <td>
        <span style="font-weight:600;font-size:13px;"><?= e($v['company_name']) ?></span>
        <?php if (!empty($v['phone'])): ?>
        <div style="font-size:11px;color:var(--text-muted);margin-top:1px;"><?= e($v['phone']) ?></div>
        <?php endif; ?>
      </td>

      <!-- Type -->
      <td>
        <span class="badge" style="background:var(--cyan-dim);color:var(--cyan);"><?= ucfirst($v['type']) ?></span>
      </td>

      <!-- Contact name -->
      <td style="font-size:12px;color:var(--text-dim);"><?= e($v['contact_name'] ?? '—') ?></td>

      <!-- Email -->
      <td style="font-size:12px;color:var(--text-dim);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($v['email'] ?? '—') ?></td>

      <!-- Product count -->
      <td style="text-align:center;font-size:13px;"><?= (int)$v['product_count'] ?></td>

      <!-- PO count -->
      <td style="text-align:center;font-size:13px;"><?= (int)$v['po_count'] ?></td>

      <!-- Status -->
      <td>
        <?php if ($v['status'] === 'active'): ?>
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
                  onclick="editVendor(<?= $v['id'] ?>,'<?= e(addslashes($v['company_name'])) ?>','<?= e(addslashes($v['contact_name'] ?? '')) ?>','<?= e(addslashes($v['email'] ?? '')) ?>','<?= e(addslashes($v['phone'] ?? '')) ?>','<?= $v['type'] ?>','<?= e(addslashes($v['address'] ?? '')) ?>','<?= e(addslashes($v['notes'] ?? '')) ?>')">
            <i class="fa fa-pencil"></i>
          </button>
          <button class="btn-del-vendor" data-id="<?= $v['id'] ?>" title="Deactivate"
                  style="background:var(--red-dim);color:var(--red);border:none;padding:4px 10px;border-radius:6px;font-size:12px;cursor:pointer;white-space:nowrap;">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </td>

    </tr>
    <?php endforeach; ?>

    <?php if (empty($vendors)): ?>
    <tr class="vnd-empty-row">
      <td colspan="8" class="text-center" style="color:var(--text-muted);padding:40px 14px;">
        No vendors yet. Click <strong>Add Vendor</strong> to create one.
      </td>
    </tr>
    <?php endif; ?>

    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.vendors-table-wrap -->

<!-- ── Pagination nav ───────────────────────────────────── -->
<?php $itemLabel = 'vendors'; include __DIR__ . '/../layouts/_pagnav.php'; ?>

<!-- ═══════════════════════════════════════════════════════
     Add / Edit Vendor Modal
     ═══════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="vendorModal" style="display:none;">
  <div class="modal-box" style="max-width:520px;width:90%;">

    <div class="modal-header">
      <span class="modal-title" id="vendorModalTitle">Add Vendor</span>
      <button class="modal-close" onclick="closeVendorModal()">×</button>
    </div>

    <form id="vendorForm" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" id="vendorId" name="id" value="">

      <div class="modal-body" style="display:flex;flex-direction:column;gap:16px;">

        <!-- Company Name + Type (2-col → 1-col on mobile) -->
        <div class="modal-row-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label">Company Name *</label>
            <input type="text" name="company_name" id="vCompany" class="form-control"
                   required placeholder="e.g. Acme Ltd">
          </div>
          <div>
            <label class="form-label">Type</label>
            <select name="type" id="vType" class="form-control">
              <option value="vendor">Vendor</option>
              <option value="supplier">Supplier</option>
            </select>
          </div>
        </div>

        <!-- Contact Person + Phone (2-col → 1-col on mobile) -->
        <div class="modal-row-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label">Contact Person</label>
            <input type="text" name="contact_name" id="vContact" class="form-control"
                   placeholder="Full name">
          </div>
          <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" id="vPhone" class="form-control"
                   placeholder="+94 77 000 0000">
          </div>
        </div>

        <!-- Email -->
        <div>
          <label class="form-label">Email</label>
          <input type="email" name="email" id="vEmail" class="form-control"
                 placeholder="contact@company.com">
        </div>

        <!-- Address -->
        <div>
          <label class="form-label">Address</label>
          <textarea name="address" id="vAddress" class="form-control" rows="2"
                    placeholder="Street, City"></textarea>
        </div>

        <!-- Notes -->
        <div>
          <label class="form-label">Notes</label>
          <textarea name="notes" id="vNotes" class="form-control" rows="2"
                    placeholder="Internal notes…"></textarea>
        </div>

        <!-- Status -->
        <div>
          <label class="form-label">Status</label>
          <select name="status" id="vStatus" class="form-control">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

      </div><!-- /.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeVendorModal()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveVendorBtn">
          <i class="fa fa-save me-1"></i>Save Vendor
        </button>
      </div>
    </form>

  </div><!-- /.modal-box -->
</div><!-- /#vendorModal -->

<?php $extraScript = <<<'JS'
<script>
/* ── Modal open / close ────────────────────────────────── */
function openVendorModal() {
  document.getElementById('vendorModal').style.display = 'flex';
  document.getElementById('vCompany').focus();
}
function closeVendorModal() {
  document.getElementById('vendorModal').style.display = 'none';
  document.getElementById('vendorForm').reset();
  document.getElementById('vendorId').value = '';
  document.getElementById('vendorModalTitle').textContent = 'Add Vendor';
}
function editVendor(id, name, contact, email, phone, type, address, notes) {
  document.getElementById('vendorId').value   = id;
  document.getElementById('vCompany').value   = name;
  document.getElementById('vContact').value   = contact;
  document.getElementById('vEmail').value     = email;
  document.getElementById('vPhone').value     = phone;
  document.getElementById('vType').value      = type;
  document.getElementById('vAddress').value   = address;
  document.getElementById('vNotes').value     = notes;
  document.getElementById('vendorModalTitle').textContent = 'Edit Vendor';
  openVendorModal();
}
document.getElementById('vendorModal').addEventListener('click', function(e) {
  if (e.target === this) closeVendorModal();
});

/* ── Form submit ───────────────────────────────────────── */
document.getElementById('vendorForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const id  = document.getElementById('vendorId').value;
  const url = id ? 'admin/vendors/update' : 'admin/vendors/store';
  const btn = document.getElementById('saveVendorBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving…';
  const fd = new FormData(this);
  fd.append('_csrf', CSRF_TOKEN);
  $.ajax({
    url: SITE_URL + '/' + url, type: 'POST',
    data: fd, processData: false, contentType: false, dataType: 'json',
    success: res => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-1"></i>Save Vendor';
      if (res.success) {
        Swal.fire({ icon: 'success', title: res.message, timer: 1200, showConfirmButton: false })
          .then(() => location.reload());
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    },
    error: () => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-1"></i>Save Vendor';
      Swal.fire('Error', 'Request failed.', 'error');
    }
  });
});

/* ── Delete / deactivate ───────────────────────────────── */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-del-vendor');
  if (!btn) return;
  const id = btn.dataset.id;
  Swal.fire({
    title: 'Deactivate vendor?',
    text: "This won't delete their products or orders.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    confirmButtonText: 'Deactivate'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/vendors/delete', { id }, res => {
      if (res.success) {
        document.getElementById('vendor-row-' + id)?.remove();
        Swal.fire({ icon: 'success', title: 'Vendor deactivated.', timer: 1200, showConfirmButton: false });
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    });
  });
});
</script>
JS;
?>
