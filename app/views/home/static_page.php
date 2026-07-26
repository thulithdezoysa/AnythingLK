<?php $pageTitle = e($page['title']); ?>
<div class="page-breadcrumb">
  <div class="container">
    <nav><ol class="breadcrumb mb-0 small">
      <li class="breadcrumb-item"><a href="<?= url('') ?>">Home</a></li>
      <li class="breadcrumb-item active"><?= e($page['title']) ?></li>
    </ol></nav>
  </div>
</div>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:40px;">
        <h1 class="fw-bold mb-4" style="color:var(--text-dark);font-size:28px;"><?= e($page['title']) ?></h1>
        <div style="color:var(--text-body);line-height:1.8;font-size:15px;">
          <?= $page['content'] ?>
        </div>
        <div class="mt-5 pt-4 border-top d-flex gap-3 flex-wrap" style="border-color:var(--border)!important;">
          <a href="<?= url('contact') ?>" class="btn btn-primary">Need More Help?</a>
          <a href="<?= url('') ?>" class="btn btn-outline-secondary">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
