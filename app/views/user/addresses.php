<?php
$pageTitle = 'My Addresses';
$user = Auth::user();
$provinces = ['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'];
?>

<style>
.acct-card{background:var(--bg-card,#fff);border-radius:14px;box-shadow:0 1px 6px rgba(0,0,0,.07);border:1px solid var(--border,#f0f0f0);}
.addr-card{background:var(--bg-card,#fff);border:2px solid var(--border,#eee);border-radius:12px;padding:1.1rem;height:100%;position:relative;transition:.15s;}
.addr-card.is-default{border-color:var(--primary,#E63946);background:rgba(230,57,70,.04);}
.addr-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:2px 8px;border-radius:99px;display:inline-block;margin-bottom:.5rem;}
address{color:var(--text,#333);}
</style>

<div class="container py-4 py-lg-5">
  <div class="row g-4">

    <div class="col-lg-3">
      <?php include __DIR__ . '/_sidebar.php'; ?>
    </div>

    <div class="col-lg-9">
      <div class="acct-card overflow-hidden">
        <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">My Addresses</h5>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="fa fa-plus me-1"></i>Add New
          </button>
        </div>

        <div class="p-4">
          <?php if (empty($addresses)): ?>
          <div class="text-center text-muted py-5">
            <i class="fa fa-location-dot fa-3x mb-3 d-block" style="color:var(--border,#ddd);"></i>
            <h6>No saved addresses</h6>
            <p class="small">Add an address to speed up checkout.</p>
            <button class="btn btn-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#addAddressModal">
              <i class="fa fa-plus me-1"></i>Add Address
            </button>
          </div>
          <?php else: ?>
          <div class="row g-3">
            <?php foreach ($addresses as $addr): ?>
            <div class="col-md-6" id="addrBlock-<?= $addr['id'] ?>">
              <div class="addr-card <?= $addr['is_default'] ? 'is-default' : '' ?>">
                <!-- Label + default badge -->
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="addr-label" style="background:<?= $addr['is_default']?'var(--primary,#E63946)':'var(--bg-soft,#e5e7eb)' ?>;color:<?= $addr['is_default']?'#fff':'var(--text-muted,#555)' ?>;">
                    <?= e($addr['label'] ?? 'Address') ?>
                  </span>
                  <?php if ($addr['is_default']): ?>
                  <span class="badge bg-success" style="font-size:10px;">Default</span>
                  <?php endif; ?>
                </div>

                <address class="mb-3 small lh-lg" style="font-style:normal;">
                  <strong><?= e($addr['full_name']) ?></strong><br>
                  <?php if (!empty($addr['phone'])): ?><?= e($addr['phone']) ?><br><?php endif; ?>
                  <?= e($addr['address_line1']) ?>
                  <?php if (!empty($addr['address_line2'])): ?>, <?= e($addr['address_line2']) ?><?php endif; ?><br>
                  <?= e($addr['city']) ?>
                  <?php if (!empty($addr['state'])): ?>, <?= e($addr['state']) ?><?php endif; ?><br>
                  <?= e($addr['country'] ?? 'Sri Lanka') ?>
                  <?php if (!empty($addr['postal_code'])): ?> &mdash; <?= e($addr['postal_code']) ?><?php endif; ?>
                </address>

                <div class="d-flex gap-2 flex-wrap">
                  <?php if (!$addr['is_default']): ?>
                  <button class="btn btn-sm btn-outline-primary btn-set-default"
                          data-id="<?= $addr['id'] ?>">
                    <i class="fa fa-star me-1"></i>Set Default
                  </button>
                  <?php endif; ?>
                  <button class="btn btn-sm btn-outline-danger btn-delete-addr"
                          data-id="<?= $addr['id'] ?>">
                    <i class="fa fa-trash me-1"></i>Remove
                  </button>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Add New Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addAddressForm" novalidate>
        <?= CSRF::field() ?>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Label</label>
              <select name="label" class="form-select">
                <option value="Home">Home</option>
                <option value="Work">Work</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" class="form-control" required
                     value="<?= e($user['name'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Phone</label>
              <input type="text" name="phone" class="form-control" placeholder="+94 77 000 0000">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Address Line 1 <span class="text-danger">*</span></label>
              <input type="text" name="address_line1" class="form-control" required placeholder="House no., street name">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Address Line 2</label>
              <input type="text" name="address_line2" class="form-control" placeholder="Apartment, floor (optional)">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">City <span class="text-danger">*</span></label>
              <input type="text" name="city" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Province</label>
              <select name="state" class="form-select">
                <option value="">Select…</option>
                <?php foreach ($provinces as $pv): ?>
                <option value="<?= $pv ?>"><?= $pv ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Postal Code</label>
              <input type="text" name="postal_code" class="form-control" placeholder="00000">
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="chkDefault">
                <label class="form-check-label small" for="chkDefault">Set as default address</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="saveAddrBtn">
            <i class="fa fa-floppy-disk me-1"></i>Save Address
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
(function () {

  // Add address — use FormData directly (preserves checkbox state)
  document.getElementById('addAddressForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) { this.classList.add('was-validated'); return; }
    var btn = document.getElementById('saveAddrBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';
    var fd = new FormData(this);
    fd.set('_csrf', CSRF_TOKEN);
    $.ajax({
      url: SITE_URL + '/account/add-address',
      type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
      success: function (res) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-floppy-disk me-1"></i>Save Address';
        if (res.success) {
          Swal.fire({ icon: 'success', title: 'Address saved!', timer: 1400, showConfirmButton: false })
            .then(function () { location.reload(); });
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-floppy-disk me-1"></i>Save Address';
        Swal.fire('Error', 'Request failed.', 'error');
      }
    });
  });

  // Delete address
  $(document).on('click', '.btn-delete-addr', function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Remove address?', icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#e74c3c',
      confirmButtonText: 'Remove',
      cancelButtonText: 'Keep'
    }).then(function (r) {
      if (!r.isConfirmed) return;
      apPost('account/delete-address', { address_id: id }, function (res) {
        if (res.success) {
          $('#addrBlock-' + id).fadeOut(250, function () {
            $(this).remove();
            if ($('[id^="addrBlock-"]').length === 0) location.reload();
          });
        }
      });
    });
  });

  // Set default address
  $(document).on('click', '.btn-set-default', function () {
    var id = $(this).data('id');
    apPost('account/set-default-address', { address_id: id }, function (res) {
      if (res.success) location.reload();
    });
  });

})();
</script>
JS;
?>
