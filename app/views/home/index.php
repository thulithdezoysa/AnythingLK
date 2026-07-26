<?php
$pageTitle = setting('site_name', 'Anything.lk');

if (!function_exists('bannerPosStyles')) {
    function bannerPosStyles(string $pos): array {
        $p = explode('-', $pos, 2);
        return [
            'justify'   => ['left'=>'flex-start','center'=>'center','right'=>'flex-end'][$p[1]??'left'] ?? 'flex-start',
            'align'     => ['top'=>'flex-start','middle'=>'center','bottom'=>'flex-end'][$p[0]??'middle'] ?? 'center',
            'textAlign' => ['left'=>'left','center'=>'center','right'=>'right'][$p[1]??'left'] ?? 'left',
        ];
    }
}

// Featured products for hero grid (right panel of secondary hero)
$heroGridProducts = array_slice($featured ?? [], 0, 8);
?>

<style>
/* ═══════════════════════════════════════════════
   HOMEPAGE DESIGN SYSTEM
═══════════════════════════════════════════════ */
.section-heading {
  font-family: 'Outfit', sans-serif;
  font-size: clamp(19px,2.2vw,27px);
  font-weight: 800;
  color: var(--text);
  letter-spacing: -.02em;
}
.hp-view-all {
  display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;
  color:var(--brand);white-space:nowrap;text-decoration:none;padding:6px 14px;
  border:1.5px solid rgba(230,57,70,.3);border-radius:50px;transition:var(--transition);
}
.hp-view-all:hover{background:var(--brand-light);border-color:var(--brand);color:var(--brand);}

.swiper-btn {
  position:absolute;top:50%;transform:translateY(-50%);
  width:36px;height:36px;border-radius:50%;border:1.5px solid var(--border);
  background:var(--bg-card);color:var(--text);display:flex;align-items:center;
  justify-content:center;cursor:pointer;z-index:4;transition:var(--transition);font-size:12px;
}
.swiper-btn:hover{background:var(--brand);border-color:var(--brand);color:#fff;}
.swiper-btn.prev{left:-16px;} .swiper-btn.next{right:-16px;}
.product-carousel-wrap{padding:0 4px;}
@media(max-width:767px){
  .swiper-btn{display:none;}
  .product-carousel > div{width:calc(50% - 7px)!important;min-width:148px!important;}
}

/* ── §1 Hero Carousel ─────────────────────────── */
.hero-carousel-item {
  height: clamp(360px, 50vw, 520px);
  position: relative;
  overflow: hidden;
}
.hero-carousel-item img {
  position: absolute;inset:0;width:100%;height:100%;object-fit:cover;
}
.hero-overlay {
  position: absolute;inset:0;
  background: rgba(0,0,0,.38);
}
/* Content wrapper — overrideable on mobile */
.hero-content-wrap {
  position: absolute;inset:0;display:flex;
}
.hero-content {
  animation-duration: .5s;
}
/* Carousel prev/next — larger tap targets on mobile */
#heroCarousel .carousel-control-prev,
#heroCarousel .carousel-control-next {
  width: 44px;
}
/* Indicators — slightly bigger dots */
#heroCarousel .carousel-indicators button {
  border-radius: 50%;
  width: 8px; height: 8px;
  transition: width .2s, border-radius .2s;
}
#heroCarousel .carousel-indicators .active {
  width: 22px; border-radius: 4px;
}
/* ── Tablet hero ── */
@media (min-width: 576px) and (max-width: 991.98px) {
  .hero-carousel-item { height: clamp(300px, 45vw, 420px); }
  .hero-content { max-width: 420px !important; }
}
/* ── Mobile hero ── */
@media (max-width: 575.98px) {
  .hero-carousel-item {
    height: clamp(240px, 72vw, 320px);
  }
  .hero-content-wrap {
    align-items: flex-end !important;
    justify-content: center !important;
    padding: 0 !important;
    /* gradient scrim so text is always readable regardless of image */
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.1) 55%, transparent 100%);
  }
  .hero-content {
    max-width: 100% !important;
    text-align: center !important;
    width: 100%;
    padding: 16px 20px 22px;
  }
  .hero-content h1 { margin-bottom: 10px !important; }
  .hero-content-desc { display: none; }      /* hide body text — too cramped */
  .hero-content-subtitle { display: none; }  /* hide label tag line */
  .hero-cta-btn {
    padding: 10px 22px !important;
    font-size: 13px !important;
  }
  /* Hide prev/next arrows on mobile — swipe is enough */
  #heroCarousel .carousel-control-prev,
  #heroCarousel .carousel-control-next { display: none; }
  /* Indicators above bottom edge */
  #heroCarousel .carousel-indicators { bottom: 6px; margin-bottom: 0; }
}

/* ── §2 Trust strip ───────────────────────────── */
.trust-strip {
  background: var(--brand);
  padding: 12px 0;
}
.trust-strip-inner {
  display: grid;grid-template-columns: repeat(4,1fr);gap:0;
}
@media(max-width:767px){.trust-strip-inner{grid-template-columns:repeat(2,1fr);gap:8px 0;}}
.trust-item {
  display:flex;align-items:center;justify-content:center;gap:9px;
  padding:8px 12px;border-right:1px solid rgba(255,255,255,.2);
  color:#fff;
}
.trust-item:last-child{border-right:none;}
.trust-item i{color:var(--accent);font-size:16px;flex-shrink:0;}
.trust-item-label{font-size:12.5px;font-weight:600;line-height:1.2;}
.trust-item-sub{font-size:10.5px;opacity:.75;}

/* ── §5 Collection Hero ───────────────────────── */
@keyframes blink-hp{0%,100%{opacity:1}50%{opacity:.3}}
.hs-collection {
  background: linear-gradient(135deg, #0f0507 0%, #1a0408 60%, #0f0507 100%);
  padding: clamp(40px,5vw,72px) 0;
  position: relative;
  overflow: hidden;
}
.hs-collection::before {
  content:'';position:absolute;top:-20%;right:-5%;width:500px;height:500px;
  background:radial-gradient(circle,rgba(230,57,70,.07) 0%,transparent 65%);
  pointer-events:none;
}
.hs-col-grid {
  display:grid;grid-template-columns:280px 1fr;gap:2.5rem;align-items:center;
}
@media(max-width:991px){
  .hs-col-grid{grid-template-columns:1fr;gap:1.75rem;}
  .hs-col-left{text-align:center;}
  .hs-col-trust{justify-content:center;}
}
.hs-col-badge {
  display:inline-flex;align-items:center;gap:6px;
  background:rgba(230,57,70,.12);border:1px solid rgba(230,57,70,.3);
  color:var(--brand);font-size:11px;font-weight:700;letter-spacing:.1em;
  text-transform:uppercase;padding:5px 14px;border-radius:50px;margin-bottom:1.2rem;
}
.hs-col-badge-dot {
  width:6px;height:6px;background:var(--brand);border-radius:50%;
  animation:blink-hp 1.4s infinite;flex-shrink:0;
}
.hs-col-title {
  font-family:'Outfit',sans-serif;font-size:clamp(24px,3vw,42px);
  font-weight:900;line-height:1.12;color:#fff;margin-bottom:.875rem;letter-spacing:-.03em;
}
.hs-col-title span {
  background:linear-gradient(90deg,var(--brand),#ff8a93);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hs-col-sub {
  font-size:clamp(13px,1.2vw,15px);color:rgba(255,255,255,.5);
  line-height:1.7;margin-bottom:1.75rem;
}
.hs-col-cta {
  display:inline-flex;align-items:center;gap:.5rem;
  background:linear-gradient(135deg,var(--brand),var(--brand-dark));
  color:#fff;font-weight:700;font-size:14px;padding:12px 26px;
  border-radius:50px;transition:var(--transition);
  box-shadow:0 0 28px rgba(230,57,70,.35);text-decoration:none;
}
.hs-col-cta:hover{transform:translateY(-2px);box-shadow:0 0 36px rgba(230,57,70,.5);color:#fff;}
.hs-col-trust {
  display:flex;flex-direction:column;gap:8px;margin-top:1.5rem;
}
.hs-col-trust-item {
  display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.4);
}
.hs-col-trust-item i{color:var(--brand);font-size:11px;width:13px;text-align:center;}

/* ── §4 Collections ───────────────────────────── */
.hp-cat-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
@media(max-width:575px){.hp-cat-grid{grid-template-columns:1fr;}}
.hp-cat-card {
  position:relative;border-radius:18px;overflow:hidden;min-height:240px;
  display:flex;flex-direction:column;justify-content:flex-end;
  text-decoration:none;cursor:pointer;transition:var(--transition);
}
@media(max-width:767px){.hp-cat-card{min-height:180px;}}
.hp-cat-card:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(0,0,0,.18);}
.hp-cat-card:hover .hp-cat-img{transform:scale(1.06);}
.hp-cat-card:hover .hp-cat-btn{opacity:1;transform:translateY(0);}
.hp-cat-bg{position:absolute;inset:0;}
.hp-cat-img{width:100%;height:100%;object-fit:cover;transition:transform .55s cubic-bezier(.4,0,.2,1);}
.hp-cat-icon-bg{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3.5rem;opacity:.12;}
.hp-cat-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.85) 0%,rgba(0,0,0,.2) 55%,transparent 100%);}
.hp-cat-body{position:relative;z-index:1;padding:20px 22px;}
.hp-cat-tag{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:5px;}
.hp-cat-name{font-family:'Outfit',sans-serif;font-size:clamp(17px,2vw,22px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:8px;}
.hp-cat-btn{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#fff;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);backdrop-filter:blur(6px);padding:7px 16px;border-radius:50px;transition:var(--transition);opacity:.8;transform:translateY(4px);}
.hp-cat-btn:hover{background:rgba(255,255,255,.22);color:#fff;}

/* ── §5 Reviews ───────────────────────────────── */
/* ══ Reviews section ══════════════════════════════════ */
.hp-reviews {
  background: var(--bg-soft);
  position: relative;
  overflow: hidden;
}
.hp-reviews::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 700px 400px at 10% 50%, rgba(230,57,70,.04) 0%, transparent 70%),
    radial-gradient(ellipse 500px 500px at 90% 20%, rgba(230,57,70,.03) 0%, transparent 70%);
  pointer-events: none;
}

/* header */
.hp-rev-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 36px;
  flex-wrap: wrap;
}
.hp-rev-see-all {
  font-size: 13px; font-weight: 600; color: var(--brand);
  text-decoration: none; white-space: nowrap;
  display: flex; align-items: center; gap: 6px;
  padding: 8px 18px; border-radius: 24px;
  border: 1.5px solid rgba(230,57,70,.3);
  transition: var(--transition); flex-shrink: 0;
}
.hp-rev-see-all:hover { background: var(--brand); color: #fff; border-color: var(--brand); }

/* stats bar */
.hp-rev-stats {
  display: grid;
  grid-template-columns: auto 1px auto 1px auto;
  align-items: center;
  gap: 0;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 24px 32px;
  margin-bottom: 36px;
}
@media(max-width:767px) {
  .hp-rev-stats { grid-template-columns: 1fr; gap: 20px; padding: 20px; }
  .hp-rev-divider { display: none; }
}
.hp-rev-divider { width: 1px; height: 56px; background: var(--border); margin: 0 32px; }
.hp-rev-stat-score {
  font-family: 'Outfit', sans-serif;
  font-size: 4.5rem; font-weight: 900; line-height: 1;
  color: var(--text);
  letter-spacing: -2px;
}
.hp-rev-stat-stars { display: flex; gap: 4px; margin: 6px 0 4px; }
.hp-rev-stat-stars i { color: #f59e0b; font-size: 18px; }
.hp-rev-stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
.hp-rev-bars { flex: 1; display: flex; flex-direction: column; gap: 6px; min-width: 180px; }
.hp-rev-bar-row { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--text-muted); }
.hp-rev-bar-row span:first-child { width: 20px; text-align: right; flex-shrink: 0; }
.hp-rev-bar-track { flex: 1; height: 6px; background: var(--border); border-radius: 4px; overflow: hidden; }
.hp-rev-bar-fill { height: 100%; background: #f59e0b; border-radius: 4px; transition: width .8s cubic-bezier(.4,0,.2,1); }
.hp-rev-bar-row span:last-child { width: 26px; flex-shrink: 0; }
.hp-rev-stat-num { text-align: center; }
.hp-rev-stat-big { font-family: 'Outfit', sans-serif; font-size: 2.2rem; font-weight: 900; color: var(--text); line-height: 1; }
.hp-rev-stat-sub { font-size: 12px; color: var(--text-muted); margin-top: 5px; }

/* carousel */
.hp-rev-carousel-wrap {
  position: relative;
}
.rev-track {
  display: flex; gap: 16px;
  overflow-x: auto; scroll-snap-type: x mandatory;
  scrollbar-width: none; padding-bottom: 4px;
  cursor: grab;
}
.rev-track.dragging { cursor: grabbing; scroll-behavior: auto; }
.rev-track::-webkit-scrollbar { display: none; }

/* review card */
.hp-review-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 24px;
  flex: 0 0 calc(33.333% - 11px);
  scroll-snap-align: start;
  position: relative;
  transition: var(--transition);
  display: flex; flex-direction: column; gap: 0;
}
@media(max-width:991px) { .hp-review-card { flex: 0 0 calc(50% - 8px); } }
@media(max-width:575px)  { .hp-review-card { flex: 0 0 calc(85% - 6px); } }
.hp-review-card:hover {
  border-color: var(--brand);
  box-shadow: 0 12px 36px rgba(230,57,70,.1);
  transform: translateY(-3px);
}

/* quote decoration */
.hp-review-quote {
  font-size: 52px; line-height: 1; font-family: Georgia, serif;
  color: var(--brand); opacity: .18; position: absolute;
  top: 14px; right: 20px; pointer-events: none; user-select: none;
}

/* stars */
.hp-review-stars {
  display: flex; gap: 3px; margin-bottom: 12px;
}
.hp-review-stars i { color: #f59e0b; font-size: 13px; }

/* text */
.hp-review-text {
  font-size: 13.5px; color: var(--text); line-height: 1.7;
  flex: 1; margin-bottom: 18px;
  display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
}

/* footer */
.hp-review-foot {
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
  padding-top: 14px; border-top: 1px solid var(--border);
}
.hp-review-user { display: flex; align-items: center; gap: 10px; min-width: 0; }
.hp-review-avatar {
  width: 40px; height: 40px; border-radius: 50%;
  background: linear-gradient(135deg, var(--brand) 0%, #ff6b6b 100%);
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; font-weight: 700; color: #fff; flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(230,57,70,.3);
}
.hp-review-info { min-width: 0; }
.hp-review-name { font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.hp-review-meta { display: flex; align-items: center; gap: 6px; margin-top: 2px; }
.hp-review-ago { font-size: 11px; color: var(--text-muted); }
.hp-verified {
  font-size: 10px; font-weight: 600; color: #10b981;
  background: rgba(16,185,129,.1);
  padding: 1px 7px; border-radius: 10px;
  display: flex; align-items: center; gap: 3px;
}
.hp-review-src { width: 20px; height: 20px; flex-shrink: 0; opacity: .7; }

/* nav buttons */
.hp-rev-nav {
  display: flex; align-items: center; justify-content: center;
  gap: 10px; margin-top: 24px;
}
.hp-rev-nav-btn {
  width: 38px; height: 38px; border-radius: 50%;
  border: 1.5px solid var(--border); background: var(--bg-card);
  color: var(--text-muted); cursor: pointer; font-size: 12px;
  display: flex; align-items: center; justify-content: center;
  transition: var(--transition);
}
.hp-rev-nav-btn:not(:disabled):hover { background: var(--brand); color: #fff; border-color: var(--brand); }
.hp-rev-nav-btn:disabled { opacity: .3; cursor: default; }
.hp-rev-dots { display: flex; gap: 6px; align-items: center; }
.hp-rev-dot {
  width: 7px; height: 7px; border-radius: 50%; border: none; cursor: pointer; padding: 0;
  background: var(--border); transition: background .2s, transform .2s, width .2s;
}
.hp-rev-dot.active { background: var(--brand); transform: scale(1.25); width: 20px; border-radius: 4px; }

/* Section header */
.hp-sh{margin-bottom:1.75rem;}
.hp-sh-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--brand);margin-bottom:4px;}
.hp-sh-title{font-family:'Outfit',sans-serif;font-size:clamp(20px,2.5vw,30px);font-weight:800;color:var(--text);line-height:1.15;letter-spacing:-.02em;margin:0;}
</style>


<!-- ══════════════════════════════════════════
     §1 — HERO CAROUSEL (Restored)
══════════════════════════════════════════ -->
<section class="mb-0">
<?php if (!empty($banners)): ?>
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
  <div class="carousel-indicators">
    <?php foreach ($banners as $i => $b): ?>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>"
            <?= $i===0?'class="active"':'' ?> style="width:8px;height:8px;border-radius:50%;"></button>
    <?php endforeach; ?>
  </div>
  <div class="carousel-inner">
    <?php foreach ($banners as $i => $b):
      $ps     = bannerPosStyles($b['desc_position'] ?? 'middle-left');
      $isDark = ($b['text_color'] ?? 'light') === 'dark';
      $clr    = $isDark ? '#111' : '#fff';
      $opac   = $isDark ? '.7' : '.85';
    ?>
    <div class="carousel-item <?= $i===0?'active':'' ?>">
      <div class="hero-carousel-item" style="background:linear-gradient(135deg,var(--brand) 0%,var(--brand-dark) 100%);">
        <?php if (!empty($b['image'])): ?>
        <img src="<?= url('uploads/banners/'.e($b['image'])) ?>" alt="<?= e($b['title']??'') ?>" loading="<?= $i===0?'eager':'lazy' ?>">
        <?php endif; ?>
        <div class="hero-overlay" style="background:<?= $isDark?'rgba(255,255,255,.3)':'rgba(0,0,0,.35)' ?>;"></div>
        <div class="hero-content-wrap" style="align-items:<?= $ps['align'] ?>;justify-content:<?= $ps['justify'] ?>;padding:clamp(24px,5%,60px) clamp(20px,6%,80px);">
          <div class="hero-content animate__animated animate__fadeIn" style="max-width:540px;text-align:<?= $ps['textAlign'] ?>;">
            <?php if ($b['subtitle']): ?>
            <div class="hero-content-subtitle" style="font-size:12px;color:<?= $clr ?>;opacity:<?= $opac ?>;text-transform:uppercase;letter-spacing:.12em;margin-bottom:10px;">
              <i class="fa-solid fa-tag me-2"></i><?= e($b['subtitle']) ?>
            </div>
            <?php endif; ?>
            <?php if ($b['title']): ?>
            <h1 style="font-family:'Outfit',sans-serif;font-size:clamp(22px,4vw,56px);line-height:1.08;margin-bottom:14px;font-weight:900;color:<?= $clr ?>;">
              <?= e($b['title']) ?>
            </h1>
            <?php endif; ?>
            <?php if (!empty($b['description'])): ?>
            <p class="hero-content-desc" style="font-size:clamp(13px,1.4vw,17px);color:<?= $clr ?>;opacity:<?= $opac ?>;margin-bottom:22px;line-height:1.55;">
              <?= e($b['description']) ?>
            </p>
            <?php endif; ?>
            <?php if ($b['link']): ?>
            <a href="<?= e($b['link']) ?>" class="hero-cta-btn"
               style="display:inline-flex;align-items:center;gap:8px;background:var(--grad-brand);color:#fff;font-weight:700;font-size:14px;padding:13px 28px;border-radius:50px;text-decoration:none;box-shadow:0 4px 20px rgba(230,57,70,.35);transition:var(--transition);">
              Shop Now <i class="fa-solid fa-arrow-right"></i>
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<?php else: ?>
<!-- Fallback hero -->
<div style="background:var(--grad-brand);min-height:clamp(340px,45vw,500px);display:flex;align-items:center;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 600px 500px at 70% 50%,rgba(244,162,97,.25),transparent);"></div>
  <div class="container text-center text-white py-5" style="position:relative;z-index:1;">
    <div class="animate__animated animate__fadeInDown">
      <div style="font-size:12px;opacity:.75;letter-spacing:.15em;text-transform:uppercase;margin-bottom:14px;">
        <i class="fa-solid fa-star me-2"></i>Sri Lanka's Premier Electronics Marketplace
      </div>
      <h1 style="font-family:'Outfit',sans-serif;font-size:clamp(30px,5vw,62px);line-height:1.08;font-weight:900;margin-bottom:14px;">
        Shop Anything.<br>Deliver Everywhere.
      </h1>
      <p style="font-size:17px;opacity:.8;margin-bottom:28px;line-height:1.6;">Thousands of products. Unbeatable prices. Island-wide delivery.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= url('products') ?>"
           style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--brand);font-weight:700;padding:13px 30px;border-radius:50px;text-decoration:none;font-size:15px;box-shadow:0 4px 20px rgba(0,0,0,.2);">
          Shop Now <i class="fa-solid fa-arrow-right"></i>
        </a>
        <a href="<?= url('products?sort=popular') ?>"
           style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.5);color:#fff;font-weight:600;padding:12px 24px;border-radius:50px;text-decoration:none;backdrop-filter:blur(6px);">
          Best Sellers
        </a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</section>


<!-- ══════════════════════════════════════════
     §2 — TRUST STRIP
══════════════════════════════════════════ -->
<div class="trust-strip">
  <div class="container">
    <div class="trust-strip-inner">
      <?php foreach ([
        ['fa-truck-fast',    'Free Shipping',   'Orders over LKR 5,000'],
        ['fa-shield-halved', 'Secure Payments', 'SSL encrypted checkout'],
        ['fa-rotate-left',   'Easy Returns',    '7-day hassle-free'],
        ['fa-headset',       '24/7 Support',    'Always here to help'],
      ] as $t): ?>
      <div class="trust-item">
        <i class="fa-solid <?= $t[0] ?>"></i>
        <div>
          <div class="trust-item-label"><?= $t[1] ?></div>
          <div class="trust-item-sub"><?= $t[2] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════
     §3 — CATEGORIES (Icons)
══════════════════════════════════════════ -->
<?php if (!empty($categories)): ?>
<section class="py-5" style="background:var(--bg);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
      <h2 class="section-heading mb-0">Shop by Category</h2>
      <a href="<?= url('products') ?>" class="hp-view-all">All Products <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="d-none d-md-block" data-aos="fade-up" data-aos-delay="80">
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;">
        <?php foreach ($categories as $cat): ?>
        <a href="<?= url('category/'.e($cat['slug'])) ?>" class="text-decoration-none">
          <div class="cat-card text-center" style="padding:18px 10px;transition:var(--transition);"
               onmouseover="this.style.borderColor='var(--brand)'" onmouseout="this.style.borderColor=''">
            <div class="cat-icon" style="width:52px;height:52px;font-size:20px;margin-bottom:10px;">
              <?php if (!empty($cat['image'])): ?>
              <img src="<?= url('uploads/categories/'.e($cat['image'])) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" alt="" loading="lazy">
              <?php else: ?>
              <i class="fa-solid <?= e($cat['icon']??'fa-tag') ?>" style="color:var(--brand);"></i>
              <?php endif; ?>
            </div>
            <div class="cat-name" style="font-size:12px;"><?= e($cat['name']) ?></div>
            <?php if (!empty($cat['product_count']) && $cat['product_count'] > 0): ?>
            <div class="cat-count mt-1" style="font-size:10.5px;color:var(--text-muted);"><?= $cat['product_count'] ?> items</div>
            <?php endif; ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="d-md-none" data-aos="fade-up">
      <div style="display:flex;gap:10px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding-bottom:6px;" id="catCarousel">
        <?php foreach ($categories as $cat): ?>
        <a href="<?= url('category/'.e($cat['slug'])) ?>" class="text-decoration-none flex-shrink-0" style="width:105px;scroll-snap-align:start;">
          <div class="cat-card text-center" style="padding:14px 8px;">
            <div class="cat-icon" style="width:44px;height:44px;font-size:17px;margin-bottom:8px;">
              <?php if (!empty($cat['image'])): ?>
              <img src="<?= url('uploads/categories/'.e($cat['image'])) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" alt="" loading="lazy">
              <?php else: ?>
              <i class="fa-solid <?= e($cat['icon']??'fa-tag') ?>" style="color:var(--brand);"></i>
              <?php endif; ?>
            </div>
            <div class="cat-name" style="font-size:11px;"><?= e($cat['name']) ?></div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §4 — FEATURED PRODUCTS
══════════════════════════════════════════ -->
<?php if (!empty($featured)): ?>
<section class="py-5" style="background:var(--bg-soft);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
      <h2 class="section-heading mb-0">Featured Products</h2>
      <a href="<?= url('products?featured=1') ?>" class="hp-view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="position-relative product-carousel-wrap" data-aos="fade-up" data-aos-delay="80">
      <button class="swiper-btn prev" id="featPrev"><i class="fa-solid fa-chevron-left"></i></button>
      <div class="overflow-hidden">
        <div class="d-flex gap-3 product-carousel" id="featCarousel" style="overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding-bottom:4px;">
          <?php foreach ($featured as $p): ?>
          <div class="flex-shrink-0" style="width:calc(25% - 12px);min-width:200px;max-width:260px;scroll-snap-align:start;">
            <?php include __DIR__ . '/../product/_product_card.php'; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="swiper-btn next" id="featNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §5 — COLLECTION HERO (Dynamic, admin-managed)
══════════════════════════════════════════ -->
<?php foreach ($homeHeroSections ?? [] as $hs):
  $_hsProds = $homeHeroProducts[(int)$hs['id']] ?? [];
  if (empty($_hsProds)) continue;
  $_hsId = (int)$hs['id'];
  $words = explode(' ', $hs['title'], 4);
  $first = implode(' ', array_slice($words, 0, 3));
  $rest  = implode(' ', array_slice($words, 3));
?>
<section class="hs-collection">
  <div class="container" style="position:relative;z-index:1;">
    <div class="hs-col-grid">

      <!-- Left: Promo copy -->
      <div class="hs-col-left" data-aos="fade-right" data-aos-duration="700">
        <?php if (!empty($hs['badge_text'])): ?>
        <div class="hs-col-badge">
          <span class="hs-col-badge-dot"></span>
          <?= e($hs['badge_text']) ?>
        </div>
        <?php endif; ?>

        <h2 class="hs-col-title">
          <?= e($first) ?>
          <?php if ($rest): ?>
          <span><?= e($rest) ?></span>
          <?php endif; ?>
        </h2>

        <?php if (!empty($hs['subtitle'])): ?>
        <p class="hs-col-sub"><?= e($hs['subtitle']) ?></p>
        <?php endif; ?>

        <a href="<?= e(!empty($hs['link']) ? $hs['link'] : url('products')) ?>" class="hs-col-cta">
          Shop Now <i class="fa-solid fa-arrow-right"></i>
        </a>

        <div class="hs-col-trust">
          <div class="hs-col-trust-item"><i class="fa-solid fa-truck-fast"></i> Island-wide Delivery</div>
          <div class="hs-col-trust-item"><i class="fa-solid fa-rotate-left"></i> 7-day Returns</div>
          <div class="hs-col-trust-item"><i class="fa-solid fa-lock"></i> Secure Checkout</div>
        </div>
      </div>

      <!-- Right: Product carousel (standard product cards) -->
      <div class="position-relative product-carousel-wrap" data-aos="fade-left" data-aos-duration="700" data-aos-delay="150">
        <button class="swiper-btn prev" id="hsPrev<?= $_hsId ?>"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="overflow-hidden">
          <div class="d-flex gap-3 product-carousel" id="hsCar<?= $_hsId ?>"
               style="overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding-bottom:4px;">
            <?php foreach ($_hsProds as $p): ?>
            <div class="flex-shrink-0" style="width:calc(33.333% - 9px);min-width:180px;max-width:220px;scroll-snap-align:start;">
              <?php include __DIR__ . '/../product/_product_card.php'; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <button class="swiper-btn next" id="hsNext<?= $_hsId ?>"><i class="fa-solid fa-chevron-right"></i></button>
      </div>

    </div>
  </div>
</section>
<?php endforeach; ?>


<!-- ══════════════════════════════════════════
     §6 — MID BANNERS
══════════════════════════════════════════ -->
<?php if (!empty($midBanners)): ?>
<section style="background:var(--bg);padding:40px 0 48px;">
  <div class="container mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="section-heading mb-0">Featured Offers</h2>
      <a href="<?= url('products') ?>" class="hp-view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>
  <div id="midOuter" style="overflow:hidden;position:relative;">
    <div id="midTrack" style="display:flex;gap:14px;padding-left:16px;will-change:transform;">
      <?php foreach ($midBanners as $mb):
        $mbPs  = bannerPosStyles($mb['desc_position'] ?? 'middle-left');
        $mbClr = ($mb['text_color']??'light')==='dark' ? '#111' : '#fff';
        $mbOvl = ($mb['text_color']??'light')==='dark' ? 'rgba(255,255,255,.28)' : 'rgba(0,0,0,.42)';
      ?>
      <a href="<?= e($mb['link']??url('products')) ?>" class="mid-item text-decoration-none"
         style="flex-shrink:0;height:clamp(200px,24vw,300px);border-radius:14px;overflow:hidden;position:relative;display:block;">
        <img src="<?= url('uploads/banners/'.e($mb['image'])) ?>" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s;" alt="<?= e($mb['title']??'') ?>" loading="lazy">
        <div style="position:absolute;inset:0;background:<?= $mbOvl ?>;display:flex;align-items:<?= $mbPs['align'] ?>;justify-content:<?= $mbPs['justify'] ?>;padding:24px 26px;">
          <div style="text-align:<?= $mbPs['textAlign'] ?>;color:<?= $mbClr ?>;">
            <?php if (!empty($mb['subtitle'])): ?><div style="font-size:11px;opacity:.8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:5px;"><?= e($mb['subtitle']) ?></div><?php endif; ?>
            <?php if (!empty($mb['title'])): ?><div style="font-weight:800;font-size:clamp(15px,2vw,24px);line-height:1.2;"><?= e($mb['title']) ?></div><?php endif; ?>
            <?php if (!empty($mb['description'])): ?><div style="font-size:12.5px;opacity:.85;margin-top:5px;"><?= e($mb['description']) ?></div><?php endif; ?>
            <div style="margin-top:12px;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.4);backdrop-filter:blur(4px);color:#fff;font-size:12px;font-weight:700;padding:6px 16px;border-radius:50px;">
              Shop Now <i class="fa-solid fa-arrow-right"></i>
            </div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <button id="midPrev" style="display:none;position:absolute;top:50%;left:12px;transform:translateY(-50%);width:40px;height:40px;border-radius:50%;border:none;background:rgba(0,0,0,.55);color:#fff;align-items:center;justify-content:center;cursor:pointer;z-index:10;backdrop-filter:blur(4px);"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="midNext" style="display:none;position:absolute;top:50%;right:12px;transform:translateY(-50%);width:40px;height:40px;border-radius:50%;border:none;background:rgba(0,0,0,.55);color:#fff;align-items:center;justify-content:center;cursor:pointer;z-index:10;backdrop-filter:blur(4px);"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §8 — NEW ARRIVALS
══════════════════════════════════════════ -->
<?php if (!empty($newArrivals)): ?>
<section class="py-5" style="background:var(--bg);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
      <h2 class="section-heading mb-0">New Arrivals</h2>
      <a href="<?= url('products?sort=newest') ?>" class="hp-view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="position-relative product-carousel-wrap" data-aos="fade-up" data-aos-delay="80">
      <button class="swiper-btn prev" id="newPrev"><i class="fa-solid fa-chevron-left"></i></button>
      <div class="overflow-hidden">
        <div class="d-flex gap-3 product-carousel" id="newCarousel" style="overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding-bottom:4px;">
          <?php foreach ($newArrivals as $p): ?>
          <div class="flex-shrink-0" style="width:calc(25% - 12px);min-width:200px;max-width:260px;scroll-snap-align:start;">
            <?php include __DIR__ . '/../product/_product_card.php'; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="swiper-btn next" id="newNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §7 — SHOP BY COLLECTION (2×2 Dynamic)
══════════════════════════════════════════ -->
<?php if (!empty($homeCollections)): ?>
<section class="py-5" style="background:var(--bg-soft);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
      <div>
        <div class="hp-sh-label">Collections</div>
        <h2 class="section-heading mb-0">Shop by Collection</h2>
      </div>
    </div>
    <div class="hp-cat-grid" data-aos="fade-up" data-aos-delay="80">
      <?php foreach (array_slice($homeCollections, 0, 4) as $i => $col):
        $catUrl = e($col['link'] ?: url('products'));
        $bgColor = $col['color'] ?? '#2dc653';
        $darkBg  = '#0a1a0d';
      ?>
      <a href="<?= $catUrl ?>" class="hp-cat-card">
        <div class="hp-cat-bg" style="background:<?= $darkBg ?>;">
          <?php if (!empty($col['image'])): ?>
            <img src="<?= url('uploads/home/'.e($col['image'])) ?>" alt="<?= e($col['name']) ?>" loading="lazy" class="hp-cat-img">
          <?php else: ?>
            <div class="hp-cat-icon-bg" style="color:<?= e($bgColor) ?>;font-size:4rem;"><i class="fa-solid fa-layer-group"></i></div>
          <?php endif; ?>
        </div>
        <div class="hp-cat-overlay"></div>
        <div class="hp-cat-body">
          <?php if ($col['tagline']): ?>
          <div class="hp-cat-tag" style="color:<?= e($bgColor) ?>;"><?= e($col['tagline']) ?></div>
          <?php endif; ?>
          <div class="hp-cat-name"><?= e($col['name']) ?></div>
          <div class="hp-cat-btn">Shop Now <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §9 — BEST SELLERS
══════════════════════════════════════════ -->
<?php if (!empty($bestSellers)): ?>
<section class="py-5" style="background:var(--bg-soft);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
      <h2 class="section-heading mb-0">🔥 Best Sellers</h2>
      <a href="<?= url('products?sort=popular') ?>" class="hp-view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-3" data-aos="fade-up" data-aos-delay="80">
      <?php foreach ($bestSellers as $p): ?>
      <div class="col"><?php include __DIR__ . '/../product/_product_card.php'; ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §10 — AI PERSONALISED
══════════════════════════════════════════ -->
<?php if (!empty($personalised)): ?>
<section class="py-5" style="background:var(--bg);">
  <div class="container">
    <div class="d-flex align-items-center gap-3 mb-4" data-aos="fade-up">
      <h2 class="section-heading mb-0">Recommended For You</h2>
      <span class="badge text-white" style="background:var(--brand);font-size:10px;letter-spacing:.06em;">✨ AI</span>
    </div>
    <div class="row row-cols-2 row-cols-md-4 g-2 g-md-3" data-aos="fade-up" data-aos-delay="80">
      <?php foreach ($personalised as $p): ?>
      <div class="col"><?php include __DIR__ . '/../product/_product_card.php'; ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §11 — PROMO BANNERS
══════════════════════════════════════════ -->
<?php if (!empty($promoBanners)): ?>
<section style="background:var(--bg-soft);padding:40px 0 48px;">
  <div class="container mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="section-heading mb-0">Offers &amp; Promotions</h2>
      <a href="<?= url('products') ?>" class="hp-view-all">Browse All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>
  <div id="promoOuter" style="overflow:hidden;position:relative;">
    <div id="promoTrack" style="display:flex;gap:14px;padding-left:16px;will-change:transform;">
      <?php foreach ($promoBanners as $pb):
        $pbPs  = bannerPosStyles($pb['desc_position'] ?? 'middle-left');
        $pbClr = ($pb['text_color']??'light')==='dark' ? '#111' : '#fff';
        $pbOvl = ($pb['text_color']??'light')==='dark' ? 'rgba(255,255,255,.28)' : 'rgba(0,0,0,.42)';
      ?>
      <a href="<?= e($pb['link']??url('products')) ?>" class="promo-item text-decoration-none"
         style="flex-shrink:0;height:clamp(200px,24vw,300px);border-radius:14px;overflow:hidden;position:relative;display:block;">
        <img src="<?= url('uploads/banners/'.e($pb['image'])) ?>" style="width:100%;height:100%;object-fit:cover;display:block;" alt="<?= e($pb['title']??'') ?>" loading="lazy">
        <div style="position:absolute;inset:0;background:<?= $pbOvl ?>;display:flex;align-items:<?= $pbPs['align'] ?>;justify-content:<?= $pbPs['justify'] ?>;padding:24px 26px;">
          <div style="text-align:<?= $pbPs['textAlign'] ?>;color:<?= $pbClr ?>;">
            <?php if (!empty($pb['subtitle'])): ?><div style="font-size:11px;opacity:.8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:5px;"><?= e($pb['subtitle']) ?></div><?php endif; ?>
            <?php if (!empty($pb['title'])): ?><div style="font-weight:800;font-size:clamp(15px,2vw,24px);line-height:1.2;"><?= e($pb['title']) ?></div><?php endif; ?>
            <?php if (!empty($pb['description'])): ?><div style="font-size:12.5px;opacity:.85;margin-top:5px;"><?= e($pb['description']) ?></div><?php endif; ?>
            <div style="margin-top:12px;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.4);backdrop-filter:blur(4px);color:#fff;font-size:12px;font-weight:700;padding:6px 16px;border-radius:50px;">
              Shop Now <i class="fa-solid fa-arrow-right"></i>
            </div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <button id="promoPrev" style="display:none;position:absolute;top:50%;left:12px;transform:translateY(-50%);width:40px;height:40px;border-radius:50%;border:none;background:rgba(0,0,0,.55);color:#fff;align-items:center;justify-content:center;cursor:pointer;z-index:10;backdrop-filter:blur(4px);"><i class="fa-solid fa-chevron-left"></i></button>
    <button id="promoNext" style="display:none;position:absolute;top:50%;right:12px;transform:translateY(-50%);width:40px;height:40px;border-radius:50%;border:none;background:rgba(0,0,0,.55);color:#fff;align-items:center;justify-content:center;cursor:pointer;z-index:10;backdrop-filter:blur(4px);"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §12 — REVIEWS (Dynamic)
══════════════════════════════════════════ -->
<?php if (!empty($homeReviews)):
  $totalRev  = count($homeReviews);
  $avgRating = $totalRev ? round(array_sum(array_column($homeReviews,'rating')) / $totalRev, 1) : 5;
  $fiveStar  = count(array_filter($homeReviews, fn($r) => (int)$r['rating'] >= 4));
  $recoPct   = $totalRev ? round($fiveStar / $totalRev * 100) : 98;
  $starDist  = [5=>0,4=>0,3=>0,2=>0,1=>0];
  foreach ($homeReviews as $r) $starDist[max(1,min(5,(int)$r['rating']))]++;
  $srcIcons  = [
    'google'     => '<svg class="hp-review-src" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>',
    'facebook'   => '<svg class="hp-review-src" viewBox="0 0 24 24" fill="#1877F2" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
    'trustpilot' => '<svg class="hp-review-src" viewBox="0 0 24 24" fill="#00B67A" xmlns="http://www.w3.org/2000/svg"><path d="M12 0L14.7 8.3H24L16.6 13.4L19.4 21.7L12 16.6L4.6 21.7L7.4 13.4L0 8.3H9.3Z"/></svg>',
    'other'      => '',
  ];
?>
<section class="hp-reviews py-5">
  <div class="container">

    <!-- Header -->
    <div class="hp-rev-header" data-aos="fade-up">
      <div>
        <div class="hp-sh-label">Customer Reviews</div>
        <h2 class="section-heading mb-1">What People Say</h2>
        <p style="font-size:14px;color:var(--text-muted);margin:0;">Real reviews from real customers across Sri Lanka</p>
      </div>
      <a href="<?= url('products') ?>" class="hp-rev-see-all">
        Browse Products <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <!-- Stats bar -->
    <div class="hp-rev-stats" data-aos="fade-up" data-aos-delay="60">
      <!-- Score -->
      <div style="text-align:center;padding-right:32px;">
        <div class="hp-rev-stat-score"><?= $avgRating ?></div>
        <div class="hp-rev-stat-stars">
          <?php for ($i=1;$i<=5;$i++): ?>
            <i class="fa-solid fa-star" style="color:<?= $i<=$avgRating?'#f59e0b':'#d1d5db' ?>;"></i>
          <?php endfor; ?>
        </div>
        <div class="hp-rev-stat-label">out of 5</div>
      </div>
      <div class="hp-rev-divider"></div>
      <!-- Bar breakdown -->
      <div class="hp-rev-bars" style="padding:0 32px;">
        <?php foreach ([5,4,3,2,1] as $s): ?>
        <div class="hp-rev-bar-row">
          <span><?= $s ?><i class="fa-solid fa-star" style="color:#f59e0b;font-size:9px;margin-left:2px;"></i></span>
          <div class="hp-rev-bar-track">
            <div class="hp-rev-bar-fill" style="width:<?= $totalRev?round($starDist[$s]/$totalRev*100):0 ?>%"></div>
          </div>
          <span><?= $starDist[$s] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="hp-rev-divider"></div>
      <!-- Numbers -->
      <div style="display:flex;flex-direction:column;gap:20px;padding-left:32px;">
        <div class="hp-rev-stat-num">
          <div class="hp-rev-stat-big"><?= $totalRev ?>+</div>
          <div class="hp-rev-stat-sub">Verified Reviews</div>
        </div>
        <div class="hp-rev-stat-num">
          <div class="hp-rev-stat-big"><?= $recoPct ?>%</div>
          <div class="hp-rev-stat-sub">Would Recommend</div>
        </div>
      </div>
    </div>

    <!-- Cards -->
    <div class="hp-rev-carousel-wrap" data-aos="fade-up" data-aos-delay="120">
      <div class="rev-track" id="revTrack">
        <?php foreach ($homeReviews as $rv):
          $initial = strtoupper(substr($rv['name'], 0, 1));
          $rating  = max(1, min(5, (int)$rv['rating']));
          $srcIcon = $srcIcons[$rv['source'] ?? 'other'] ?? '';
        ?>
        <div class="hp-review-card">
          <div class="hp-review-stars">
            <?php for ($i=1;$i<=5;$i++): ?>
              <i class="fa-<?= $i<=$rating?'solid':'regular' ?> fa-star"></i>
            <?php endfor; ?>
          </div>
          <span class="hp-review-quote">&ldquo;</span>
          <p class="hp-review-text"><?= e($rv['review']) ?></p>
          <div class="hp-review-foot">
            <div class="hp-review-user">
              <div class="hp-review-avatar"><?= $initial ?></div>
              <div class="hp-review-info">
                <div class="hp-review-name"><?= e($rv['name']) ?></div>
                <div class="hp-review-meta">
                  <span class="hp-review-ago"><?= date('M j, Y', strtotime($rv['created_at'])) ?></span>
                  <span class="hp-verified"><i class="fa-solid fa-circle-check"></i> Verified</span>
                </div>
              </div>
            </div>
            <?= $srcIcon ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Navigation -->
    <div class="hp-rev-nav">
      <button class="hp-rev-nav-btn" id="revPrev" disabled><i class="fa-solid fa-chevron-left"></i></button>
      <div class="hp-rev-dots" id="revDots"></div>
      <button class="hp-rev-nav-btn" id="revNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

  </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     §13 — WHY CHOOSE US
══════════════════════════════════════════ -->
<section class="py-5" style="background:var(--bg);">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="section-heading">Why Anything.lk?</h2>
    </div>
    <div class="row g-4" data-aos="fade-up" data-aos-delay="80">
      <?php foreach ([
        ['fa-map-marker-alt','Island-Wide Delivery','We deliver to all 25 districts across Sri Lanka.'],
        ['fa-shield-halved', '100% Authentic Products','All products are verified and quality checked.'],
        ['fa-tag',           'Best Price Guarantee','We match or beat any competitor price.'],
        ['fa-headset',       '24/7 Customer Support','Our team is always here to help you.'],
      ] as $r): ?>
      <div class="col-md-6 col-lg-3">
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;text-align:center;height:100%;transition:var(--transition);"
             onmouseover="this.style.borderColor='var(--brand)';this.style.boxShadow='var(--shadow-md)'"
             onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">
          <div style="width:58px;height:58px;background:var(--brand-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:22px;color:var(--brand);">
            <i class="fa-solid <?= $r[0] ?>"></i>
          </div>
          <h6 style="font-weight:700;margin-bottom:8px;color:var(--text);font-size:14px;"><?= $r[1] ?></h6>
          <p style="color:var(--text-muted);font-size:13px;margin:0;line-height:1.65;"><?= $r[2] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<?php
// Build carousel setup calls for dynamic hero sections
$_hsCarJs = '';
foreach ($homeHeroSections ?? [] as $_hs) {
    if (!empty($homeHeroProducts[(int)$_hs['id']])) {
        $i = (int)$_hs['id'];
        $_hsCarJs .= "setupCarousel('hsCar{$i}','hsPrev{$i}','hsNext{$i}');";
    }
}

$extraScript = <<<'JSEOF'
<script>
// Product carousels
function setupCarousel(id, prevId, nextId) {
  var el = document.getElementById(id);
  var prev = document.getElementById(prevId);
  var next = document.getElementById(nextId);
  if (!el) return;
  var step = function() { return (el.querySelector(':first-child')?.offsetWidth||220)+12; };
  prev?.addEventListener('click', function(){ el.scrollBy({left:-step(),behavior:'smooth'}); });
  next?.addEventListener('click', function(){ el.scrollBy({left: step(),behavior:'smooth'}); });
}
setupCarousel('featCarousel','featPrev','featNext');
setupCarousel('newCarousel', 'newPrev', 'newNext');
// Reviews carousel — drag + dots + edge-aware buttons
(function() {
  var track  = document.getElementById('revTrack');
  var prev   = document.getElementById('revPrev');
  var next   = document.getElementById('revNext');
  var dotsEl = document.getElementById('revDots');
  if (!track) return;

  function itemW() {
    var item = track.querySelector('.hp-review-card');
    return item ? item.offsetWidth + 16 : 300;
  }
  function visible() { return Math.max(1, Math.round(track.offsetWidth / itemW())); }
  function total()   { return track.querySelectorAll('.hp-review-card').length; }

  function buildDots() {
    if (!dotsEl) return;
    var pages = Math.ceil(total() / visible());
    if (pages <= 1) { dotsEl.innerHTML = ''; return; }
    dotsEl.innerHTML = '';
    for (var i = 0; i < pages; i++) {
      var d = document.createElement('button');
      d.className = 'hp-rev-dot' + (i === 0 ? ' active' : '');
      d.dataset.page = i;
      d.setAttribute('aria-label', 'Page ' + (i+1));
      d.addEventListener('click', function() {
        track.style.scrollBehavior = 'smooth';
        track.scrollLeft = +this.dataset.page * visible() * itemW();
      });
      dotsEl.appendChild(d);
    }
  }

  function sync() {
    var atStart = track.scrollLeft <= 4;
    var atEnd   = track.scrollLeft >= track.scrollWidth - track.offsetWidth - 4;
    if (prev) prev.disabled = atStart;
    if (next) next.disabled = atEnd;
    if (dotsEl) {
      var page = Math.round(track.scrollLeft / (visible() * itemW()));
      dotsEl.querySelectorAll('.hp-rev-dot').forEach(function(d, i) {
        d.classList.toggle('active', i === page);
      });
    }
  }

  buildDots(); sync();
  track.addEventListener('scroll', sync, { passive: true });
  window.addEventListener('resize', function() { buildDots(); sync(); });

  if (prev) prev.addEventListener('click', function() {
    track.style.scrollBehavior = 'smooth';
    track.scrollLeft -= visible() * itemW();
  });
  if (next) next.addEventListener('click', function() {
    track.style.scrollBehavior = 'smooth';
    track.scrollLeft += visible() * itemW();
  });

  // Drag to scroll
  var isDrag = false, startX, startScroll;
  track.addEventListener('mousedown', function(e) {
    isDrag = true; startX = e.pageX; startScroll = track.scrollLeft;
    track.style.scrollBehavior = 'auto';
    track.classList.add('dragging');
  });
  document.addEventListener('mousemove', function(e) {
    if (!isDrag) return;
    track.scrollLeft = startScroll - (e.pageX - startX);
  });
  document.addEventListener('mouseup', function() {
    if (!isDrag) return;
    isDrag = false;
    track.classList.remove('dragging');
    track.style.scrollBehavior = 'smooth';
  });
})();

// Banner carousels (3-up peek)
function makeBannerCarousel(outerId, trackId, itemClass, prevId, nextId) {
  var outer = document.getElementById(outerId);
  if (!outer) return;
  var track = document.getElementById(trackId);
  var items = Array.from(track.querySelectorAll('.'+itemClass));
  if (!items.length) return;
  var gap=14, pad=16, peek=52, cur=0;
  var pBtn = document.getElementById(prevId);
  var nBtn = document.getElementById(nextId);
  function vis() { return window.innerWidth>=992?3:window.innerWidth>=576?2:1; }
  function render(anim) {
    var v=vis(), w=(outer.offsetWidth-pad-(v-1)*gap-(items.length>v?peek:0))/v;
    items.forEach(function(el){ el.style.width=w+'px'; });
    var max=Math.max(0,items.length-v); if(cur>max)cur=max;
    track.style.transition=anim?'transform .4s cubic-bezier(.4,0,.2,1)':'none';
    track.style.transform=cur===0?'':'translateX('+(-(cur*(w+gap)))+'px)';
    if(pBtn)pBtn.style.display=cur>0?'flex':'none';
    if(nBtn)nBtn.style.display=cur<max?'flex':'none';
  }
  pBtn?.addEventListener('click',function(){cur=Math.max(0,cur-vis());render(true);});
  nBtn?.addEventListener('click',function(){cur=Math.min(Math.max(0,items.length-vis()),cur+vis());render(true);});
  render(false);
  var raf;
  window.addEventListener('resize',function(){cancelAnimationFrame(raf);raf=requestAnimationFrame(function(){cur=0;render(false);});});
}
makeBannerCarousel('midOuter','midTrack','mid-item','midPrev','midNext');
makeBannerCarousel('promoOuter','promoTrack','promo-item','promoPrev','promoNext');

// Category mobile drag
var catEl=document.getElementById('catCarousel');
if(catEl){var sx,drag=false;catEl.addEventListener('mousedown',function(e){sx=e.pageX;drag=true;});catEl.addEventListener('mousemove',function(e){if(drag){catEl.scrollLeft-=(e.pageX-sx);sx=e.pageX;}});catEl.addEventListener('mouseup',function(){drag=false;});}
</script>
JSEOF;
// Hero section carousels (dynamic IDs)
if ($_hsCarJs) $extraScript .= "<script>{$_hsCarJs}</script>";
?>
