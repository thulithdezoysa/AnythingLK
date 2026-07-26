<?php $pageTitle = 'Forgot Password'; ?>
<div class="container py-5">
  <div class="mx-auto" style="max-width:420px;">

    <?php $_lgFile = setting('site_logo',''); $_lgName = setting('site_name','Anything.lk'); ?>
    <div class="text-center mb-4">
      <a href="<?= url('') ?>" class="d-inline-block text-decoration-none">
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
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-5">
        <h2 class="fw-bold mb-1 text-center">Forgot Password</h2>
        <p class="text-center text-muted small mb-4">Enter your email and we'll send a reset link.</p>
        <form id="forgotForm">
          <?= CSRF::field() ?>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required autofocus>
          </div>
          <button type="submit" class="btn btn-primary w-100" id="forgotBtn">Send Reset Link</button>
        </form>
        <div class="text-center mt-3 small">
          <a href="<?= url('login') ?>" style="color:var(--primary);">← Back to Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $extraScript = <<<'JS'
<script>
$('#forgotForm').on('submit', function(e) {
  e.preventDefault();
  const btn = $('#forgotBtn');
  btn.prop('disabled',true).html('<i class="fa fa-spinner fa-spin me-2"></i>Sending...');
  ajaxPost('forgot-password', {email:$('[name="email"]').val()}, function(res) {
    btn.prop('disabled',false).text('Send Reset Link');
    Swal.fire({icon:res.success?'success':'error', title:res.message, timer:3000, showConfirmButton:false});
  });
});
</script>
JS;
?>
