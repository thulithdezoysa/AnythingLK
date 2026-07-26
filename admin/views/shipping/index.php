<?php
$pageTitle = 'Shipping Methods';
$zones = [
    'all'        => ['label'=>'All Island',       'icon'=>'fa-earth-asia',    'bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
    'colombo'    => ['label'=>'Colombo',           'icon'=>'fa-city',          'bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
    'western'    => ['label'=>'Western Province',  'icon'=>'fa-map-location',  'bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
    'outstation' => ['label'=>'Outstation',        'icon'=>'fa-road',          'bg'=>'var(--surface2)',  'color'=>'var(--text-dim)'],
    'express'    => ['label'=>'Express / Same Day','icon'=>'fa-bolt',          'bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
];
?>
<style>
/* ── Cards grid ── */
.ship-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1rem; }

.ship-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; transition:var(--transition); display:flex; flex-direction:column; }
.ship-card:hover { border-color:var(--border2); box-shadow:0 4px 18px rgba(0,0,0,.15); }
.ship-card.ship-inactive { opacity:.5; }

.ship-card-head { display:flex; justify-content:space-between; align-items:flex-start; padding:16px 16px 12px; gap:10px; }
.ship-card-name { font-size:14px; font-weight:700; color:var(--text); line-height:1.3; }
.ship-card-desc { font-size:11px; color:var(--text-muted); margin-top:3px; }

.ship-zone-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; white-space:nowrap; flex-shrink:0; }

.ship-card-body { padding:0 16px 14px; flex:1; display:flex; flex-direction:column; gap:10px; }

/* Price block */
.ship-price-row { display:flex; align-items:baseline; gap:8px; }
.ship-price-main { font-size:24px; font-weight:800; color:var(--text); line-height:1; }
.ship-price-curr { font-size:12px; font-weight:600; color:var(--text-muted); }
.ship-free-tag  { display:inline-flex; align-items:center; gap:5px; background:var(--green-dim); color:var(--green); font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
.ship-free-above { font-size:11px; color:var(--green); margin-top:2px; }

/* Meta row */
.ship-meta-row { display:flex; align-items:center; gap:14px; font-size:12px; color:var(--text-muted); }
.ship-meta-item { display:flex; align-items:center; gap:5px; }
.ship-meta-item i { font-size:11px; color:var(--cyan); }

/* Footer */
.ship-card-foot { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-top:1px solid var(--border); background:var(--surface2); gap:8px; }
.ship-status { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; }
.ship-status-dot { width:7px; height:7px; border-radius:50%; }

.ship-btn { display:inline-flex; align-items:center; gap:5px; padding:5px 11px; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; transition:var(--transition); }
.ship-btn-edit { background:var(--cyan-dim); color:var(--cyan); }
.ship-btn-edit:hover { background:var(--cyan); color:#000; }
.ship-btn-del  { background:var(--red-dim); color:var(--red); }
.ship-btn-del:hover  { background:var(--red); color:#fff; }

/* Empty state */
.ship-empty { text-align:center; padding:4rem 1rem; color:var(--text-muted); }
.ship-empty i { font-size:3rem; display:block; margin-bottom:1rem; opacity:.3; }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <div>
    <h4 style="margin:0;font-size:1.15rem;font-weight:700;">Shipping Methods</h4>
    <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">Configure delivery zones, rates and free-shipping thresholds</div>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openAddModal()">
    <i class="fa fa-plus me-1"></i>Add Shipping Method
  </button>
</div>

<!-- Zone legend -->
<div class="d-flex flex-wrap gap-2 mb-4">
  <?php foreach ($zones as $zk => $zv): ?>
  <span style="background:<?= $zv['bg'] ?>;color:<?= $zv['color'] ?>;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;">
    <i class="fa <?= $zv['icon'] ?>"></i><?= $zv['label'] ?>
  </span>
  <?php endforeach; ?>
</div>

<!-- Methods grid -->
<?php if (empty($methods)): ?>
<div class="card">
  <div class="card-body ship-empty">
    <i class="fa fa-truck"></i>
    <p class="mb-3">No shipping methods configured yet.</p>
    <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fa fa-plus me-1"></i>Add Method</button>
  </div>
</div>
<?php else: ?>
<div class="ship-grid">
  <?php foreach ($methods as $m):
    $zkey = $m['zone'] ?? 'all';
    $z    = $zones[$zkey] ?? $zones['all'];
    $isFree = !empty($m['is_free']);
    $hasFreeAbove = !$isFree && (float)($m['free_above'] ?? 0) > 0;
  ?>
  <div class="ship-card <?= $m['status'] ? '' : 'ship-inactive' ?>">

    <div class="ship-card-head">
      <div>
        <div class="ship-card-name"><?= e($m['name']) ?></div>
        <?php if ($m['description']): ?>
        <div class="ship-card-desc"><?= e($m['description']) ?></div>
        <?php endif; ?>
      </div>
      <span class="ship-zone-badge" style="background:<?= $z['bg'] ?>;color:<?= $z['color'] ?>;">
        <i class="fa <?= $z['icon'] ?>"></i><?= $z['label'] ?>
      </span>
    </div>

    <div class="ship-card-body">
      <!-- Price -->
      <div>
        <?php if ($isFree): ?>
        <span class="ship-free-tag"><i class="fa fa-check"></i>Always Free</span>
        <?php else: ?>
        <div class="ship-price-row">
          <span class="ship-price-curr">LKR</span>
          <span class="ship-price-main"><?= number_format((float)$m['price'], 0) ?></span>
        </div>
        <?php if ($hasFreeAbove): ?>
        <div class="ship-free-above"><i class="fa fa-tag me-1"></i>Free above LKR <?= number_format((float)$m['free_above'], 0) ?></div>
        <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Meta -->
      <div class="ship-meta-row">
        <div class="ship-meta-item">
          <i class="fa fa-clock"></i>
          <span><?= (int)$m['min_days'] ?>–<?= (int)$m['max_days'] ?> business days</span>
        </div>
        <div class="ship-meta-item">
          <i class="fa fa-hashtag"></i>
          <span>Sort #<?= (int)($m['sort_order'] ?? 0) ?></span>
        </div>
      </div>
    </div>

    <div class="ship-card-foot">
      <div class="ship-status">
        <span class="ship-status-dot" style="background:<?= $m['status'] ? 'var(--green)' : 'var(--text-muted)' ?>;"></span>
        <span style="color:<?= $m['status'] ? 'var(--green)' : 'var(--text-muted)' ?>;"><?= $m['status'] ? 'Active' : 'Inactive' ?></span>
      </div>
      <div class="d-flex gap-2">
        <button class="ship-btn ship-btn-edit"
                data-row='<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>'>
          <i class="fa fa-pencil"></i>Edit
        </button>
        <button class="ship-btn ship-btn-del"
                data-id="<?= $m['id'] ?>" data-name="<?= e($m['name']) ?>">
          <i class="fa fa-trash"></i>
        </button>
      </div>
    </div>

  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ═══ ADD / EDIT MODAL ═══ -->
<div class="modal fade" id="shipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shipModalTitle"><i class="fa fa-truck me-2" style="color:var(--cyan);"></i>Add Shipping Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="shipForm">
        <?= CSRF::field() ?>
        <input type="hidden" name="id" id="fId">
        <div class="modal-body">
          <div class="row g-3">

            <div class="col-12">
              <label class="form-label fw-semibold">Name <span style="color:var(--red);">*</span></label>
              <input type="text" name="name" id="fName" class="form-control" required placeholder="e.g. Standard Delivery">
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <input type="text" name="description" id="fDesc" class="form-control" placeholder="Shown at checkout">
            </div>

            <!-- Free toggle -->
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3"
                   style="background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border);">
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="is_free" id="fIsFree" value="1">
                </div>
                <div>
                  <div class="fw-semibold" style="font-size:13px;">Always Free</div>
                  <div style="font-size:11px;color:var(--text-muted);">Ignore price — always charge LKR 0</div>
                </div>
              </div>
            </div>

            <!-- Price fields -->
            <div id="priceGroup" class="col-12">
              <div class="row g-3">
                <div class="col-6">
                  <label class="form-label fw-semibold">Price (LKR)</label>
                  <input type="number" name="price" id="fPrice" class="form-control" min="0" step="1" value="0">
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold">Free Above (LKR)</label>
                  <input type="number" name="free_above" id="fFreeAbove" class="form-control" min="0" step="1" value="0" placeholder="0 = disabled">
                  <div class="form-text">Order total above this → free</div>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label fw-semibold">Zone</label>
              <select name="zone" id="fZone" class="form-control">
                <option value="all">All Island</option>
                <option value="colombo">Colombo</option>
                <option value="western">Western Province</option>
                <option value="outstation">Outstation</option>
                <option value="express">Express / Same Day</option>
              </select>
            </div>

            <div class="col-6 col-sm-3">
              <label class="form-label fw-semibold">Min Days</label>
              <input type="number" name="min_days" id="fMinDays" class="form-control" min="0" value="1">
            </div>
            <div class="col-6 col-sm-3">
              <label class="form-label fw-semibold">Max Days</label>
              <input type="number" name="max_days" id="fMaxDays" class="form-control" min="1" value="5">
            </div>

            <div class="col-6 col-sm-4">
              <label class="form-label fw-semibold">Sort Order</label>
              <input type="number" name="sort_order" id="fSort" class="form-control" min="0" value="0">
            </div>

            <div class="col-6 col-sm-4" id="fStatusGroup" style="display:none;">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" id="fStatus" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="fSubmitBtn" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i>Add Method
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
$('#fIsFree').on('change', function() { $('#priceGroup').toggle(!this.checked); });

function openAddModal() {
  $('#shipModalTitle').html('<i class="fa fa-truck me-2" style="color:var(--cyan);"></i>Add Shipping Method');
  $('#fSubmitBtn').html('<i class="fa fa-plus me-1"></i>Add Method');
  $('#shipForm')[0].reset();
  $('#fId').val(''); $('#fStatusGroup').hide(); $('#priceGroup').show();
  new bootstrap.Modal(document.getElementById('shipModal')).show();
}

$(document).on('click', '.ship-btn-edit', function() {
  var m = $(this).data('row');
  if (typeof m === 'string') m = JSON.parse(m);
  $('#shipModalTitle').html('<i class="fa fa-pencil me-2" style="color:var(--cyan);"></i>Edit Shipping Method');
  $('#fSubmitBtn').html('<i class="fa fa-floppy-disk me-1"></i>Save Changes');
  $('#fId').val(m.id); $('#fName').val(m.name || ''); $('#fDesc').val(m.description || '');
  $('#fPrice').val(m.price || 0); $('#fFreeAbove').val(m.free_above || 0);
  $('#fZone').val(m.zone || 'all'); $('#fMinDays').val(m.min_days || 1); $('#fMaxDays').val(m.max_days || 5);
  $('#fSort').val(m.sort_order || 0); $('#fStatus').val(m.status != null ? m.status : 1);
  var isFree = parseInt(m.is_free) === 1;
  $('#fIsFree').prop('checked', isFree); $('#priceGroup').toggle(!isFree);
  $('#fStatusGroup').show();
  new bootstrap.Modal(document.getElementById('shipModal')).show();
});

$('#shipForm').on('submit', function(e) {
  e.preventDefault();
  var isEdit = !!$('#fId').val();
  var btn = $('#fSubmitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Saving…');
  var url = SITE_URL + '/admin/shipping/' + (isEdit ? 'update' : 'store');
  $.post(url, $(this).serialize(), function(res) {
    btn.prop('disabled', false).html(isEdit ? '<i class="fa fa-floppy-disk me-1"></i>Save Changes' : '<i class="fa fa-plus me-1"></i>Add Method');
    if (res.success) {
      SwalDark.fire({ icon:'success', title:res.message, timer:1400, showConfirmButton:false }).then(() => location.reload());
    } else {
      SwalDark.fire('Error', res.message, 'error');
    }
  }, 'json').fail(() => { btn.prop('disabled', false); SwalDark.fire('Error', 'Request failed.', 'error'); });
});

$(document).on('click', '.ship-btn-del', function() {
  var id = $(this).data('id'), name = $(this).data('name');
  SwalDark.fire({
    title: 'Delete "' + name + '"?',
    text: 'Active orders using this method will retain their shipping label.',
    icon: 'warning', showCancelButton: true,
    confirmButtonText: 'Delete', confirmButtonColor: '#ef4444'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/shipping/delete', { id: id }, function(res) {
      if (res.success) {
        SwalDark.fire({ icon: res.disabled ? 'warning' : 'success', title: res.message, timer:1500, showConfirmButton:false }).then(() => location.reload());
      } else {
        SwalDark.fire('Error', res.message, 'error');
      }
    });
  });
});
</script>
JS;
?>
