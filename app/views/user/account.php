<?php
$pageTitle = 'My Account';

// $user is the full DB row (fixed in Controller::view — no longer overwritten by Auth::user())
$fullName   = $user['full_name']  ?? '';
$email      = $user['email']      ?? '';
$phone      = $user['phone']      ?? '';
$avatarFile = $user['avatar']     ?? null;
$createdAt  = $user['created_at'] ?? null;

$nameParts  = explode(' ', trim($fullName), 2);
$firstName  = $nameParts[0] ?? '';
$lastName   = $nameParts[1] ?? '';

$avatarUrl  = $avatarFile
    ? url('uploads/avatars/' . e($avatarFile))
    : null;

$memberSince = $createdAt ? date('F j, Y', strtotime($createdAt)) : '—';
?>

<style>
.acct-card{background:var(--bg-card,#fff);border-radius:14px;box-shadow:0 1px 6px rgba(0,0,0,.07);border:1px solid var(--border,#f0f0f0);}
.stat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.avatar-wrap{width:80px;height:80px;border-radius:50%;overflow:hidden;background:var(--primary,#E63946);
             display:flex;align-items:center;justify-content:center;flex-shrink:0;border:3px solid var(--bg,#fff);
             box-shadow:0 2px 8px rgba(0,0,0,.15);}
.avatar-wrap img{width:100%;height:100%;object-fit:cover;}
.avatar-initial{color:#fff;font-size:28px;font-weight:700;line-height:1;}
.profile-field-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted,#9ca3af);margin-bottom:2px;}
.profile-field-val{font-weight:600;font-size:.95rem;color:var(--text,#111);}
#avatarPreview{width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--border,#eee);cursor:pointer;}
/* Override Bootstrap's per-cell bg/color vars so dark mode works correctly */
.acct-table {
  --bs-table-bg:           var(--bg-card, #fff);
  --bs-table-color:        var(--text, #111);
  --bs-table-border-color: var(--border, #e9ecef);
}
.acct-table thead th {
  background: var(--bg-soft, #f8f9fc) !important;
  color: var(--text-muted, #6c757d) !important;
  border-bottom: 2px solid var(--border, #e9ecef) !important;
  font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.acct-table td, .acct-table th { border-color: var(--border, #e9ecef) !important; }
/* Target <td> directly — Bootstrap's --bs-table-bg is applied per cell, not per row */
.acct-table tbody tr:hover > td,
.acct-table tbody tr:hover > th {
  background: var(--bg-soft, #f8f9fc) !important;
  color: var(--text, #111);
}
.acct-card .text-muted{color:var(--text-muted) !important;}
.acct-card .fw-semibold,.acct-card .fw-bold{color:var(--text);}
</style>

<div class="container py-4 py-lg-5">
  <div class="row g-4">

    <div class="col-lg-3">
      <?php include __DIR__ . '/_sidebar.php'; ?>
    </div>

    <div class="col-lg-9">

      <!-- Stats -->
      <div class="row g-3 mb-4">
        <?php
        $stats = [
          ['val'=>$totalOrders,    'label'=>'Total Orders', 'color'=>'#6366f1','bg'=>'rgba(99,102,241,.1)', 'icon'=>'fa-bag-shopping'],
          ['val'=>$pendingCount,   'label'=>'Pending',      'color'=>'#f59e0b','bg'=>'rgba(245,158,11,.1)', 'icon'=>'fa-clock'],
          ['val'=>$deliveredCount, 'label'=>'Delivered',    'color'=>'#10b981','bg'=>'rgba(16,185,129,.1)', 'icon'=>'fa-circle-check'],
          ['val'=>$wishlistCount,  'label'=>'Wishlist',     'color'=>'#ef4444','bg'=>'rgba(239,68,68,.1)',  'icon'=>'fa-heart'],
        ];
        foreach ($stats as $s): ?>
        <div class="col-6 col-sm-3">
          <div class="acct-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;">
              <i class="fa <?= $s['icon'] ?>"></i>
            </div>
            <div>
              <div class="fw-bold fs-5 lh-1 mb-1"><?= (int)$s['val'] ?></div>
              <div class="text-muted" style="font-size:12px;"><?= $s['label'] ?></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Profile card -->
      <div class="acct-card p-4 mb-4" id="profileCard">
        <div class="d-flex justify-content-between align-items-start mb-4">
          <h6 class="fw-bold mb-0">Profile Information</h6>
          <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fa fa-pen me-1"></i>Edit Profile
          </button>
        </div>

        <div class="d-flex align-items-start gap-4 flex-wrap">
          <!-- Avatar -->
          <div class="avatar-wrap" id="profileAvatarWrap">
            <?php if ($avatarUrl): ?>
            <img src="<?= $avatarUrl ?>" id="profileAvatarImg" alt="Avatar">
            <?php else: ?>
            <span class="avatar-initial" id="profileAvatarInitial">
              <?= strtoupper(substr($firstName ?: 'U', 0, 1)) ?>
            </span>
            <?php endif; ?>
          </div>

          <!-- Fields -->
          <div class="row g-3 flex-grow-1">
            <div class="col-sm-6">
              <div class="profile-field-label">First Name</div>
              <div class="profile-field-val" id="dispFirstName"><?= e($firstName) ?: '—' ?></div>
            </div>
            <div class="col-sm-6">
              <div class="profile-field-label">Last Name</div>
              <div class="profile-field-val" id="dispLastName"><?= e($lastName) ?: '—' ?></div>
            </div>
            <div class="col-sm-6">
              <div class="profile-field-label">Email Address</div>
              <div class="profile-field-val" id="dispEmail"><?= e($email) ?></div>
            </div>
            <div class="col-sm-6">
              <div class="profile-field-label">Phone Number</div>
              <div class="profile-field-val" id="dispPhone"><?= e($phone) ?: '—' ?></div>
            </div>
            <div class="col-sm-6">
              <div class="profile-field-label">Member Since</div>
              <div class="profile-field-val"><?= $memberSince ?></div>
            </div>
            <div class="col-sm-6">
              <div class="profile-field-label">Account Status</div>
              <div><span class="badge bg-success">Active</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="acct-card overflow-hidden">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--border,#e9ecef);">
          <h6 class="fw-bold mb-0">Recent Orders</h6>
          <a href="<?= url('account/orders') ?>" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <?php if (empty($recentOrders)): ?>
        <div class="p-5 text-center text-muted">
          <i class="fa fa-bag-shopping fa-3x mb-3 d-block" style="color:var(--border,#ddd);"></i>
          <p class="mb-3">No orders yet.</p>
          <a href="<?= url('products') ?>" class="btn btn-primary btn-sm">Start Shopping</a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table acct-table align-middle mb-0">
            <thead>
              <tr>
                <th class="ps-4">Order #</th>
                <th>Date</th>
                <th class="d-none d-md-table-cell">Items</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($recentOrders as $o):
              $sc = ['pending'=>'warning','confirmed'=>'info','processing'=>'info',
                     'shipped'=>'primary','delivered'=>'success',
                     'cancelled'=>'danger','refunded'=>'secondary'][$o['status']] ?? 'secondary';
            ?>
            <tr>
              <td class="ps-4 fw-bold small"><?= e($o['order_number']) ?></td>
              <td class="small text-muted"><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
              <td class="small d-none d-md-table-cell"><?= (int)($o['item_count'] ?? 0) ?></td>
              <td class="fw-bold small" style="white-space:nowrap;">LKR <?= number_format($o['total_amount'],2) ?></td>
              <td><span class="badge bg-<?= $sc ?>"><?= ucfirst($o['status']) ?></span></td>
              <td><a href="<?= url('account/order/'.$o['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">View</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="profileForm" enctype="multipart/form-data" novalidate>
        <?= CSRF::field() ?>
        <div class="modal-body pt-3">

          <!-- Avatar upload row -->
          <div class="d-flex align-items-center gap-4 mb-4 p-3 rounded-3" style="background:var(--bg-soft,#f8f9fa);">
            <div style="position:relative;">
              <?php if ($avatarUrl): ?>
              <img src="<?= $avatarUrl ?>" id="avatarPreview" alt="Avatar">
              <?php else: ?>
              <div id="avatarPreview" class="d-flex align-items-center justify-content-center fw-bold fs-4"
                   style="width:72px;height:72px;border-radius:50%;background:var(--primary,#E63946);color:#fff;border:2px solid #eee;">
                <?= strtoupper(substr($firstName ?: 'U', 0, 1)) ?>
              </div>
              <?php endif; ?>
              <label for="avatarInput" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                     style="width:24px;height:24px;line-height:1;cursor:pointer;" title="Change photo">
                <i class="fa fa-camera" style="font-size:11px;"></i>
              </label>
            </div>
            <div>
              <div class="fw-semibold small">Profile Photo</div>
              <div class="text-muted" style="font-size:12px;">JPG, PNG or WebP · Max 5MB</div>
              <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none">
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" id="inputFirstName" class="form-control" required
                     value="<?= e($firstName) ?>">
              <div class="invalid-feedback">First name is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Last Name</label>
              <input type="text" name="last_name" id="inputLastName" class="form-control"
                     value="<?= e($lastName) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Email Address</label>
              <input type="email" class="form-control" value="<?= e($email) ?>" disabled>
              <div class="form-text">Email cannot be changed.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Phone Number</label>
              <input type="text" name="phone" id="inputPhone" class="form-control"
                     value="<?= e($phone) ?>" placeholder="+94 77 000 0000">
            </div>
          </div>

          <!-- Password section -->
          <hr class="my-4">
          <p class="fw-bold mb-3 small text-uppercase" style="letter-spacing:.05em;">
            Change Password
            <span class="text-muted fw-normal normal-case" style="text-transform:none;"> — leave blank to keep current</span>
          </p>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Current Password</label>
              <input type="password" name="current_password" id="inputCurPw" class="form-control" autocomplete="current-password">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">New Password</label>
              <input type="password" name="new_password" id="inputNewPw" class="form-control" minlength="8" autocomplete="new-password">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Confirm New</label>
              <input type="password" name="confirm_new_password" id="inputConfPw" class="form-control" autocomplete="new-password">
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4" id="saveProfileBtn">
            <i class="fa fa-floppy-disk me-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JSEOF'
<script>
(function () {

  // Avatar preview
  document.getElementById('avatarInput').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      var prev = document.getElementById('avatarPreview');
      if (prev.tagName === 'IMG') {
        prev.src = e.target.result;
      } else {
        // Replace the div with an img
        var img = document.createElement('img');
        img.id = 'avatarPreview';
        img.src = e.target.result;
        img.style.cssText = 'width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid #eee;cursor:pointer;';
        prev.replaceWith(img);
      }
    };
    reader.readAsDataURL(file);
  });

  // Profile form submit
  document.getElementById('profileForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Password validation
    var newPw  = document.getElementById('inputNewPw').value;
    var confPw = document.getElementById('inputConfPw').value;
    if (newPw && newPw !== confPw) {
      Swal.fire('Password Mismatch', 'New passwords do not match.', 'error');
      return;
    }
    if (newPw && newPw.length < 8) {
      Swal.fire('Too Short', 'Password must be at least 8 characters.', 'error');
      return;
    }
    if (!this.checkValidity()) {
      this.classList.add('was-validated');
      return;
    }

    var btn = document.getElementById('saveProfileBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

    var fd = new FormData(this);
    fd.set('_csrf', CSRF_TOKEN);

    $.ajax({
      url:         SITE_URL + '/account/update-profile',
      type:        'POST',
      data:        fd,
      processData: false,
      contentType: false,
      dataType:    'json',
      success: function (res) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-floppy-disk me-1"></i>Save Changes';
        if (res.success) {
          // Update displayed values in-place
          var u = res.user;
          var parts    = (u.full_name || '').split(' ');
          var firstName = parts[0] || '';
          var lastName  = parts.slice(1).join(' ');

          var set = function (id, val) {
            var el = document.getElementById(id);
            if (el) el.textContent = val || '—';
          };
          set('dispFirstName', firstName);
          set('dispLastName',  lastName);
          set('dispEmail',     u.email);
          set('dispPhone',     u.phone);

          // Update sidebar initial if no avatar
          var init = document.getElementById('profileAvatarInitial');
          if (init) init.textContent = (firstName[0] || 'U').toUpperCase();

          // Update avatar if uploaded
          if (u.avatar) {
            var avatarUrl = SITE_URL + '/uploads/avatars/' + u.avatar;
            var wrap = document.getElementById('profileAvatarWrap');
            if (wrap) {
              wrap.innerHTML = '<img src="' + avatarUrl + '" style="width:100%;height:100%;object-fit:cover;" alt="">';
            }
          }

          // Close modal, show toast
          bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
          Swal.fire({ icon: 'success', title: res.message, timer: 1600, showConfirmButton: false });

          // Sync form inputs with saved values
          document.getElementById('inputFirstName').value = firstName;
          document.getElementById('inputLastName').value  = lastName;
          document.getElementById('inputPhone').value     = u.phone || '';
          document.getElementById('inputCurPw').value     = '';
          document.getElementById('inputNewPw').value     = '';
          document.getElementById('inputConfPw').value    = '';

        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-floppy-disk me-1"></i>Save Changes';
        Swal.fire('Error', 'Request failed. Please try again.', 'error');
      }
    });
  });

})();
</script>
JSEOF;
?>
