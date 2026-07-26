<?php
// $p = product row from DB
$effectivePrice = (float)($p['sale_price'] ?: $p['price']);
$hasDiscount    = !empty($p['sale_price']) && (float)$p['sale_price'] < (float)$p['price'];
$discountPct    = $hasDiscount ? round((((float)$p['price'] - $effectivePrice) / (float)$p['price']) * 100) : 0;
$thumbnail      = !empty($p['thumbnail'])
    ? url('uploads/products/' . e($p['thumbnail']))
    : url('assets/img/placeholder.webp');
$stockQty   = isset($p['stock_qty']) ? (int)$p['stock_qty'] : -1;
$outOfStock = $stockQty === 0;
$lowStock   = $stockQty > 0 && $stockQty <= 5;
$isNew      = !empty($p['is_new']);
$isBest     = !empty($p['is_featured']);
$rating     = (float)($p['rating_avg'] ?? 0);
$ratingCnt  = (int)($p['rating_count'] ?? 0);
$productUrl = url('product/' . e($p['slug']));
$cur        = setting('currency', 'LKR');
$pid        = (int)$p['id'];
$shortDesc  = trim(strip_tags($p['short_desc'] ?? $p['description'] ?? ''));
?>
<div class="product-card<?= $outOfStock ? ' out-of-stock' : '' ?>">

  <!-- ═══ IMAGE ═══ -->
  <div class="img-wrap">
    <a href="<?= $productUrl ?>" aria-label="<?= e($p['name']) ?>">
      <img src="<?= $thumbnail ?>" alt="<?= e($p['name']) ?>" loading="lazy">
    </a>

    <?php if ($isNew || $isBest || $hasDiscount): ?>
    <div class="pc-badge-stack">
      <?php if ($isNew): ?><span class="pc-label-badge pc-label-new">New</span>
      <?php elseif ($isBest): ?><span class="pc-label-badge pc-label-best">Best</span><?php endif; ?>
      <?php if ($hasDiscount): ?><span class="pc-discount-badge">-<?= $discountPct ?>%</span><?php endif; ?>
    </div>
    <?php endif; ?>

    <button class="pc-wishlist wishlist-btn" data-product-id="<?= $pid ?>" aria-label="Add to wishlist">
      <i class="far fa-heart"></i>
    </button>

    <div class="pc-overlay">
      <button class="pc-cta-btn btn-add-cart" data-product-id="<?= $pid ?>" <?= $outOfStock ? 'disabled' : '' ?>>
        <?php if ($outOfStock): ?><i class="fa fa-ban"></i> Out of Stock
        <?php else: ?><i class="fa fa-cart-plus"></i><?= __('product.add_cart') ?><?php endif; ?>
      </button>
      <button class="pc-qv-btn btn-quick-view" data-slug="<?= e($p['slug']) ?>" aria-label="Quick view">
        <i class="fa fa-eye"></i>
      </button>
    </div>

    <!-- Always-visible quick view button for touch/mobile devices -->
    <button class="pc-qv-mobile btn-quick-view" data-slug="<?= e($p['slug']) ?>" aria-label="Quick view">
      <i class="fa fa-eye"></i>
    </button>
  </div>

  <!-- ═══ BODY ═══ -->
  <div class="card-body">

    <!-- LIST VIEW ZONES (hidden globally; shown only by listing.php when data-view=list) -->
    <div class="lv-info">
      <a href="<?= $productUrl ?>" class="product-name"><?= e($p['name']) ?></a>
      <?php if ($shortDesc): ?>
        <p class="lv-desc"><?= e(mb_strimwidth($shortDesc, 0, 140, '…')) ?></p>
      <?php endif; ?>
      <div class="pc-price-block">
        <span class="product-price"><?= $cur ?> <?= number_format($effectivePrice, 2) ?></span>
        <?php if ($hasDiscount): ?>
          <span class="price-old"><?= $cur ?> <?= number_format((float)$p['price'], 2) ?></span>
        <?php endif; ?>
      </div>
      <?php if ($ratingCnt > 0): ?>
      <div class="star-row"><?= stars($rating) ?><span class="rating-count">(<?= $ratingCnt ?>)</span></div>
      <?php endif; ?>
      <?php if ($outOfStock): ?><span class="pc-stock pc-stock-out">Out of Stock</span>
      <?php elseif ($lowStock): ?><span class="pc-stock pc-stock-low">Only <?= $stockQty ?> left</span>
      <?php else: ?><span class="pc-stock pc-stock-in">In Stock</span><?php endif; ?>
    </div>

    <div class="lv-actions">
      <?php if ($outOfStock): ?>
        <button class="btn btn-secondary btn-add-cart" disabled><i class="fa fa-ban"></i> Out of Stock</button>
      <?php else: ?>
        <button class="btn btn-primary btn-add-cart" data-product-id="<?= $pid ?>">
          <i class="fa fa-cart-plus"></i><?= __('product.add_cart') ?>
        </button>
      <?php endif; ?>
      <div class="lv-icon-row">
        <button class="lv-icon-btn wishlist-btn" data-product-id="<?= $pid ?>" aria-label="Wishlist"><i class="far fa-heart"></i></button>
        <button class="lv-icon-btn btn-quick-view" data-slug="<?= e($p['slug']) ?>" aria-label="Quick view"><i class="fa fa-eye"></i></button>
      </div>
    </div>

    <!-- GRID / COMPACT BODY (always shown; lv-* zones are display:none from default.php) -->
    <a href="<?= $productUrl ?>" class="product-name"><?= e($p['name']) ?></a>

    <div class="pc-price-block">
      <span class="product-price"><?= $cur ?> <?= number_format($effectivePrice, 2) ?></span>
      <?php if ($hasDiscount): ?>
        <span class="price-old"><?= $cur ?> <?= number_format((float)$p['price'], 2) ?></span>
      <?php endif; ?>
    </div>

    <?php if ($ratingCnt > 0): ?>
    <div class="star-row"><?= stars($rating) ?><span class="rating-count">(<?= $ratingCnt ?>)</span></div>
    <?php endif; ?>

    <?php if ($outOfStock): ?><span class="pc-stock pc-stock-out">Out of Stock</span>
    <?php elseif ($lowStock): ?><span class="pc-stock pc-stock-low">Only <?= $stockQty ?> left</span>
    <?php else: ?><span class="pc-stock pc-stock-in">In Stock</span><?php endif; ?>

    <div class="pc-cart-row">
      <?php if ($outOfStock): ?>
        <button class="btn btn-secondary btn-add-cart" disabled><i class="fa fa-ban"></i> Out of Stock</button>
      <?php else: ?>
        <button class="btn btn-primary btn-add-cart" data-product-id="<?= $pid ?>">
          <i class="fa fa-cart-plus"></i><?= __('product.add_cart') ?>
        </button>
      <?php endif; ?>
    </div>

  </div>
</div>
