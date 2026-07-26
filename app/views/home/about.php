<?php
$pageTitle = 'About Us';
$siteName  = setting('site_name', 'Anything.lk');

function fmtCount(int $n): string {
    if ($n >= 1000000) return round($n/1000000,1).'M+';
    if ($n >= 1000)    return round($n/1000,1).'K+';
    return $n > 0 ? $n.'+' : '0';
}
?>

<style>
.text-muted{color:var(--text-muted)!important;}

/* ── Page hero ── */
.about-hero{
  padding:clamp(2rem,5vw,4.5rem) 0 clamp(1.5rem,4vw,3.5rem);
  background:var(--bg-soft);
  border-bottom:1px solid var(--border);
  text-align:center;
}
.about-hero-icon{
  width:60px;height:60px;border-radius:16px;
  background:var(--primary,#E63946);color:#fff;
  display:flex;align-items:center;justify-content:center;
  font-size:24px;margin:0 auto 1rem;
  box-shadow:0 8px 24px rgba(230,57,70,.28);
}
.about-hero h1{font-size:clamp(1.4rem,5vw,2.6rem);font-weight:800;color:var(--text);margin-bottom:.4rem;}
.about-hero .sub{font-size:clamp(13px,1.5vw,16px);color:var(--text-muted);}

/* ── Stats — always 3 columns, shrink padding on mobile ── */
.about-stats{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:12px;
  margin-top:2rem;
}
.stat-box{
  background:var(--bg-card);border:1px solid var(--border);
  border-radius:14px;padding:1.25rem .75rem;text-align:center;transition:.2s;
}
.stat-box:hover{box-shadow:0 6px 18px rgba(0,0,0,.08);transform:translateY(-2px);}
.stat-icon{
  width:40px;height:40px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto .7rem;font-size:16px;
}
.stat-val{font-size:clamp(1.3rem,4vw,2rem);font-weight:800;line-height:1;color:var(--text);margin-bottom:.25rem;}
.stat-lbl{font-size:11px;color:var(--text-muted);font-weight:500;}

/* ── Story section ── */
.about-story{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:clamp(1.5rem,4vw,4rem);
  align-items:center;
  padding:clamp(2rem,5vw,4rem) 0;
}
@media(max-width:767px){
  .about-story{grid-template-columns:1fr;gap:1.75rem;}
  .about-story-text{order:1;}
  .about-story-visual{order:2;}
}
.about-story-text h2{
  font-size:clamp(1.2rem,3.5vw,2rem);font-weight:800;
  color:var(--text);margin-bottom:.85rem;
}
.about-story-text p{
  color:var(--text-muted);line-height:1.75;
  font-size:clamp(13px,1.3vw,15px);margin-bottom:.75rem;
}
.about-story-text p:last-of-type{margin-bottom:0;}

/* Visual brand card */
.about-visual-card{
  background:linear-gradient(135deg,var(--primary,#E63946) 0%,#c1121f 100%);
  border-radius:20px;
  padding:clamp(1.5rem,4vw,2.75rem);
  color:#fff;position:relative;overflow:hidden;
  min-height:220px;
  display:flex;flex-direction:column;justify-content:flex-end;
}
.about-visual-card::before{
  content:'';position:absolute;top:-40px;right:-40px;
  width:200px;height:200px;border-radius:50%;
  background:rgba(255,255,255,.07);pointer-events:none;
}
.about-visual-card::after{
  content:'';position:absolute;bottom:-50px;left:-30px;
  width:240px;height:240px;border-radius:50%;
  background:rgba(255,255,255,.05);pointer-events:none;
}
.about-vc-icon{
  font-size:clamp(3rem,7vw,5rem);opacity:.15;
  position:absolute;top:1rem;right:1.25rem;
}
.about-vc-title{
  font-size:clamp(1.1rem,2.8vw,1.7rem);font-weight:800;
  line-height:1.25;margin-bottom:.5rem;position:relative;z-index:1;
}
.about-vc-sub{
  font-size:clamp(11.5px,1.2vw,13.5px);opacity:.82;
  line-height:1.6;position:relative;z-index:1;
}
.about-vc-badges{
  display:flex;flex-wrap:wrap;gap:7px;
  margin-top:1rem;position:relative;z-index:1;
}
.about-vc-badge{
  display:inline-flex;align-items:center;gap:5px;
  background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);
  backdrop-filter:blur(4px);border-radius:50px;
  padding:4px 11px;font-size:10.5px;font-weight:600;color:#fff;
}

/* ── Values strip ── */
.about-values{
  background:var(--bg-soft);
  border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
  padding:clamp(1.75rem,4vw,3.5rem) 0;
}
.values-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:clamp(.875rem,2vw,1.75rem);
}
@media(max-width:767px){.values-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:449px){.values-grid{grid-template-columns:1fr;}}
.value-item{display:flex;align-items:flex-start;gap:12px;}
.value-item-icon{
  width:38px;height:38px;border-radius:10px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:15px;
}
.value-item-text h6{font-size:13.5px;font-weight:700;color:var(--text);margin:0 0 3px;}
.value-item-text p{font-size:12px;color:var(--text-muted);margin:0;line-height:1.55;}

/* ── Features grid ── */
.about-features-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:14px;
}
@media(max-width:991px){.about-features-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:449px){.about-features-grid{grid-template-columns:1fr;gap:12px;}}
.feature-card{
  background:var(--bg-card);border:1px solid var(--border);
  border-radius:14px;padding:1.4rem 1rem;
  text-align:center;transition:.2s;height:100%;
}
.feature-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.09);transform:translateY(-3px);}
.feature-icon{
  width:48px;height:48px;border-radius:13px;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto .875rem;font-size:19px;
}
.feature-card h6{font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:.4rem;}
.feature-card p{font-size:12px;color:var(--text-muted);margin:0;line-height:1.6;}

/* ── CTA ── */
.about-cta{
  background:var(--bg-card);border:1px solid var(--border);
  border-radius:20px;
  padding:clamp(1.75rem,5vw,3.5rem) clamp(1rem,4vw,2.5rem);
  text-align:center;
  margin:clamp(1.5rem,4vw,3rem) 0;
}
.about-cta h4{font-size:clamp(1.1rem,2.5vw,1.55rem);font-weight:800;color:var(--text);margin-bottom:.5rem;}
.about-cta p{color:var(--text-muted);margin-bottom:1.4rem;font-size:clamp(12.5px,1.2vw,15px);}
.about-cta .btn{font-size:clamp(12.5px,1.3vw,14px);}

/* ── Section spacing ── */
.about-section-pad{padding:clamp(2rem,5vw,4rem) 0;}
.about-sec-head{text-align:center;margin-bottom:clamp(1.25rem,3vw,2rem);}
.about-sec-head h3{font-size:clamp(1.15rem,2.8vw,1.7rem);font-weight:800;color:var(--text);margin-bottom:.35rem;}
.about-sec-head p{color:var(--text-muted);font-size:clamp(12.5px,1.2vw,14.5px);margin:0;}
</style>


<!-- ── Hero ── -->
<section class="about-hero">
  <div class="container">
    <div class="about-hero-icon"><i class="fas fa-store"></i></div>
    <h1>About <?= e($siteName) ?></h1>
    <p class="sub">Sri Lanka's premier online marketplace</p>
  </div>
</section>


<!-- ── Story + Visual ── -->
<div class="container">
  <div class="about-story">

    <div class="about-story-text">
      <h2>Our Story</h2>
      <p><?= e($siteName) ?> is one of Sri Lanka's fastest-growing e-commerce platforms, offering thousands of products across electronics, fashion, home goods, and more.</p>
      <p>Our mission is to make quality products accessible to everyone while empowering local businesses. We connect trusted vendors with customers across the island, ensuring competitive prices, secure payments, and reliable delivery.</p>

      <div class="about-stats">
        <div class="stat-box">
          <div class="stat-icon" style="background:rgba(99,102,241,.12);color:#6366f1;"><i class="fas fa-box"></i></div>
          <div class="stat-val"><?= fmtCount($productCount) ?></div>
          <div class="stat-lbl">Products</div>
        </div>
        <div class="stat-box">
          <div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981;"><i class="fas fa-users"></i></div>
          <div class="stat-val"><?= fmtCount($customerCount) ?></div>
          <div class="stat-lbl">Customers</div>
        </div>
        <div class="stat-box">
          <div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b;"><i class="fas fa-store"></i></div>
          <div class="stat-val"><?= fmtCount($vendorCount) ?></div>
          <div class="stat-lbl">Vendors</div>
        </div>
      </div>
    </div>

    <div class="about-story-visual">
      <div class="about-visual-card">
        <i class="fas fa-bag-shopping about-vc-icon"></i>
        <div class="about-vc-title">Shop Anything.<br>Delivered Island-Wide.</div>
        <div class="about-vc-sub">Thousands of products. Trusted vendors. Secure payments. Fast delivery to all 25 districts.</div>
        <div class="about-vc-badges">
          <span class="about-vc-badge"><i class="fas fa-shield-halved"></i>SSL Secure</span>
          <span class="about-vc-badge"><i class="fas fa-truck-fast"></i>Fast Delivery</span>
          <span class="about-vc-badge"><i class="fas fa-rotate-left"></i>Easy Returns</span>
        </div>
      </div>
    </div>

  </div>
</div>


<!-- ── Values ── -->
<div class="about-values">
  <div class="container">
    <div class="about-sec-head">
      <h3>What We Stand For</h3>
      <p>The principles that guide everything we do</p>
    </div>
    <div class="values-grid">
      <?php foreach ([
        ['fa-handshake', 'rgba(99,102,241,.12)', '#6366f1', 'Trust First',        'Every vendor is vetted. Every transaction is protected. Your trust is our foundation.'],
        ['fa-bolt',      'rgba(245,158,11,.12)', '#f59e0b', 'Speed & Simplicity', 'From browsing to delivery — we make the entire experience fast and effortless.'],
        ['fa-heart',     'rgba(239,68,68,.12)',  '#ef4444', 'Customer Obsessed',  'Our customers are at the center of every decision we make.'],
      ] as [$ico, $bg, $col, $title, $desc]): ?>
      <div class="value-item">
        <div class="value-item-icon" style="background:<?= $bg ?>;color:<?= $col ?>;"><i class="fas <?= $ico ?>"></i></div>
        <div class="value-item-text">
          <h6><?= $title ?></h6>
          <p><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<!-- ── Features + CTA ── -->
<div class="container">
  <div class="about-section-pad">
    <div class="about-sec-head">
      <h3>Why Shop With Us</h3>
      <p>The best online shopping experience in Sri Lanka</p>
    </div>
    <div class="about-features-grid">
      <?php foreach ([
        ['fa-shield-halved', 'rgba(99,102,241,.12)', '#6366f1', 'Secure Shopping',     'SSL encryption and trusted payment gateways protect every transaction.'],
        ['fa-truck-fast',    'rgba(16,185,129,.12)', '#10b981', 'Island-Wide Delivery', 'Fast delivery across all 25 districts within 1–7 business days.'],
        ['fa-rotate-left',   'rgba(245,158,11,.12)', '#f59e0b', 'Easy Returns',         '7-day hassle-free return policy for your complete peace of mind.'],
        ['fa-headset',       'rgba(239,68,68,.12)',  '#ef4444', '24/7 Support',          'Our support team is always ready to help you, anytime.'],
      ] as [$ico, $bg, $col, $title, $desc]): ?>
      <div class="feature-card">
        <div class="feature-icon" style="background:<?= $bg ?>;color:<?= $col ?>;"><i class="fas <?= $ico ?>"></i></div>
        <h6><?= $title ?></h6>
        <p><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="about-cta">
    <h4>Start Shopping Today</h4>
    <p>Discover thousands of products at unbeatable prices — delivered across Sri Lanka.</p>
    <div class="d-flex gap-2 justify-content-center flex-wrap">
      <a href="<?= url('products') ?>" class="btn btn-primary btn-sm px-4">
        <i class="fas fa-bag-shopping me-1"></i>Browse Products
      </a>
      <a href="<?= url('contact') ?>" class="btn btn-outline-secondary btn-sm px-4">
        <i class="fas fa-envelope me-1"></i>Contact Us
      </a>
    </div>
  </div>
</div>
