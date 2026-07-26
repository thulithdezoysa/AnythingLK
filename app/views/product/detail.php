<?php
$pageTitle = e($product['name']);

// Pre-compute pricing
$hasDiscount = !empty($product['sale_price']) && (float)$product['sale_price'] < (float)$product['price'];
$effPrice    = $hasDiscount ? (float)$product['sale_price'] : (float)$product['price'];
$discPct     = $hasDiscount ? round(((float)$product['price'] - (float)$product['sale_price']) / (float)$product['price'] * 100) : 0;
$stockQty    = (int)($product['stock']['quantity'] ?? 0);
$inStock     = !$product['track_stock'] || $stockQty > 0;
$lowStock    = $product['track_stock'] && $stockQty > 0 && $stockQty <= 10;
$pid         = (int)$product['id'];

// Image list
$allImages = [];
if (!empty($product['thumbnail'])) $allImages[] = $product['thumbnail'];
foreach (($product['images'] ?? []) as $img) {
    if (!empty($img['image']) && $img['image'] !== $product['thumbnail']) $allImages[] = $img['image'];
}
$mainSrc = $allImages ? url('uploads/products/'.e($allImages[0])) : url('assets/img/placeholder.webp');

// Payment method icon map
$payIconMap = [
    'cod'        => ['icon'=>'fa-truck-fast',       'color'=>'#16a34a', 'bg'=>'#dcfce7', 'label'=>'Cash on Delivery'],
    'koko'       => ['icon'=>'fa-layer-group',       'color'=>'#ea580c', 'bg'=>'#ffedd5', 'label'=>'KOKO'],
    'payzy'      => ['icon'=>'fa-shield-halved',     'color'=>'#2563eb', 'bg'=>'#dbeafe', 'label'=>'Payzy'],
    'mintpay'    => ['icon'=>'fa-calendar-check',    'color'=>'#0d9488', 'bg'=>'#ccfbf1', 'label'=>'MintPay'],
    'visa'       => ['icon'=>'fa-cc-visa',           'color'=>'#1a1f71', 'bg'=>'#e0e7ff', 'label'=>'Visa'],
    'mastercard' => ['icon'=>'fa-cc-mastercard',     'color'=>'#eb001b', 'bg'=>'#fee2e2', 'label'=>'Mastercard'],
    'card'       => ['icon'=>'fa-credit-card',       'color'=>'#6d28d9', 'bg'=>'#ede9fe', 'label'=>'Card'],
    'bank'       => ['icon'=>'fa-building-columns',  'color'=>'#374151', 'bg'=>'#f3f4f6', 'label'=>'Bank Transfer'],
];
?>

<!-- Breadcrumb -->
<div class="pd-breadcrumb-bar" style="background:var(--bg-card);border-bottom:1px solid var(--border);">
  <div class="container">
    <nav>
      <ol class="breadcrumb mb-0 small" style="margin-bottom:0;">

        <li class="breadcrumb-item">
          <a href="<?= url('') ?>" style="color:var(--text-muted);text-decoration:none;">
            Home
          </a>
        </li>

        <?php foreach ($categoryPath ?? [] as $crumb): ?>
        <li class="breadcrumb-item">
          <a href="<?= url('category/'.e($crumb['slug'])) ?>"
             style="color:var(--text-muted);text-decoration:none;">
            <?= e($crumb['name']) ?>
          </a>
        </li>
        <?php endforeach; ?>

        <li class="breadcrumb-item active text-truncate"
            style="max-width:220px;color:var(--text);font-weight:500;">
          <?= e(Helper::truncate($product['name'], 45)) ?>
        </li>

      </ol>
    </nav>
  </div>
</div>

<div class="container py-4">
<div class="row g-4 g-lg-5">

  <!-- ══════════════════════════════════
       IMAGES COLUMN
  ══════════════════════════════════ -->
  <div class="col-12 col-md-5">
    <div class="pd-gallery-sticky">

      <!-- Main image -->
      <div class="pd-img-main" id="pdImgWrap">
        <img id="mainProductImage" src="<?= $mainSrc ?>" alt="<?= e($product['name']) ?>">
        <?php if ($hasDiscount): ?>
        <div class="pd-img-disc-badge">-<?= $discPct ?>%</div>
        <?php endif; ?>
        <?php if (!$inStock): ?>
        <div class="pd-img-oos-overlay"><span>Out of Stock</span></div>
        <?php endif; ?>
        <button class="pd-zoom-btn" id="pdZoomBtn" type="button" title="Zoom">
          <i class="fas fa-expand-alt"></i>
        </button>
      </div>

      <!-- Thumbnails -->
      <?php if (count($allImages) > 1): ?>
      <div class="pd-thumbs mt-2">
        <?php foreach ($allImages as $i => $img): ?>
        <button type="button"
                class="pd-thumb <?= $i === 0 ? 'active' : '' ?>"
                onclick="pdSetImg(this,'<?= url('uploads/products/'.e($img)) ?>')">
          <img src="<?= url('uploads/products/'.e($img)) ?>" alt="">
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Share row -->
      <div class="pd-share-row mt-3">
        <span class="small" style="color:var(--text-muted);">Share:</span>
        <a href="https://wa.me/?text=<?= rawurlencode($product['name'].' — '.url('product/'.e($product['slug']))) ?>"
           target="_blank" rel="noopener" class="pd-share-btn whatsapp" title="WhatsApp">
          <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode(url('product/'.e($product['slug']))) ?>"
           target="_blank" rel="noopener" class="pd-share-btn facebook" title="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <button type="button" class="pd-share-btn copy-link" title="Copy link" id="pdCopyLink">
          <i class="fas fa-link"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- ══════════════════════════════════
       PRODUCT INFO COLUMN
  ══════════════════════════════════ -->
  <div class="col-12 col-md-7">

    <!-- Category + badges row -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
      <?php if (!empty($product['category_name'])): ?>
      <a href="<?= url('category/'.e($product['category_slug'])) ?>" class="pd-cat-tag">
        <?= e($product['category_name']) ?>
      </a>
      <?php endif; ?>
      <?php if (!empty($product['is_new'])): ?>
      <span class="pd-badge-new">NEW</span>
      <?php endif; ?>
      <?php if ($hasDiscount): ?>
      <span class="pd-badge-sale">-<?= $discPct ?>% OFF</span>
      <?php endif; ?>
    </div>

    <!-- Product name -->
    <h1 class="pd-name"><?= e($product['name']) ?></h1>

    <!-- Rating + brand + SKU -->
    <div class="pd-meta-row">
      <?php $avgR = (float)($product['avg_rating']['avg'] ?? 0); $totalR = (int)($product['avg_rating']['total'] ?? 0); ?>
      <?php if ($totalR > 0): ?>
      <div class="pd-rating">
        <?= stars($avgR) ?>
        <a href="#tabReviews" class="pd-rating-link" data-bs-toggle="tab" data-bs-target="#tabReviews">
          <?= number_format($avgR, 1) ?> (<?= $totalR ?> review<?= $totalR !== 1 ? 's' : '' ?>)
        </a>
      </div>
      <?php else: ?>
      <span class="text-muted small">No reviews yet</span>
      <?php endif; ?>
      <?php if (!empty($product['brand_name'])): ?>
      <span class="pd-meta-sep">•</span>
      <a href="<?= url('brand/'.e($product['brand_slug'])) ?>" class="pd-brand-link">
        <?= e($product['brand_name']) ?>
      </a>
      <?php endif; ?>
      <?php if (!empty($product['sku'])): ?>
      <span class="pd-meta-sep">•</span>
      <span class="small" style="color:var(--text-muted);">SKU: <?= e($product['sku']) ?></span>
      <?php endif; ?>
    </div>

    <!-- Price -->
    <div class="pd-price-block">
      <span class="pd-price-main" id="productEffPrice">LKR <?= number_format($effPrice, 2) ?></span>
      <?php if ($hasDiscount): ?>
      <span class="pd-price-old" id="productOldPrice">LKR <?= number_format((float)$product['price'], 2) ?></span>
      <span class="pd-price-save">Save LKR <?= number_format((float)$product['price'] - $effPrice, 2) ?></span>
      <?php endif; ?>
    </div>

    <!-- ── Discount alerts ── -->
    <?php if (!empty($productDiscount)): ?>
    <?php $dv = $productDiscount; ?>
    <div class="pd-discount-alert product-disc">
      <i class="fas fa-tag"></i>
      <div>
        <strong>
          <?= $dv['type'] === 'percentage' ? $dv['value'].'% OFF' : 'LKR '.number_format($dv['value'],2).' OFF' ?>
          <?= $dv['name'] ? '— '.e($dv['name']) : 'on this product' ?>
        </strong>
        <span>Applied automatically at checkout</span>
      </div>
    </div>
    <?php endif; ?>

    <?php foreach ($globalDiscounts as $gd): ?>
    <?php if ((float)$gd['min_order'] <= $effPrice || (float)$gd['min_order'] === 0.0): ?>
    <div class="pd-discount-alert global-disc">
      <i class="fas fa-percent"></i>
      <div>
        <strong>
          <?= $gd['type'] === 'percentage' ? $gd['value'].'% OFF' : 'LKR '.number_format($gd['value'],2).' OFF' ?>
          — <?= e($gd['name']) ?>
        </strong>
        <?php if ((float)$gd['min_order'] > 0): ?>
        <span>On orders over LKR <?= number_format($gd['min_order'], 0) ?></span>
        <?php else: ?>
        <span>On all orders — auto-applied</span>
        <?php endif; ?>
      </div>
    </div>
    <?php break; ?>
    <?php endif; ?>
    <?php endforeach; ?>

    <!-- Short description -->
    <?php if (!empty($product['short_desc'])): ?>
    <p class="pd-short-desc"><?= nl2br(e($product['short_desc'])) ?></p>
    <?php endif; ?>

    <!-- Stock status -->
    <div class="pd-stock-row">
      <?php if ($inStock): ?>
        <span class="pd-stock-badge in-stock">
          <i class="fas fa-circle-check"></i>
          <?php if ($lowStock): ?>Only <?= $stockQty ?> left!
          <?php else: ?>In Stock<?php endif; ?>
        </span>
      <?php else: ?>
        <span class="pd-stock-badge out-stock">
          <i class="fas fa-circle-xmark"></i> Out of Stock
        </span>
      <?php endif; ?>
      <?php if ($product['track_stock'] && $inStock && !$lowStock): ?>
      <span class="small" style="color:var(--text-muted);">
  <?= $stockQty ?> available
</span>
      <?php endif; ?>
    </div>

    <!-- Variations -->
    <?php if (!empty($product['variations'])): ?>
    <div class="pd-variations">
      <label class="pd-field-label"><?= __('product.select_var') ?></label>
      <div class="d-flex flex-wrap gap-2" id="variationGroup">
        <?php foreach ($product['variations'] as $var): ?>
        <button type="button" class="pd-var-btn variation-btn"
                data-vid="<?= $var['id'] ?>"
                data-price="<?= $var['price'] ?? $product['price'] ?>"
                data-sale="<?= $var['sale_price'] ?? ($product['sale_price'] ?? '') ?>">
          <?= e($var['name']) ?>
        </button>
        <?php endforeach; ?>
      </div>
      <input type="hidden" id="selectedVariation" value="">
    </div>
    <?php endif; ?>

    <!-- Qty + Add to Cart + Wishlist -->
    <div class="pd-atc-row">
      <div class="pd-qty-wrap">
        <button type="button" id="qtyMinus">−</button>
        <input type="number" id="qty-<?= $pid ?>" value="1" min="1" max="<?= $inStock ? ($stockQty ?: 99) : 1 ?>">
        <button type="button" id="qtyPlus">+</button>
      </div>
      <button class="pd-cart-btn btn-add-cart"
              id="addToCartBtn"
              data-product-id="<?= $pid ?>"
              <?= $inStock ? '' : 'disabled' ?>>
        <?php if ($inStock): ?>
        <i class="fas fa-shopping-bag"></i><?= __('product.add_cart') ?>
        <?php else: ?>
        <i class="fas fa-bell"></i>Notify Me
        <?php endif; ?>
      </button>
      <button class="pd-wish-btn wishlist-btn" data-product-id="<?= $pid ?>" title="Save to wishlist">
        <i class="far fa-heart"></i>
      </button>
    </div>

    <!-- Payment methods -->
    <?php if (!empty($paymentMethods)): ?>
    <div class="pd-pay-section">
      <div class="pd-field-label mb-2">Accepted payments</div>
      <?php $paymentSize = 'md'; $paymentLabel = false;
            include dirname(__DIR__) . '/components/payment_logos.php'; ?>
    </div>
    <?php endif; ?>

    <!-- Trust badges -->
    <div class="pd-trust-strip">
      <div class="pd-trust-item">
        <i class="fas fa-shield-halved" style="color:#2563eb;"></i>
        <span>Secure Payment</span>
      </div>
      <div class="pd-trust-item">
        <i class="fas fa-truck-fast" style="color:#16a34a;"></i>
        <span>Fast Delivery</span>
      </div>
      <div class="pd-trust-item">
        <i class="fas fa-rotate-left" style="color:#ea580c;"></i>
        <span>Easy Returns</span>
      </div>
      <div class="pd-trust-item">
        <i class="fas fa-headset" style="color:#7c3aed;"></i>
        <span>24/7 Support</span>
      </div>
    </div>

    <!-- Attributes -->
    <?php if (!empty($product['attributes'])): ?>
    <div class="pd-attrs">
      <?php foreach ($product['attributes'] as $attr): ?>
      <span class="pd-attr-chip">
        <span class="pd-attr-name"><?= e($attr['attr_name']) ?></span>
        <?php if ($attr['type'] === 'color' && $attr['color_hex']): ?>
        <span class="pd-attr-swatch" style="background:<?= e($attr['color_hex']) ?>;"></span>
        <?php endif; ?>
        <?= e($attr['value']) ?>
      </span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Meta footer -->
    <div class="pd-meta-footer">
      <?php if (!empty($product['category_name'])): ?>
      <div><span class="pd-meta-key">Category:</span>
        <a href="<?= url('category/'.e($product['category_slug'])) ?>"><?= e($product['category_name']) ?></a>
      </div>
      <?php endif; ?>
      <?php if (!empty($product['vendor_name'])): ?>
      <div><span class="pd-meta-key">Sold by:</span> <strong><?= e($product['vendor_name']) ?></strong></div>
      <?php endif; ?>
      <?php if (!empty($product['tags'])): ?>
      <div><span class="pd-meta-key">Tags:</span> <?= e($product['tags']) ?></div>
      <?php endif; ?>
    </div>
  </div><!-- /info col -->
</div><!-- /row -->

<!-- ══════════════════════════════════
     FREQUENTLY BOUGHT TOGETHER
══════════════════════════════════ -->
<?php if (!empty($fbt)): ?>
<section class="pd-fbt-section mt-5">
  <div class="pd-fbt-header">
    <i class="fas fa-bag-shopping"></i>
    Frequently Bought Together
  </div>
  <div class="pd-fbt-items">
    <?php foreach ($fbt as $i => $fp): ?>
    <?php if ($i > 0): ?><div class="pd-fbt-plus">+</div><?php endif; ?>
    <a href="<?= url('product/'.e($fp['slug'])) ?>" class="pd-fbt-item" title="<?= e($fp['name']) ?>">
      <img src="<?= !empty($fp['thumbnail']) ? url('uploads/products/'.e($fp['thumbnail'])) : url('assets/img/placeholder.webp') ?>" alt="">
      <div class="pd-fbt-item-name"><?= e(Helper::truncate($fp['name'], 22)) ?></div>
      <div class="pd-fbt-item-price">LKR <?= number_format((float)($fp['sale_price'] ?: $fp['price']), 0) ?></div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════
     DESCRIPTION + REVIEWS TABS
══════════════════════════════════ -->
<div class="pd-tabs-section mt-5">
  <ul class="nav nav-tabs" id="productTabs" role="tablist">
    <li class="nav-item">
      <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tabDesc" type="button">
        <i class="fas fa-align-left me-1"></i><?= __('product.description') ?>
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tabReviews" type="button" id="reviewsTabBtn">
        <i class="fas fa-star me-1"></i><?= __('product.reviews') ?>
        <?php if ((int)($product['avg_rating']['total'] ?? 0) > 0): ?>
        <span class="badge ms-1" style="background:var(--brand);font-size:.7rem;"><?= (int)$product['avg_rating']['total'] ?></span>
        <?php endif; ?>
      </button>
    </li>
  </ul>
  <div class="tab-content pd-tab-body">

    <!-- Description tab -->
    <div class="tab-pane fade show active" id="tabDesc">
      <?php if (!empty($product['description'])): ?>
        <div class="pd-desc-content"><?= $product['description'] ?></div>
      <?php else: ?>
        <p class="text-muted">No description available.</p>
      <?php endif; ?>
    </div>

    <!-- Reviews tab -->
    <div class="tab-pane fade" id="tabReviews">
      <?php
      $ratingBreakdown = array_fill(1, 5, 0);
      foreach ($reviews as $rv) { $r = max(1, min(5, (int)$rv['rating'])); $ratingBreakdown[$r]++; }
      $totalRev  = (int)($product['avg_rating']['total'] ?? 0);
      $avgRating = (float)($product['avg_rating']['avg'] ?? 0);
      ?>
      <div class="row g-4">
        <div class="col-12 col-md-3">
          <div class="pd-rating-summary">
            <div class="pd-rating-big"><?= number_format($avgRating, 1) ?></div>
            <div class="my-2"><?= stars($avgRating) ?></div>
            <div class="text-muted small"><?= $totalRev ?> review<?= $totalRev !== 1 ? 's' : '' ?></div>
          </div>
          <div class="pd-rating-bars mt-3">
            <?php for ($s = 5; $s >= 1; $s--): ?>
            <?php $cnt = $ratingBreakdown[$s]; $pct = $totalRev > 0 ? round($cnt / $totalRev * 100) : 0; ?>
            <div class="pd-rating-bar-row">
              <span><?= $s ?></span>
              <i class="fas fa-star" style="color:#ffb83c;font-size:10px;"></i>
              <div class="pd-rating-bar-track">
                <div class="pd-rating-bar-fill" style="width:<?= $pct ?>%"></div>
              </div>
              <span class="pd-rating-bar-count"><?= $cnt ?></span>
            </div>
            <?php endfor; ?>
          </div>
        </div>
        <div class="col-12 col-md-9">
          <?php if ($product['allow_review'] && Auth::check()): ?>
          <div class="pd-review-form-wrap">
            <h6 class="fw-bold mb-3"><?= __('product.write_review') ?></h6>
            <form id="reviewForm">
              <?= CSRF::field() ?>
              <input type="hidden" name="product_id" value="<?= $pid ?>">
              <div class="mb-3">
                <label class="pd-field-label mb-1">Your Rating</label>
                <div class="pd-star-picker">
                  <?php for ($i = 5; $i >= 1; $i--): ?>
                  <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" class="d-none">
                  <label for="star<?= $i ?>" class="fas fa-star pd-star-label" title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"></label>
                  <?php endfor; ?>
                </div>
              </div>
              <input type="text" name="title" class="form-control form-control-sm mb-2" placeholder="Review title (optional)">
              <textarea name="body" class="form-control form-control-sm mb-3" rows="3" placeholder="Share your experience..." required></textarea>
              <button type="submit" class="btn btn-primary btn-sm px-4">Submit Review</button>
            </form>
          </div>
          <?php elseif (!Auth::check()): ?>
          <div class="alert alert-light border small mb-4">
            <a href="<?= url('login') ?>" style="color:var(--primary);">Sign in</a> to write a review.
          </div>
          <?php endif; ?>

          <div class="pd-reviews-list">
            <?php foreach ($reviews as $rv): ?>
            <div class="pd-review-item">
              <div class="rv-avatar"><?= strtoupper(substr($rv['full_name'], 0, 1)) ?></div>
              <div class="pd-review-body">
                <div class="pd-review-header">
                  <strong><?= e($rv['full_name']) ?></strong>
                  <span class="text-muted small"><?= Helper::timeAgo($rv['created_at']) ?></span>
                </div>
                <div class="mb-1"><?= stars($rv['rating']) ?></div>
                <?php if ($rv['title']): ?><div class="fw-semibold small mb-1"><?= e($rv['title']) ?></div><?php endif; ?>
                <p class="small mb-0 text-secondary"><?= e($rv['body']) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($reviews)): ?>
            <p class="text-muted small py-3">No reviews yet. Be the first!</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div><!-- /tabReviews -->
  </div>
</div>

<!-- ══════════════════════════════════
     RELATED PRODUCTS CAROUSEL
══════════════════════════════════ -->
<?php if (!empty($related)): ?>
<section class="pd-section pd-rel-section">
  <div class="pd-section-header">
    <div>
      <h4 class="pd-section-title"><?= __('product.related') ?></h4>
      <p class="pd-section-sub">More from this category</p>
    </div>
    <div class="pd-car-controls">
      <button class="pd-car-btn" id="relPrev" disabled><i class="fas fa-chevron-left"></i></button>
      <button class="pd-car-btn" id="relNext"><i class="fas fa-chevron-right"></i></button>
      <?php if (isset($cat)): ?>
      <a href="<?= url('category/'.e($cat['slug'])) ?>" class="pd-see-all">See all <i class="fas fa-arrow-right"></i></a>
      <?php endif; ?>
    </div>
  </div>
  <div class="pd-carousel-wrap" id="relWrap">
    <div class="pd-carousel" id="relCarousel">
      <?php foreach ($related as $p): ?>
      <div class="pd-carousel-item">
        <?php include __DIR__ . '/_product_card.php'; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="pd-car-dots" id="relDots"></div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════
     YOU MAY ALSO LIKE
══════════════════════════════════ -->
<?php if (!empty($youMayLike)): ?>
<section class="pd-section pd-rel-section mb-4">
  <div class="pd-section-header">
    <div>
      <h4 class="pd-section-title">You May Also Like</h4>
      <p class="pd-section-sub">Picked for you</p>
    </div>
    <div class="pd-car-controls">
      <button class="pd-car-btn" id="ymPrev" disabled><i class="fas fa-chevron-left"></i></button>
      <button class="pd-car-btn" id="ymNext"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
  <div class="pd-carousel-wrap" id="ymWrap">
    <div class="pd-carousel" id="ymCarousel">
      <?php foreach ($youMayLike as $p): ?>
      <div class="pd-carousel-item">
        <?php include __DIR__ . '/_product_card.php'; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="pd-car-dots" id="ymDots"></div>
</section>
<?php endif; ?>

</div><!-- /container -->

<!-- Lightbox -->
<div id="pdLightbox">
  <button id="pdLbClose" title="Close"><i class="fas fa-times"></i></button>
  <button id="pdLbPrev" title="Previous"><i class="fas fa-chevron-left"></i></button>
  <img id="pdLbImg" src="" alt="Zoom">
  <button id="pdLbNext" title="Next"><i class="fas fa-chevron-right"></i></button>
</div>

<!-- ══════════════════════════════════
     PAGE STYLES
══════════════════════════════════ -->
<style>
/* ── Breadcrumb bar ── */
.pd-breadcrumb-bar {
  background: var(--bg-soft);
  border-bottom: 1px solid var(--border);
  padding: 10px 0;
}
.pd-breadcrumb-bar .breadcrumb { font-size: 12.5px; }
.pd-breadcrumb-bar .breadcrumb-item a { color: var(--text-muted); text-decoration: none; }
.pd-breadcrumb-bar .breadcrumb-item a:hover { color: var(--brand); }

/* ── Sticky gallery ── */
.pd-gallery-sticky { position: sticky; top: calc(var(--header-h) + 16px); }

/* ── Main image ── */
.pd-img-main {
  position: relative; overflow: hidden;
  background: var(--bg-soft);
  border: 1px solid var(--border);
  border-radius: 18px;
  aspect-ratio: 1 / 1;
  cursor: zoom-in;
}
.pd-img-main img {
  width: 100%; height: 100%;
  object-fit: contain;
  transition: transform .5s cubic-bezier(.4,0,.2,1);
  display: block;
}
.pd-img-main:hover img { transform: scale(1.04); }

.pd-img-disc-badge {
  position: absolute; top: 14px; left: 14px;
  background: var(--grad-brand); color: #fff;
  font-size: 12px; font-weight: 700;
  padding: 4px 10px; border-radius: 8px;
  pointer-events: none; z-index: 2;
}
.pd-img-oos-overlay {
  position: absolute; inset: 0; z-index: 3;
  background: rgba(0,0,0,.45);
  display: flex; align-items: center; justify-content: center;
  border-radius: 18px;
}
.pd-img-oos-overlay span {
  background: #fff; color: #e63946;
  font-weight: 700; padding: 8px 20px;
  border-radius: 8px; font-size: 15px;
}
.pd-zoom-btn {
  position: absolute; bottom: 12px; right: 12px;
  width: 36px; height: 36px; border-radius: 50%;
  background: rgba(0,0,0,.5); color: #fff; border: none;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: 13px; z-index: 2;
  opacity: 0; transition: opacity .2s;
}
.pd-img-main:hover .pd-zoom-btn { opacity: 1; }

/* ── Thumbnails ── */
.pd-thumbs { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-thumb {
  width: 68px; height: 68px; padding: 0; flex-shrink: 0;
  border-radius: 10px; overflow: hidden;
  border: 2px solid var(--border); cursor: pointer;
  background: none; transition: var(--transition);
}
.pd-thumb:hover, .pd-thumb.active {
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(230,57,70,.15);
}
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* ── Share row ── */
.pd-share-row { display: flex; align-items: center; gap: 8px; }
.pd-share-btn {
  width: 34px; height: 34px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; border: 1px solid var(--border);
  background: var(--bg-card); color: var(--text-muted);
  cursor: pointer; text-decoration: none; transition: var(--transition);
}
.pd-share-btn.whatsapp:hover { background: #25d366; color: #fff; border-color: #25d366; }
.pd-share-btn.facebook:hover { background: #1877f2; color: #fff; border-color: #1877f2; }
.pd-share-btn.copy-link:hover { background: var(--brand); color: #fff; border-color: var(--brand); }

/* ── Category tag + badges ── */
.pd-cat-tag {
  display: inline-block; font-size: 11px; font-weight: 600;
  padding: 3px 10px; border-radius: 6px;
  background: var(--bg-soft); color: var(--text-muted);
  border: 1px solid var(--border); text-decoration: none;
  text-transform: uppercase; letter-spacing: .04em;
  transition: var(--transition);
}
.pd-cat-tag:hover { background: var(--brand-light); color: var(--brand); border-color: var(--brand); }
.pd-badge-new {
  display: inline-block; font-size: 11px; font-weight: 700;
  padding: 3px 9px; border-radius: 6px;
  background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;
}
.pd-badge-sale {
  display: inline-block; font-size: 11px; font-weight: 700;
  padding: 3px 9px; border-radius: 6px;
  background: var(--brand-light); color: var(--brand); border: 1px solid rgba(230,57,70,.2);
}

/* ── Product name ── */
.pd-name {
  font-size: clamp(1.35rem, 3vw, 1.75rem);
  font-weight: 800; line-height: 1.25;
  font-family: 'Outfit', sans-serif;
  color: var(--text); margin-bottom: 10px;
}

/* ── Meta row ── */
.pd-meta-row {
  display: flex; flex-wrap: wrap; align-items: center;
  gap: 6px; margin-bottom: 14px;
}
.pd-rating { display: flex; align-items: center; gap: 5px; }
.pd-rating-link { font-size: 12.5px; color: var(--text-muted); text-decoration: none; }
.pd-rating-link:hover { color: var(--brand); }
.pd-meta-sep {
  color: var(--text-muted);
  font-size: 14px;
  opacity: 0.7;
}
.pd-brand-link { font-size: 13px; font-weight: 600; color: var(--brand); text-decoration: none; }
.pd-brand-link:hover { text-decoration: underline; }

/* ── Price block ── */
.pd-price-block {
  display: flex; align-items: baseline;
  flex-wrap: wrap; gap: 8px; margin-bottom: 14px;
}
.pd-price-main {
  font-size: 2rem; font-weight: 800;
  color: var(--brand); line-height: 1;
  font-family: 'Outfit', sans-serif;
}
.pd-price-old {
  font-size: 1rem; color: var(--text-muted);
  text-decoration: line-through;
}
.pd-price-save {
  font-size: 12px; font-weight: 700; padding: 3px 10px;
  background: var(--brand-light); color: var(--brand);
  border-radius: 6px;
}

/* ── Discount alerts ── */
.pd-discount-alert {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 10px 14px; border-radius: 10px;
  margin-bottom: 12px; font-size: 13px;
}
.pd-discount-alert.product-disc {
  background: #f0fdf4; border: 1px solid #86efac; color: #166534;
}
.pd-discount-alert.global-disc {
  background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af;
}
.pd-discount-alert i { font-size: 15px; flex-shrink: 0; margin-top: 2px; }
.pd-discount-alert strong { display: block; font-weight: 700; }
.pd-discount-alert span { font-size: 12px; opacity: .85; }

/* ── Short description ── */
.pd-short-desc {
  font-size: 14px; color: var(--text-muted);
  line-height: 1.7; margin-bottom: 16px;
}

/* ── Stock ── */
.pd-stock-row { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; }
.pd-stock-badge {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 600; padding: 5px 12px; border-radius: 8px;
}
.pd-stock-badge.in-stock { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.pd-stock-badge.out-stock { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

/* ── Variations ── */
.pd-variations { margin-bottom: 18px; }
.pd-field-label { font-size: 12.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; }
.pd-var-btn {
  padding: 6px 16px; border-radius: 8px;
  border: 2px solid var(--border);
  background: var(--bg-card); color: var(--text);
  font-size: 13px; font-weight: 500; cursor: pointer;
  transition: var(--transition);
}
.pd-var-btn:hover { border-color: var(--brand); color: var(--brand); }
.pd-var-btn.selected { background: var(--brand); color: #fff; border-color: var(--brand); }

/* ── Add to Cart row ── */
.pd-atc-row {
  display: flex; align-items: center; gap: 10px;
  flex-wrap: wrap; margin-bottom: 20px;
}
.pd-qty-wrap {
  display: flex; align-items: center;
  border: 2px solid var(--border); border-radius: 12px; overflow: hidden;
  flex-shrink: 0;
}
.pd-qty-wrap button {
  width: 44px; height: 52px; border: none;
  background: var(--bg-soft); font-size: 20px;
  cursor: pointer; transition: var(--transition);
  color: var(--text); display: flex; align-items: center; justify-content: center;
}
.pd-qty-wrap button:hover { background: var(--brand); color: #fff; }
.pd-qty-wrap input {
  width: 52px; height: 52px; border: none;
  border-left: 2px solid var(--border); border-right: 2px solid var(--border);
  text-align: center; font-weight: 700; font-size: 16px;
  background: var(--bg-card); color: var(--text);
}
.pd-qty-wrap input:focus { outline: none; }
.pd-cart-btn {
  flex: 1; min-height: 52px; min-width: 180px;
  background: var(--grad-brand); color: #fff; border: none;
  border-radius: 12px; font-weight: 700; font-size: 15px;
  cursor: pointer; transition: var(--transition);
  display: flex; align-items: center; justify-content: center;
  gap: 8px; font-family: 'Inter', sans-serif;
  box-shadow: 0 4px 18px rgba(230,57,70,.3);
}
.pd-cart-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(230,57,70,.45);
}
.pd-cart-btn:active:not(:disabled) { transform: translateY(0); }
.pd-cart-btn:disabled { opacity: .55; cursor: not-allowed; }
.pd-wish-btn {
  width: 52px; height: 52px; border: 2px solid var(--border);
  border-radius: 12px; background: var(--bg-card);
  color: var(--text-muted); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; transition: var(--transition); flex-shrink: 0;
}
.pd-wish-btn:hover, .pd-wish-btn.wishlisted {
  background: var(--brand-light); color: var(--brand);
  border-color: rgba(230,57,70,.3);
}

/* ── Payment methods ── */
.pd-pay-section { margin-bottom: 18px; }
.pd-pay-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.pd-pay-chip {
  display: flex; align-items: center; gap: 7px;
  padding: 7px 12px; border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--bg-card);
  font-size: 12.5px; transition: var(--transition);
}
.pd-pay-chip:hover { border-color: var(--brand); transform: translateY(-1px); box-shadow: var(--shadow-sm); }
.pd-pay-icon {
  width: 30px; height: 30px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; flex-shrink: 0;
}
.pd-pay-name { font-weight: 600; color: var(--text); }
.pd-pay-sub { font-size: 10px; color: var(--text-muted); }

/* ── Trust strip ── */
.pd-trust-strip {
  display: flex; flex-wrap: wrap; gap: 8px;
  padding: 14px 16px; border-radius: 12px;
  background: var(--bg-soft); border: 1px solid var(--border);
  margin-bottom: 18px;
}
.pd-trust-item {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; font-weight: 500; color: var(--text-muted);
  flex: 1; min-width: 120px;
}
.pd-trust-item i { font-size: 14px; flex-shrink: 0; }

/* ── Attributes ── */
.pd-attrs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
.pd-attr-chip {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12px; padding: 4px 10px; border-radius: 6px;
  background: var(--bg-soft); border: 1px solid var(--border); color: var(--text);
}
.pd-attr-name { color: var(--text-muted); }
.pd-attr-swatch {
  width: 12px; height: 12px; border-radius: 50%;
  border: 1px solid rgba(0,0,0,.15); flex-shrink: 0;
}

/* ── Meta footer ── */
.pd-meta-footer {
  padding-top: 14px; border-top: 1px solid var(--border);
  font-size: 12.5px; color: var(--text-muted);
  display: flex; flex-direction: column; gap: 4px;
}
.pd-meta-footer a { color: var(--brand); text-decoration: none; }
.pd-meta-key { font-weight: 600; margin-right: 4px; }

/* ── FBT section ── */
.pd-fbt-section {
  padding: 20px 24px; border-radius: 16px;
  background: var(--bg-soft); border: 1px solid var(--border);
}
.pd-fbt-header {
  display: flex; align-items: center; gap: 8px;
  font-size: 15px; font-weight: 700; margin-bottom: 16px;
  color: var(--text);
}
.pd-fbt-header i { color: var(--brand); }
.pd-fbt-items { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.pd-fbt-plus {
  font-size: 22px; font-weight: 700;
  color: var(--text-muted); flex-shrink: 0;
}
.pd-fbt-item {
  display: flex; flex-direction: column; align-items: center;
  width: 100px; text-decoration: none; color: var(--text);
  transition: var(--transition);
}
.pd-fbt-item:hover { transform: translateY(-2px); }
.pd-fbt-item img {
  width: 90px; height: 90px; object-fit: cover;
  border-radius: 12px; border: 1px solid var(--border);
  margin-bottom: 6px;
}
.pd-fbt-item-name { font-size: 11px; text-align: center; line-height: 1.3; color: var(--text-muted); }
.pd-fbt-item-price { font-size: 12px; font-weight: 700; color: var(--brand); margin-top: 2px; }

/* ── Tab section ── */
.pd-tabs-section { }
.pd-tab-body {
  background: var(--bg-card); border: 1px solid var(--border);
  border-top: none; border-radius: 0 0 14px 14px; padding: 28px;
}
.pd-desc-content { line-height: 1.85; font-size: 14.5px; }
.pd-desc-content h1,.pd-desc-content h2,.pd-desc-content h3 { font-family:'Outfit',sans-serif; margin-top:1.2em; }
.pd-desc-content img { max-width:100%; border-radius:8px; }
.pd-desc-content table { width:100%; border-collapse:collapse; }
.pd-desc-content td, .pd-desc-content th { border:1px solid var(--border); padding:8px 12px; font-size:13px; }

/* Reviews */
.pd-rating-summary {
  text-align: center; padding: 16px;
  background: var(--bg-soft); border-radius: 14px;
}
.pd-rating-big { font-size: 3.5rem; font-weight: 800; color: var(--accent); line-height: 1; font-family:'Outfit',sans-serif; }
.pd-rating-bars { }
.pd-rating-bar-row {
  display: flex; align-items: center; gap: 6px; margin-bottom: 6px;
  font-size: 11px; color: var(--text-muted);
}
.pd-rating-bar-track {
  flex: 1; height: 7px; background: var(--border);
  border-radius: 9px; overflow: hidden;
}
.pd-rating-bar-fill { height: 100%; background: var(--grad-brand); border-radius: 9px; }
.pd-rating-bar-count { min-width: 16px; text-align: right; }
.pd-review-form-wrap {
  background: var(--bg-soft); border-radius: 12px;
  padding: 18px; margin-bottom: 24px;
}
.pd-star-picker { display: flex; flex-direction: row-reverse; gap: 4px; width: fit-content; }
.pd-star-label { font-size: 22px; cursor: pointer; color: #ddd; transition: color .15s; }
.pd-star-picker input:checked ~ label,
.pd-star-label:hover, .pd-star-label:hover ~ label { color: #ffb83c; }
.pd-reviews-list { }
.pd-review-item { display: flex; gap: 12px; padding: 16px 0; border-bottom: 1px solid var(--border); }
.pd-review-item:last-child { border-bottom: none; }
.rv-avatar {
  width: 42px; height: 42px; border-radius: 12px;
  background: var(--grad-brand); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 16px; flex-shrink: 0;
}
.pd-review-body { flex: 1; }
.pd-review-header {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 4px;
}

/* ── Section header ── */
.pd-rel-section { margin-top: 48px; }
.pd-section-header {
  display: flex; justify-content: space-between;
  align-items: flex-start; margin-bottom: 18px; gap: 12px;
}
.pd-section-title {
  font-size: 1.15rem; font-weight: 800;
  font-family: 'Outfit', sans-serif; color: var(--text);
  position: relative; padding-bottom: 6px; margin-bottom: 0;
}
.pd-section-title::after {
  content: ''; display: block; position: absolute; bottom: 0; left: 0;
  width: 36px; height: 3px; border-radius: 2px;
  background: var(--grad-brand);
}
.pd-section-sub {
  font-size: 12px; color: var(--text-muted); margin: 6px 0 0;
}
.pd-car-controls { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.pd-car-btn {
  width: 36px; height: 36px; border-radius: 50%;
  border: 1px solid var(--border); background: var(--bg-card);
  color: var(--text-muted); cursor: pointer; font-size: 12px;
  display: flex; align-items: center; justify-content: center;
  transition: var(--transition);
}
.pd-car-btn:not(:disabled):hover { background: var(--brand); color: #fff; border-color: var(--brand); }
.pd-car-btn:disabled { opacity: .35; cursor: default; }
.pd-see-all {
  font-size: 12px; font-weight: 600; color: var(--brand);
  text-decoration: none; display: flex; align-items: center; gap: 5px;
  padding: 6px 12px; border-radius: 20px;
  border: 1px solid rgba(230,57,70,.25);
  transition: var(--transition); white-space: nowrap;
}
.pd-see-all:hover { background: var(--brand); color: #fff; border-color: var(--brand); }

/* ── Carousel ── */
.pd-carousel-wrap {
  overflow: hidden;
  cursor: grab;
  user-select: none;
}
.pd-carousel-wrap.dragging { cursor: grabbing; }
.pd-carousel {
  display: flex; gap: 14px;
  overflow-x: auto; scroll-behavior: smooth;
  scroll-snap-type: x mandatory;
  scrollbar-width: none;
  padding-bottom: 2px;
}
.pd-carousel::-webkit-scrollbar { display: none; }
.pd-carousel-item {
  flex: 0 0 calc(25% - 11px);
  scroll-snap-align: start;
  min-width: 0;
}
@media (max-width: 1199px) { .pd-carousel-item { flex: 0 0 calc(33.333% - 10px); } }
@media (max-width: 767px)  { .pd-carousel-item { flex: 0 0 calc(50% - 7px); } }
@media (max-width: 479px)  { .pd-carousel-item { flex: 0 0 calc(65% - 5px); } }

/* ── Dots ── */
.pd-car-dots {
  display: flex; justify-content: center; gap: 6px;
  margin-top: 14px; min-height: 10px;
}
.pd-car-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--border2, rgba(255,255,255,.15));
  border: none; cursor: pointer; padding: 0;
  transition: background .2s, transform .2s;
}
.pd-car-dot.active {
  background: var(--brand);
  transform: scale(1.3);
}

/* ── Lightbox ── */
#pdLightbox {
  display: none; position: fixed; inset: 0; z-index: 9000;
  background: rgba(0,0,0,.93);
  align-items: center; justify-content: center;
  cursor: zoom-out;
}
#pdLightbox.open { display: flex; }
#pdLbImg { max-width: 90vw; max-height: 88vh; object-fit: contain; border-radius: 10px; user-select: none; }
#pdLbClose {
  position: absolute; top: 18px; right: 24px;
  background: rgba(255,255,255,.12); color: #fff; border: none;
  width: 42px; height: 42px; border-radius: 50%; cursor: pointer;
  font-size: 16px; display: flex; align-items: center; justify-content: center;
  transition: var(--transition); z-index: 1;
}
#pdLbClose:hover { background: var(--brand); }
#pdLbPrev, #pdLbNext {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(255,255,255,.12); color: #fff; border: none;
  width: 46px; height: 46px; border-radius: 50%; cursor: pointer;
  font-size: 16px; display: flex; align-items: center; justify-content: center;
  transition: var(--transition);
}
#pdLbPrev:hover, #pdLbNext:hover { background: var(--brand); }
#pdLbPrev { left: 20px; } #pdLbNext { right: 20px; }

/* ── Mobile tweaks ── */
@media (max-width: 767px) {
  .pd-gallery-sticky { position: static; }
  .pd-img-main { border-radius: 14px; }
  .pd-carousel-item { flex-basis: 180px; }
  .pd-atc-row { flex-wrap: nowrap; }
  .pd-cart-btn { min-width: 0; }
  .pd-pay-chip .pd-pay-sub { display: none; }
  .pd-tab-body { padding: 18px 14px; }
  .pd-fbt-section { padding: 16px; }
}
</style>

<?php
$allImagesJson = json_encode(array_values(array_map(function($img) { return url('uploads/products/'.e($img)); }, $allImages)));
$extraScript = <<<JSEOF
<script>
(function(){
  // ── Image gallery ──
  var pdImages = {$allImagesJson};
  var pdCurrentIdx = 0;

  window.pdSetImg = function(el, src) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.pd-thumb').forEach(function(t){ t.classList.remove('active'); });
    if (el) el.classList.add('active');
    pdCurrentIdx = pdImages.indexOf(src);
  };

  // ── Lightbox ──
  var lb    = document.getElementById('pdLightbox');
  var lbImg = document.getElementById('pdLbImg');

  document.getElementById('pdImgWrap').addEventListener('click', function() {
    lbImg.src = document.getElementById('mainProductImage').src;
    pdCurrentIdx = pdImages.indexOf(lbImg.src);
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
  });
  document.getElementById('pdLbClose').addEventListener('click', closeLb);
  lb.addEventListener('click', function(e){ if(e.target===lb) closeLb(); });
  document.getElementById('pdLbPrev').addEventListener('click', function(e){ e.stopPropagation(); lbNav(-1); });
  document.getElementById('pdLbNext').addEventListener('click', function(e){ e.stopPropagation(); lbNav(1); });
  function lbNav(dir) {
    if (!pdImages.length) return;
    pdCurrentIdx = (pdCurrentIdx + dir + pdImages.length) % pdImages.length;
    lbImg.src = pdImages[pdCurrentIdx];
  }
  function closeLb() { lb.classList.remove('open'); document.body.style.overflow=''; }
  document.addEventListener('keydown', function(e){
    if (!lb.classList.contains('open')) return;
    if (e.key==='Escape') closeLb();
    if (e.key==='ArrowLeft') lbNav(-1);
    if (e.key==='ArrowRight') lbNav(1);
  });
  if (pdImages.length <= 1) {
    document.getElementById('pdLbPrev').style.display='none';
    document.getElementById('pdLbNext').style.display='none';
  }

  // ── Qty buttons ──
  document.getElementById('qtyMinus').addEventListener('click', function(){
    var el = document.getElementById('qty-{$pid}');
    if (el && parseInt(el.value) > 1) el.value = parseInt(el.value) - 1;
  });
  document.getElementById('qtyPlus').addEventListener('click', function(){
    var el  = document.getElementById('qty-{$pid}');
    var max = parseInt(el.getAttribute('max')) || 99;
    if (el && parseInt(el.value) < max) el.value = parseInt(el.value) + 1;
  });

  // ── Variation buttons ──
  document.querySelectorAll('.variation-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.querySelectorAll('.variation-btn').forEach(function(b){ b.classList.remove('selected'); });
      this.classList.add('selected');
      document.getElementById('selectedVariation').value = this.dataset.vid;
      document.getElementById('addToCartBtn').dataset.variationId = this.dataset.vid;
      var sale = parseFloat(this.dataset.sale);
      var base = parseFloat(this.dataset.price);
      var eff  = (sale && sale < base) ? sale : base;
      document.getElementById('productEffPrice').textContent = 'LKR ' + eff.toLocaleString('en-US',{minimumFractionDigits:2});
      var oldEl = document.getElementById('productOldPrice');
      if (oldEl) oldEl.textContent = 'LKR ' + base.toLocaleString('en-US',{minimumFractionDigits:2});
    });
  });

  // ── Copy link ──
  var copyBtn = document.getElementById('pdCopyLink');
  if (copyBtn) {
    copyBtn.addEventListener('click', function(){
      navigator.clipboard.writeText(window.location.href).then(function(){
        toast('success', 'Link copied!', '');
      });
    });
  }

  // ── Review star picker ──
  var starLabels = document.querySelectorAll('.pd-star-label');
  starLabels.forEach(function(label){
    label.addEventListener('mouseover', function(){
      var val = parseInt(this.getAttribute('for').replace('star',''));
      starLabels.forEach(function(l){
        l.style.color = parseInt(l.getAttribute('for').replace('star','')) <= val ? '#ffb83c' : '#ddd';
      });
    });
    label.addEventListener('click', function(){
      starLabels.forEach(function(l){ l.style.color=''; });
    });
  });

  // ── Review form submit ──
  var reviewForm = document.getElementById('reviewForm');
  if (reviewForm) {
    reviewForm.addEventListener('submit', function(e){
      e.preventDefault();
      var data = {
        product_id: this.querySelector('[name="product_id"]').value,
        rating:     (this.querySelector('[name="rating"]:checked') || {value:5}).value,
        title:      this.querySelector('[name="title"]').value,
        body:       this.querySelector('[name="body"]').value,
        _csrf:      CSRF_TOKEN
      };
      $.ajax({
        url: SITE_URL + '/review/submit', type:'POST', data:data, dataType:'json',
        success: function(res){
          if (res.success) { toast('success','Review submitted!',res.message); reviewForm.reset(); }
          else { toast('warning','Oops',res.message); }
        }
      });
    });
  }

  // ── Carousels ──
  function initCarousel(carId, prevId, nextId, dotsId) {
    var car  = document.getElementById(carId);
    var prev = document.getElementById(prevId);
    var next = document.getElementById(nextId);
    var dotsEl = document.getElementById(dotsId);
    if (!car) return;

    function itemWidth() {
      var item = car.querySelector('.pd-carousel-item');
      return item ? item.offsetWidth + 14 : 220;
    }

    function visibleCount() {
      return Math.round(car.offsetWidth / itemWidth()) || 1;
    }

    function totalItems() {
      return car.querySelectorAll('.pd-carousel-item').length;
    }

    // ── Dots ──
    function buildDots() {
      if (!dotsEl) return;
      var pages = Math.ceil(totalItems() / visibleCount());
      if (pages <= 1) { dotsEl.innerHTML = ''; return; }
      dotsEl.innerHTML = '';
      for (var i = 0; i < pages; i++) {
        var d = document.createElement('button');
        d.className = 'pd-car-dot' + (i === 0 ? ' active' : '');
        d.dataset.page = i;
        d.setAttribute('aria-label', 'Page ' + (i + 1));
        d.addEventListener('click', function() {
          car.scrollLeft = this.dataset.page * visibleCount() * itemWidth();
        });
        dotsEl.appendChild(d);
      }
    }

    function updateState() {
      var atStart = car.scrollLeft <= 4;
      var atEnd   = car.scrollLeft >= car.scrollWidth - car.offsetWidth - 4;
      if (prev) prev.disabled = atStart;
      if (next) next.disabled = atEnd;
      // update dots
      if (dotsEl) {
        var page = Math.round(car.scrollLeft / (visibleCount() * itemWidth()));
        dotsEl.querySelectorAll('.pd-car-dot').forEach(function(d, i) {
          d.classList.toggle('active', i === page);
        });
      }
    }

    buildDots();
    updateState();
    car.addEventListener('scroll', updateState, { passive: true });
    window.addEventListener('resize', function() { buildDots(); updateState(); });

    if (prev) prev.addEventListener('click', function() {
      car.scrollLeft -= visibleCount() * itemWidth();
    });
    if (next) next.addEventListener('click', function() {
      car.scrollLeft += visibleCount() * itemWidth();
    });

    // ── Drag to scroll ──
    var wrap = car.parentElement;
    var isDragging = false, startX, startScroll;
    wrap.addEventListener('mousedown', function(e) {
      isDragging = true; startX = e.pageX; startScroll = car.scrollLeft;
      wrap.classList.add('dragging');
      car.style.scrollBehavior = 'auto';
    });
    document.addEventListener('mousemove', function(e) {
      if (!isDragging) return;
      car.scrollLeft = startScroll - (e.pageX - startX);
    });
    document.addEventListener('mouseup', function() {
      if (!isDragging) return;
      isDragging = false;
      wrap.classList.remove('dragging');
      car.style.scrollBehavior = 'smooth';
    });
  }

  initCarousel('relCarousel', 'relPrev', 'relNext', 'relDots');
  initCarousel('ymCarousel',  'ymPrev',  'ymNext',  'ymDots');

  // ── Track view ──
  $.post(SITE_URL + '/track/view', { product_id: {$pid}, _csrf: CSRF_TOKEN });
})();
</script>
JSEOF;
?>
