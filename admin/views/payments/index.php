<?php
$pageTitle = 'Payment Methods';
$noDelete  = ['cod','koko','payzy','mintpay'];
$pmIcons   = [
    'cod'        => ['fa-truck',              '#10b981', '#052e16'],
    'bank'       => ['fa-building-columns',   '#3b82f6', '#1e3a5f'],
    'card'       => ['fa-credit-card',        '#6366f1', '#1e1b4b'],
    'visa'       => ['fa-cc-visa',            '#1a56db', '#1e3a5f'],
    'mastercard' => ['fa-cc-mastercard',      '#ef4444', '#3b0a0a'],
    'payhere'    => ['fa-shield-halved',      '#f59e0b', '#3d2503'],
    'payzy'      => ['fa-bolt',               '#8b5cf6', '#2e1065'],
    'koko'       => ['fa-layer-group',        '#f59e0b', '#3d2503'],
    'mintpay'    => ['fa-calendar-days',      '#06b6d4', '#0a3040'],
];
?>
<style>
/* ── Page layout ── */
.pm-page-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
@media(max-width:600px){ .pm-page-stats{ grid-template-columns:repeat(2,1fr); } }

.pm-stat { display:flex; flex-direction:column; gap:4px; padding:16px 18px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); }
.pm-stat-num { font-size:26px; font-weight:800; line-height:1; }
.pm-stat-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-muted); }

/* ── Method rows ── */
.pm-list { display:flex; flex-direction:column; gap:.6rem; }
.pm-row { display:flex; align-items:center; gap:14px; padding:14px 16px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); transition:var(--transition); }
.pm-row:hover { border-color:var(--border2); box-shadow:0 2px 12px rgba(0,0,0,.12); }
.pm-row.pm-inactive { opacity:.55; }

.pm-icon-box { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.pm-meta { flex:1; min-width:0; }
.pm-name { font-size:14px; font-weight:700; color:var(--text); line-height:1.2; }
.pm-desc { font-size:11px; color:var(--text-muted); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pm-code-badge { display:inline-block; font-family:monospace; font-size:10px; font-weight:700; text-transform:uppercase; background:var(--surface2); color:var(--text-muted); padding:1px 7px; border-radius:4px; margin-left:6px; vertical-align:middle; }

.pm-sort { font-size:11px; color:var(--text-muted); background:var(--surface2); border-radius:6px; padding:3px 9px; white-space:nowrap; flex-shrink:0; }

/* Toggle switch */
.pm-sw { position:relative; width:40px; height:22px; flex-shrink:0; }
.pm-sw input { opacity:0; width:0; height:0; }
.pm-sw-track { position:absolute; inset:0; background:var(--surface2); border:1px solid var(--border2); border-radius:11px; cursor:pointer; transition:var(--transition); }
.pm-sw-track::before { content:''; position:absolute; width:16px; height:16px; left:2px; top:2px; background:var(--text-muted); border-radius:50%; transition:var(--transition); }
.pm-sw input:checked + .pm-sw-track { background:var(--green); border-color:var(--green); }
.pm-sw input:checked + .pm-sw-track::before { transform:translateX(18px); background:#fff; }

.pm-actions-wrap { display:flex; align-items:center; gap:8px; flex-shrink:0; }
.pm-btn { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; transition:var(--transition); }
.pm-btn-edit { background:var(--cyan-dim); color:var(--cyan); }
.pm-btn-edit:hover { background:var(--cyan); color:#000; }
.pm-btn-del  { background:var(--red-dim); color:var(--red); }
.pm-btn-del:hover  { background:var(--red); color:#fff; }

/* ── Section header ── */
.pm-section-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:.75rem; }
.pm-section-title { font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--text-muted); }

/* ── Empty state ── */
.pm-empty { text-align:center; padding:3rem 1rem; color:var(--text-muted); }
.pm-empty i { font-size:2.5rem; margin-bottom:1rem; display:block; opacity:.4; }

/* ── Shortcut links ── */
.pm-shortcut { display:flex; align-items:center; gap:8px; padding:11px 14px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); text-decoration:none; color:var(--text); font-size:13px; font-weight:600; transition:var(--transition); }
.pm-shortcut:hover { border-color:var(--cyan); color:var(--cyan); }
.pm-shortcut i { color:var(--cyan); font-size:15px; }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <div>
    <h4 style="margin:0;font-size:1.15rem;font-weight:700;">Payment Methods</h4>
    <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">Manage checkout payment options, order and gateway settings</div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= url('admin/payments/transactions') ?>" class="pm-shortcut">
      <i class="fa fa-receipt"></i> Transactions
    </a>
    <a href="<?= url('admin/payment-gateway') ?>" class="pm-shortcut">
      <i class="fa fa-key"></i> Gateway Settings
    </a>
    <button class="btn btn-primary btn-sm" onclick="openAddModal()">
      <i class="fa fa-plus me-1"></i> Add Method
    </button>
  </div>
</div>

<!-- Transaction stats -->
<?php if (!empty($txnStats)): ?>
<div class="pm-page-stats">
  <div class="pm-stat">
    <span class="pm-stat-num" style="color:var(--text);"><?= number_format((int)($txnStats['total'] ?? 0)) ?></span>
    <span class="pm-stat-lbl">Total Transactions</span>
  </div>
  <div class="pm-stat">
    <span class="pm-stat-num" style="color:var(--amber);"><?= number_format((int)($txnStats['pending'] ?? 0)) ?></span>
    <span class="pm-stat-lbl">Pending</span>
  </div>
  <div class="pm-stat">
    <span class="pm-stat-num" style="color:var(--green);"><?= number_format((int)($txnStats['success'] ?? 0)) ?></span>
    <span class="pm-stat-lbl">Successful</span>
  </div>
  <div class="pm-stat">
    <span class="pm-stat-num" style="color:var(--red);"><?= number_format((int)($txnStats['failed'] ?? 0)) ?></span>
    <span class="pm-stat-lbl">Failed</span>
  </div>
</div>
<?php endif; ?>

<!-- Methods list -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="card-title"><i class="fa fa-credit-card me-2" style="color:var(--cyan);"></i>Configured Methods</span>
    <span style="font-size:12px;color:var(--text-muted);"><?= count($methods) ?> method<?= count($methods) !== 1 ? 's' : '' ?></span>
  </div>
  <div class="card-body">
    <?php if (empty($methods)): ?>
    <div class="pm-empty">
      <i class="fa fa-credit-card"></i>
      <p>No payment methods yet.</p>
      <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fa fa-plus me-1"></i>Add Method</button>
    </div>
    <?php else: ?>
    <div class="pm-list">
      <?php foreach ($methods as $m):
        $code  = strtolower($m['code']);
        [$ico, $icoBg, $icoDark] = $pmIcons[$code] ?? ['fa-credit-card','#6366f1','#1e1b4b'];
      ?>
      <div class="pm-row <?= $m['is_active'] ? '' : 'pm-inactive' ?>">

        <!-- Icon -->
        <div class="pm-icon-box" style="background:<?= $icoDark ?>;color:<?= $icoBg ?>;">
          <i class="fa <?= $ico ?>"></i>
        </div>

        <!-- Info -->
        <div class="pm-meta">
          <div class="pm-name">
            <?= e($m['display_name']) ?>
            <span class="pm-code-badge"><?= e($m['code']) ?></span>
          </div>
          <?php if ($m['description']): ?>
          <div class="pm-desc"><?= e($m['description']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Sort order -->
        <div class="pm-sort">#<?= (int)$m['sort_order'] ?></div>

        <!-- Active toggle -->
        <label class="pm-sw" title="<?= $m['is_active'] ? 'Enabled — click to disable' : 'Disabled — click to enable' ?>">
          <input type="checkbox" class="pm-toggle" data-id="<?= $m['id'] ?>" <?= $m['is_active'] ? 'checked' : '' ?>>
          <span class="pm-sw-track"></span>
        </label>

        <!-- Actions -->
        <div class="pm-actions-wrap">
          <button class="pm-btn pm-btn-edit pm-edit"
                  data-row='<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>'>
            <i class="fa fa-pencil"></i><span class="d-none d-sm-inline">Edit</span>
          </button>
          <?php if (!in_array($code, $noDelete)): ?>
          <button class="pm-btn pm-btn-del pm-delete"
                  data-id="<?= $m['id'] ?>" data-name="<?= e($m['display_name']) ?>">
            <i class="fa fa-trash"></i>
          </button>
          <?php endif; ?>
        </div>

      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- ═══ ADD MODAL ═══ -->
<div class="modal fade" id="addMethodModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus me-2" style="color:var(--cyan);"></i>Add Payment Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addMethodForm">
        <?= CSRF::field() ?>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12 col-sm-6">
              <label class="form-label fw-semibold">Code <span style="color:var(--red);">*</span></label>
              <input type="text" name="code" class="form-control" required placeholder="e.g. bank_transfer"
                     pattern="[a-z0-9_]+" title="Lowercase letters, numbers, underscores only" style="font-family:monospace;">
              <div class="form-text">Lowercase, no spaces, e.g. <code>bank_transfer</code></div>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label fw-semibold">Display Name <span style="color:var(--red);">*</span></label>
              <input type="text" name="display_name" class="form-control" required placeholder="e.g. Bank Transfer">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <input type="text" name="description" class="form-control" placeholder="Short description shown at checkout">
            </div>
            <div class="col-5">
              <label class="form-label fw-semibold">Icon (emoji)</label>
              <input type="text" name="icon" class="form-control" value="💳" maxlength="5"
                     style="font-size:20px;text-align:center;">
            </div>
            <div class="col-4">
              <label class="form-label fw-semibold">Sort Order</label>
              <input type="number" name="sort_order" class="form-control" value="99" min="0">
            </div>
            <div class="col-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="addSubmitBtn" class="btn btn-primary"><i class="fa fa-plus me-1"></i>Add Method</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══ EDIT MODAL ═══ -->
<div class="modal fade" id="editMethodModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-pencil me-2" style="color:var(--cyan);"></i>Edit Payment Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editMethodForm">
        <?= CSRF::field() ?>
        <input type="hidden" name="id" id="eId">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Code</label>
              <input type="text" id="eCode" class="form-control" disabled
                     style="background:var(--surface2);color:var(--text-muted);font-family:monospace;">
              <div class="form-text">Code cannot be changed after creation.</div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Display Name <span style="color:var(--red);">*</span></label>
              <input type="text" name="display_name" id="eName" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <input type="text" name="description" id="eDesc" class="form-control">
            </div>
            <div class="col-5">
              <label class="form-label fw-semibold">Icon (emoji)</label>
              <input type="text" name="icon" id="eIcon" class="form-control" maxlength="5"
                     style="font-size:20px;text-align:center;">
            </div>
            <div class="col-4">
              <label class="form-label fw-semibold">Sort Order</label>
              <input type="number" name="sort_order" id="eSort" class="form-control" min="0">
            </div>
            <div class="col-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" id="eActive" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="editSubmitBtn" class="btn btn-primary"><i class="fa fa-floppy-disk me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
function openAddModal() {
  document.getElementById('addMethodForm').reset();
  new bootstrap.Modal(document.getElementById('addMethodModal')).show();
}

// Add
$('#addMethodForm').on('submit', function(e) {
  e.preventDefault();
  var btn = $('#addSubmitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Adding…');
  $.post(SITE_URL + '/admin/payments/store', $(this).serialize(), function(res) {
    btn.prop('disabled', false).html('<i class="fa fa-plus me-1"></i>Add Method');
    if (res.success) {
      SwalDark.fire({ icon:'success', title:res.message, timer:1400, showConfirmButton:false }).then(() => location.reload());
    } else {
      SwalDark.fire('Error', res.message, 'error');
    }
  }, 'json').fail(() => { btn.prop('disabled', false); SwalDark.fire('Error', 'Request failed.', 'error'); });
});

// Edit
$(document).on('click', '.pm-edit', function() {
  var m = $(this).data('row');
  if (typeof m === 'string') m = JSON.parse(m);
  $('#eId').val(m.id); $('#eCode').val(m.code); $('#eName').val(m.display_name);
  $('#eDesc').val(m.description || ''); $('#eIcon').val(m.icon || '💳');
  $('#eSort').val(m.sort_order || 0); $('#eActive').val(m.is_active != null ? m.is_active : 1);
  new bootstrap.Modal(document.getElementById('editMethodModal')).show();
});

$('#editMethodForm').on('submit', function(e) {
  e.preventDefault();
  var btn = $('#editSubmitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Saving…');
  $.post(SITE_URL + '/admin/payments/update', $(this).serialize(), function(res) {
    btn.prop('disabled', false).html('<i class="fa fa-floppy-disk me-1"></i>Save Changes');
    if (res.success) {
      SwalDark.fire({ icon:'success', title:res.message, timer:1400, showConfirmButton:false }).then(() => location.reload());
    } else {
      SwalDark.fire('Error', res.message, 'error');
    }
  }, 'json').fail(() => { btn.prop('disabled', false); SwalDark.fire('Error', 'Request failed.', 'error'); });
});

// Toggle via switch
$(document).on('change', '.pm-toggle', function() {
  var cb  = $(this);
  var id  = cb.data('id');
  var row = cb.closest('.pm-row');
  ajaxPost('admin/payments/toggle', { id: id }, function(res) {
    if (res.success) {
      row.toggleClass('pm-inactive', !res.active);
    } else {
      cb.prop('checked', !cb.prop('checked')); // revert
      SwalDark.fire('Error', res.message, 'error');
    }
  });
});

// Delete
$(document).on('click', '.pm-delete', function() {
  var id = $(this).data('id'), name = $(this).data('name');
  SwalDark.fire({
    title: 'Delete "' + name + '"?',
    text: 'Methods with existing orders cannot be deleted.',
    icon: 'warning', showCancelButton: true,
    confirmButtonText: 'Delete', confirmButtonColor: '#ef4444'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/payments/delete', { id: id }, function(res) {
      if (res.success) location.reload();
      else SwalDark.fire('Error', res.message, 'error');
    });
  });
});
</script>
JS;
?>
