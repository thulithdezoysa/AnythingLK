<?php $pageTitle = 'Return & Refund Policy'; ?>

<style>
.text-muted{color:var(--text-muted) !important;}
.page-breadcrumb{background:var(--bg-soft);border-bottom:1px solid var(--border);padding:.6rem 0;}
.page-breadcrumb a{color:var(--primary,#E63946);text-decoration:none;}
.page-breadcrumb .breadcrumb-item.active{color:var(--text-muted);}
.page-breadcrumb .breadcrumb-item+.breadcrumb-item::before{color:var(--text-muted);}
.page-hero{padding:3.5rem 0 3rem;background:var(--bg-soft);border-bottom:1px solid var(--border);text-align:center;}
.page-hero-icon{width:60px;height:60px;border-radius:16px;background:var(--primary,#E63946);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 1.25rem;box-shadow:0 8px 24px rgba(230,57,70,.25);}
.policy-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:1.5rem;height:100%;box-shadow:0 1px 6px rgba(0,0,0,.05);}
.policy-card h6{color:var(--text);font-weight:700;margin-bottom:.85rem;}
.policy-card ul,.policy-card ol{color:var(--text-muted);margin-bottom:0;padding-left:1.2rem;}
.policy-card li{margin-bottom:.3rem;line-height:1.6;}
.policy-card p{color:var(--text-muted);margin-bottom:0;line-height:1.7;}
.policy-icon{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;font-size:13px;margin-right:.5rem;flex-shrink:0;vertical-align:middle;}
/* Highlight banner */
.highlight-banner{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:14px;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem;margin-bottom:2rem;}
.highlight-banner-icon{width:52px;height:52px;border-radius:14px;background:rgba(16,185,129,.18);color:#10b981;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;}
</style>

<div class="page-breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0 small">
      <li class="breadcrumb-item"><a href="<?= url('') ?>"><i class="fas fa-home me-1"></i>Home</a></li>
      <li class="breadcrumb-item active">Return &amp; Refund Policy</li>
    </ol>
  </div>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero-icon"><i class="fas fa-undo"></i></div>
    <h1 class="fw-bold mb-2" style="color:var(--text);">Return &amp; Refund Policy</h1>
    <p class="text-muted mb-0">Last updated: <?= date('F Y') ?></p>
  </div>
</section>

<div class="container py-5">

  <!-- Highlight -->
  <div class="highlight-banner">
    <div class="highlight-banner-icon"><i class="fas fa-check-circle"></i></div>
    <div>
      <strong style="color:var(--text);font-size:1.05rem;">7-Day Easy Returns</strong><br>
      <span class="text-muted small">Return your items within 7 days of delivery — simple and hassle-free.</span>
    </div>
  </div>

  <!-- Grid -->
  <div class="row g-4">

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="fas fa-clipboard-check"></i></span>
          Return Eligibility
        </h6>
        <ul class="small">
          <li>Item is unused and in original condition</li>
          <li>Original packaging is intact</li>
          <li>Return requested within 7 days</li>
          <li>Not a perishable or digital product</li>
        </ul>
      </div>
    </div>

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(239,68,68,.1);color:#ef4444;"><i class="fas fa-ban"></i></span>
          Non-Returnable Items
        </h6>
        <ul class="small">
          <li>Perishable goods (food, flowers)</li>
          <li>Digital downloads</li>
          <li>Opened hygiene products</li>
          <li>Hazardous materials</li>
          <li>Custom or personalized items</li>
        </ul>
      </div>
    </div>

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="fas fa-clipboard-list"></i></span>
          How to Return
        </h6>
        <ol class="small">
          <li>Contact us via email or phone</li>
          <li>Provide order number &amp; reason</li>
          <li>We arrange free pickup (24–48 hrs)</li>
          <li>Refund processed after inspection</li>
        </ol>
      </div>
    </div>

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(16,185,129,.1);color:#10b981;"><i class="fas fa-money-bill-wave"></i></span>
          Refund Timeline
        </h6>
        <ul class="small">
          <li><strong>Card / Online:</strong> 5–7 business days</li>
          <li><strong>Bank Transfer:</strong> 3–5 business days</li>
          <li><strong>COD:</strong> Bank transfer (5–7 days)</li>
          <li><strong>KOKO / MintPay:</strong> As per provider policy</li>
        </ul>
      </div>
    </div>

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(99,102,241,.1);color:#6366f1;"><i class="fas fa-exchange-alt"></i></span>
          Exchange Policy
        </h6>
        <p class="small">Need a different size or color? We offer free exchanges within 7 days. Contact our support team to arrange it.</p>
      </div>
    </div>

    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;"><i class="fas fa-headset"></i></span>
          Need Help?
        </h6>
        <p class="small">
          Email: <strong><?= e(setting('support_email','support@anything.lk')) ?></strong><br>
          Phone: <strong><?= e(setting('support_phone','+94 77 000 0000')) ?></strong>
        </p>
      </div>
    </div>

  </div>
</div>
