<?php $pageTitle = __('auth.login'); ?>

<div style="min-height:calc(100vh - 200px);display:flex;align-items:center;background:var(--bg-soft);">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">

      <!-- Logo / brand -->
      <?php $_lgFile = setting('site_logo',''); $_lgName = setting('site_name','Anything.lk'); ?>
      <div class="text-center mb-4">
        <a href="<?= url('') ?>" class="d-inline-block mb-2 text-decoration-none">
          <?php if ($_lgFile): ?>
            <img src="<?= url('uploads/logo/'.e($_lgFile)) ?>" alt="<?= e($_lgName) ?>"
                 style="max-height:52px;max-width:200px;object-fit:contain;">
          <?php else: ?>
            <span class="site-logo" style="font-size:28px;"><?php
              $__p = explode('.', $_lgName, 2);
              echo e($__p[0]);
              if (isset($__p[1])) echo '<span>.'.e($__p[1]).'</span>';
            ?></span>
          <?php endif; ?>
        </a>
        <h5 class="fw-bold mb-1" style="color:var(--text);">Welcome back!</h5>
        <p class="small" style="color:var(--text-muted);">Sign in to your account to continue shopping</p>
      </div>

      <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px 28px;box-shadow:var(--shadow-lg);">

        <div id="loginAlert" class="alert d-none mb-3"></div>

        <form id="loginForm" method="POST" action="<?= url('login') ?>">
          <?= CSRF::field() ?>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px;"><?= __('auth.email') ?></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope text-muted" style="font-size:13px;"></i></span>
              <input type="email" name="email" class="form-control" required autofocus
                     placeholder="you@example.com" style="font-size:14px;">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold d-flex justify-content-between" style="font-size:13px;">
              <?= __('auth.password') ?>
              <a href="<?= url('forgot-password') ?>" class="small fw-normal" style="color:var(--primary);">Forgot password?</a>
            </label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock text-muted" style="font-size:13px;"></i></span>
              <input type="password" name="password" id="passwordField" class="form-control" required
                     placeholder="••••••••" style="font-size:14px;">
              <button type="button" class="btn btn-outline-secondary" id="togglePassBtn" style="border-radius:0 10px 10px 0!important;">
                <i class="fa fa-eye" id="eyeIcon" style="font-size:13px;"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold py-2" id="loginBtn" style="font-size:15px;">
            <i class="fas fa-sign-in-alt me-2"></i><?= __('auth.login') ?>
          </button>
        </form>

        <div class="text-center mt-4 pt-3" style="border-top:1px solid var(--border);">
          <span class="small" style="color:var(--text-muted);"><?= __('auth.no_account') ?></span>
          <a href="<?= url('register') ?>" class="small fw-bold ms-1" style="color:var(--primary);"><?= __('auth.register') ?> →</a>
        </div>
      </div>

      <!-- Trust badges -->
      <div class="d-flex justify-content-center gap-4 mt-4 text-center">
        <?php foreach ([['fas fa-shield-alt','Secure Login'],['fas fa-lock','SSL Protected'],['fas fa-user-shield','Privacy Safe']] as $t): ?>
        <div class="small" style="color:var(--text-muted);font-size:11px;">
          <i class="<?= $t[0] ?> mb-1 d-block" style="font-size:18px;color:var(--brand);"></i><?= $t[1] ?>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>
</div>

<?php $extraScript = <<<'JSEOF'
<script>
document.getElementById('togglePassBtn').addEventListener('click', function() {
  const f = document.getElementById('passwordField');
  const i = document.getElementById('eyeIcon');
  f.type = (f.type === 'password') ? 'text' : 'password';
  i.className = (f.type === 'password') ? 'fa fa-eye' : 'fa fa-eye-slash';
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const btn   = document.getElementById('loginBtn');
  const alert = document.getElementById('loginAlert');
  const csrf  = this.querySelector('[name="_csrf"]')?.value || CSRF_TOKEN;
  alert.className = 'alert d-none';
  btn.disabled = true;
  btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Signing in...';

  $.ajax({
    url: SITE_URL + '/login',
    type: 'POST',
    data: {
      email:    this.querySelector('[name="email"]').value.trim(),
      password: this.querySelector('[name="password"]').value,
      _csrf:    csrf
    },
    dataType: 'json',
    success: function(res) {
      btn.disabled = false;
      if (res.success) {
        btn.innerHTML = '<i class="fa fa-check me-2"></i>Success!';
        btn.className = 'btn btn-success w-100 fw-bold py-2';
        setTimeout(function() { window.location.href = res.redirect; }, 700);
      } else {
        btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
        alert.className = 'alert alert-danger';
        alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + res.message;
      }
    },
    error: function(xhr) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
      let msg = 'Network error. Please try again.';
      try { msg = JSON.parse(xhr.responseText).message || msg; } catch(ex) {}
      alert.className = 'alert alert-danger';
      alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + msg;
    }
  });
});
</script>
JSEOF;
?>
