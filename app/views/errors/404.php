<?php $pageTitle = 'Page Not Found'; ?>
<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;">
  <div class="text-center" style="max-width:520px;">
    <!-- Animated 404 -->
    <div style="position:relative;margin-bottom:32px;">
      <div style="font-size:clamp(80px,15vw,160px);font-weight:900;line-height:1;background:linear-gradient(135deg,var(--brand),var(--accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
        404
      </div>
      <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:clamp(40px,8vw,80px);opacity:.08;font-weight:900;color:var(--text);pointer-events:none;white-space:nowrap;">
        NOT FOUND
      </div>
    </div>
    <h2 class="fw-bold mb-2" style="color:var(--text);">Oops! Page not found</h2>
    <p style="color:var(--text-muted);margin-bottom:32px;">
      The page you're looking for seems to have taken a detour. It may have been moved, deleted, or you might have typed the wrong URL.
    </p>
    <div class="d-flex flex-wrap gap-3 justify-content-center mb-4">
      <a href="<?= url('') ?>" class="btn-brand text-white d-inline-flex align-items-center gap-2" style="padding:12px 24px;border-radius:10px;font-weight:600;">
        <i class="fas fa-home"></i> Go Home
      </a>
      <a href="<?= url('products') ?>" class="btn-outline-brand d-inline-flex align-items-center gap-2" style="padding:11px 24px;border-radius:10px;font-weight:600;">
        <i class="fas fa-shopping-bag"></i> Browse Products
      </a>
      <a href="javascript:history.back()" class="d-inline-flex align-items-center gap-2 px-4 py-2" style="border-radius:10px;border:1.5px solid var(--border);color:var(--text-muted);font-weight:500;background:var(--bg);">
        <i class="fas fa-arrow-left"></i> Go Back
      </a>
    </div>
    <!-- Quick links -->
    <div class="card-base p-4 text-start">
      <p class="fw-semibold mb-3" style="color:var(--text);">Popular pages:</p>
      <div class="row g-2">
        <?php
        $quickLinks = [
          ['📱 Electronics', 'category/electronics'],
          ['👗 Fashion', 'category/fashion'],
          ['🏠 Home & Garden', 'category/home-garden'],
          ['📦 Track Order', 'order-tracking'],
          ['📞 Contact Us', 'contact'],
          ['❓ FAQ', 'faq'],
        ];
        foreach ($quickLinks as [$label, $path]): ?>
        <div class="col-6">
          <a href="<?= url($path) ?>" style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;background:var(--bg-soft);color:var(--text);font-size:13px;font-weight:500;transition:var(--transition);"
             onmouseover="this.style.background='var(--brand-light)';this.style.color='var(--brand)'"
             onmouseout="this.style.background='var(--bg-soft)';this.style.color='var(--text)'">
            <?= $label ?>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
