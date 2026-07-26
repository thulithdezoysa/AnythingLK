<?php $pageTitle = 'Shipping Policy'; ?>

<style>
.page-breadcrumb{background:var(--bg-soft);border-bottom:1px solid var(--border);padding:.6rem 0;}
.page-breadcrumb a{color:var(--primary,#E63946);text-decoration:none;}
.page-breadcrumb .breadcrumb-item.active{color:var(--text-muted);}
.page-breadcrumb .breadcrumb-item+.breadcrumb-item::before{color:var(--text-muted);}
.page-hero{padding:3.5rem 0 3rem;background:var(--bg-soft);border-bottom:1px solid var(--border);text-align:center;}
.page-hero-icon{width:60px;height:60px;border-radius:16px;background:var(--primary,#E63946);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 1.25rem;box-shadow:0 8px 24px rgba(230,57,70,.25);}
.text-muted{color:var(--text-muted) !important;}
/* Cards */
.policy-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;padding:1.5rem;box-shadow:0 1px 6px rgba(0,0,0,.05);}
.policy-card h5,.policy-card h6{color:var(--text);}
.policy-icon{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;font-size:13px;margin-right:.5rem;flex-shrink:0;vertical-align:middle;}
/* Highlight banner */
.highlight-banner{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:14px;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem;margin-bottom:2rem;}
.highlight-banner-icon{width:46px;height:46px;border-radius:12px;background:rgba(16,185,129,.18);color:#10b981;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
/* Shipping table */
.ship-table{width:100%;border-collapse:collapse;}
.ship-table thead tr{border-bottom:2px solid var(--border);}
.ship-table thead th{background:var(--bg-soft);color:var(--text-muted);font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;padding:.75rem 1rem;}
.ship-table tbody td{padding:.85rem 1rem;color:var(--text);border-bottom:1px solid var(--border);font-size:.9rem;}
.ship-table tbody tr:last-child td{border-bottom:none;}
.ship-table tbody tr:hover td{background:var(--bg-soft);}
.ship-price{font-weight:700;color:var(--text);}
.free-badge{display:inline-block;background:rgba(16,185,129,.12);color:#10b981;font-size:.75rem;font-weight:700;border-radius:6px;padding:2px 8px;margin-left:.4rem;}
.delivery-days{color:var(--text-muted);font-size:.85rem;}
.method-name{font-weight:600;color:var(--text);}
.method-desc{color:var(--text-muted);font-size:.8rem;margin-top:2px;}
</style>

<div class="page-breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0 small">
      <li class="breadcrumb-item"><a href="<?= url('') ?>"><i class="fas fa-home me-1"></i>Home</a></li>
      <li class="breadcrumb-item active">Shipping Policy</li>
    </ol>
  </div>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero-icon"><i class="fas fa-truck"></i></div>
    <h1 class="fw-bold mb-2" style="color:var(--text);">Shipping Policy</h1>
    <p class="text-muted mb-0">Last updated: <?= date('F Y') ?></p>
  </div>
</section>

<div class="container py-5">

  <?php
  $freeMethod = null;
  foreach ($shippingMethods as $m) {
      if ($m['is_free'] || ($m['free_above'] > 0 && ($freeMethod === null || $m['free_above'] < $freeMethod['free_above']))) {
          $freeMethod = $m;
      }
  }
  $freeThreshold = $freeMethod['free_above'] ?? null;
  ?>

  <!-- Free shipping banner -->
  <?php if ($freeThreshold > 0): ?>
  <div class="highlight-banner">
    <div class="highlight-banner-icon"><i class="fas fa-shipping-fast"></i></div>
    <div>
      <strong style="color:var(--text);font-size:1rem;">Free Shipping</strong>
      <span class="text-muted ms-1">on all orders over <strong style="color:var(--text);"><?= setting('currency','LKR') ?> <?= number_format($freeThreshold) ?></strong></span>
    </div>
  </div>
  <?php endif; ?>

  <!-- Shipping Methods Table -->
  <div class="policy-card mb-4">
    <h5 class="fw-bold mb-4">
      <i class="fas fa-box me-2" style="color:var(--primary,#E63946);"></i>Shipping Methods &amp; Rates
    </h5>
    <div class="table-responsive">
      <?php if ($shippingMethods): ?>
      <table class="ship-table">
        <thead>
          <tr>
            <th><i class="fas fa-truck me-1"></i>Method</th>
            <th><i class="fas fa-clock me-1"></i>Delivery Time</th>
            <th><i class="fas fa-tag me-1"></i>Cost</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($shippingMethods as $m): ?>
          <tr>
            <td>
              <div class="method-name"><?= e($m['name']) ?></div>
              <?php if (!empty($m['description'])): ?>
              <div class="method-desc"><?= e($m['description']) ?></div>
              <?php endif; ?>
            </td>
            <td class="delivery-days">
              <?php if ($m['min_days'] == $m['max_days']): ?>
                <?= (int)$m['min_days'] ?> day<?= $m['min_days'] != 1 ? 's' : '' ?>
              <?php else: ?>
                <?= (int)$m['min_days'] ?>–<?= (int)$m['max_days'] ?> business days
              <?php endif; ?>
            </td>
            <td>
              <?php if ($m['is_free']): ?>
                <span class="ship-price">Free</span>
              <?php else: ?>
                <span class="ship-price"><?= setting('currency','LKR') ?> <?= number_format($m['price']) ?></span>
                <?php if ($m['free_above'] > 0): ?>
                  <span class="free-badge">Free over <?= setting('currency','LKR') ?> <?= number_format($m['free_above']) ?></span>
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
      <p class="text-muted mb-0">Shipping information is currently being updated. Please contact us for details.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Info Grid -->
  <div class="row g-4">
    <?php
    $sections = [
      ['icon'=>'fa-globe-asia',     'bg'=>'rgba(99,102,241,.1)',  'color'=>'#6366f1', 'title'=>'Delivery Coverage',
       'body'=>'We deliver island-wide across all 25 districts of Sri Lanka. Remote areas may take an additional 1–2 days.'],
      ['icon'=>'fa-calendar-check', 'bg'=>'rgba(16,185,129,.1)',  'color'=>'#10b981', 'title'=>'Order Processing',
       'body'=>'Orders placed before <strong>2:00 PM</strong> are processed same day. Orders after that or on weekends are processed the next business day.'],
      ['icon'=>'fa-search-location','bg'=>'rgba(245,158,11,.1)',  'color'=>'#f59e0b', 'title'=>'Order Tracking',
       'body'=>'After shipping, you\'ll receive tracking details via SMS/email. Track your order <a href="'.url('order-tracking').'" style="color:var(--primary,#E63946);">here</a>.'],
      ['icon'=>'fa-headset',        'bg'=>'rgba(239,68,68,.1)',   'color'=>'#ef4444', 'title'=>'Need Help?',
       'body'=>'Contact us at <strong>'.e(setting('support_email','support@anything.lk')).'</strong> or <strong>'.e(setting('support_phone','+94 77 000 0000')).'</strong> if your order is delayed or missing.'],
    ];
    foreach ($sections as $s): ?>
    <div class="col-md-6">
      <div class="policy-card">
        <h6>
          <span class="policy-icon" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;"><i class="fas <?= $s['icon'] ?>"></i></span>
          <?= $s['title'] ?>
        </h6>
        <p class="text-muted small lh-lg mb-0"><?= $s['body'] ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>
