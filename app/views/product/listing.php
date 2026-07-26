<?php
$pageTitle    = isset($cat) ? e($cat['name']) : (isset($brand) ? e($brand['name']) : (isset($q) && $q ? 'Search: '.e($q) : 'Products'));
$activeBrand  = $filters['brand'] ?? '';
$activeCatSlug= isset($cat) ? $cat['slug'] : '';
$hasFilters   = !empty($activeBrand) || !empty($activeCatSlug) || !empty($filters['min_price']) || !empty($filters['max_price']);

// Build set of ancestor slugs so their subtrees are auto-expanded
$_openSlugs = array_flip(array_column($categoryPath ?? [], 'slug'));
if ($activeCatSlug) $_openSlugs[$activeCatSlug] = true;

function catSubtreeHasActive(array $node, array $openSlugs): bool {
    if (isset($openSlugs[$node['slug']])) return true;
    foreach ($node['_children'] ?? [] as $child) {
        if (catSubtreeHasActive($child, $openSlugs)) return true;
    }
    return false;
}

function renderCatTree(array $nodes, string $activeSlug, array $openSlugs = [], int $depth = 0): void {
    foreach ($nodes as $node):
        $hasChildren = !empty($node['_children']);
        $isActive    = $activeSlug === $node['slug'];
        $isOpen      = $hasChildren && catSubtreeHasActive($node, $openSlugs);
        $icon        = e($node['icon'] ?: 'fa-tag');
        $indent      = $depth * 14;
?>
<div class="ctn-row<?= $isActive ? ' ctn-active' : '' ?>" style="padding-left:<?= $indent ?>px">
  <?php if ($hasChildren): ?>
  <button type="button" class="ctn-toggle<?= $isOpen ? ' open' : '' ?>"
          aria-expanded="<?= $isOpen ? 'true' : 'false' ?>"
          data-target="cg-<?= $node['id'] ?>">
    <i class="fa fa-chevron-right"></i>
  </button>
  <?php else: ?>
  <span class="ctn-spacer"></span>
  <?php endif; ?>
  <a href="<?= url('category/'.e($node['slug'])) ?>" class="ctn-link<?= $isActive ? ' active' : '' ?>">
    <i class="fa <?= $icon ?> ctn-icon"></i>
    <span class="ctn-name"><?= e($node['name']) ?></span>
    <span class="filter-count"><?= (int)$node['total_count'] ?></span>
  </a>
</div>
<?php if ($hasChildren): ?>
<div class="ctn-children<?= $isOpen ? ' open' : '' ?>" id="cg-<?= $node['id'] ?>">
  <?php renderCatTree($node['_children'], $activeSlug, $openSlugs, $depth + 1); ?>
</div>
<?php endif; ?>
<?php endforeach; }
?>

<!-- Breadcrumb -->
<div class="listing-breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0 small">
      <li class="breadcrumb-item"><a href="<?= url('') ?>">Home</a></li>
      <?php if (isset($cat)): ?>
        <?php foreach ($categoryPath ?? [] as $i => $crumb): ?>
          <?php $isLast = ($i === count($categoryPath) - 1); ?>
          <?php if ($isLast): ?>
            <li class="breadcrumb-item active"><?= e($crumb['name']) ?></li>
          <?php else: ?>
            <li class="breadcrumb-item">
              <a href="<?= url('category/'.e($crumb['slug'])) ?>"><?= e($crumb['name']) ?></a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php elseif (isset($brand)): ?>
        <li class="breadcrumb-item"><a href="<?= url('products') ?>">Products</a></li>
        <li class="breadcrumb-item active"><?= e($brand['name']) ?></li>
      <?php elseif (isset($q) && $q): ?>
        <li class="breadcrumb-item active">Search: "<?= e($q) ?>"</li>
      <?php else: ?>
        <li class="breadcrumb-item active">All Products</li>
      <?php endif; ?>
    </ol>
  </div>
</div>

<div class="container py-4">
  <div class="row g-4">

    <!-- ══ Sidebar ══ -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="sidebar-card" style="position:sticky;top:calc(var(--topbar-h,0px) + var(--header-h,70px) + 14px);">
        <div class="sidebar-head">
          <span class="sidebar-title">Filters</span>
          <?php if ($hasFilters): ?>
            <a href="<?= url('products') ?>" class="sidebar-clear">Clear all</a>
          <?php endif; ?>
        </div>
        <form id="filterForm" method="GET">
          <?php if (!empty($q)): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
          <?php if (!empty($activeCatSlug)): ?><input type="hidden" name="_cat" value="<?= e($activeCatSlug) ?>"><?php endif; ?>

          <div class="filter-group">
            <div class="filter-label">Sort By</div>
            <select name="sort" class="filter-select" onchange="this.form.submit()">
              <option value="newest"    <?= ($filters['sort']??'newest')==='newest'   ?'selected':'' ?>>Newest First</option>
              <option value="price_asc" <?= ($filters['sort']??'')==='price_asc'  ?'selected':'' ?>>Price: Low → High</option>
              <option value="price_desc"<?= ($filters['sort']??'')==='price_desc' ?'selected':'' ?>>Price: High → Low</option>
              <option value="popular"  <?= ($filters['sort']??'')==='popular'   ?'selected':'' ?>>Most Popular</option>
              <option value="rating"   <?= ($filters['sort']??'')==='rating'    ?'selected':'' ?>>Best Rated</option>
            </select>
          </div>

          <div class="filter-group">
            <div class="filter-label">Price Range</div>
            <div class="price-range-row">
              <input type="number" name="min_price" class="filter-input" placeholder="Min" value="<?= e($filters['min_price']??'') ?>">
              <span class="price-sep">–</span>
              <input type="number" name="max_price" class="filter-input" placeholder="Max" value="<?= e($filters['max_price']??'') ?>">
            </div>
            <button type="submit" class="filter-apply-btn">Apply</button>
          </div>

          <?php if (!empty($catTree)): ?>
          <div class="filter-group">
            <div class="filter-label">Categories</div>
            <div class="cat-tree-root">
              <?php renderCatTree($catTree, $activeCatSlug, $_openSlugs); ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($brands)): ?>
          <div class="filter-group" style="margin-bottom:0;">
            <div class="filter-label">Brands</div>
            <div class="filter-list">
              <?php foreach ($brands as $br): ?>
                <?php $brActive = $activeBrand === $br['slug']; ?>
                <a href="<?= $brActive ? (isset($cat) ? url('category/'.$cat['slug']) : url('products')) : url('brand/'.$br['slug']) ?>"
                   class="filter-item<?= $brActive ? ' active' : '' ?>">
                  <span><?= e($br['name']) ?></span>
                  <span class="filter-count"><?= (int)$br['cnt'] ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <!-- ══ Main content ══ -->
    <div class="col-lg-9">

      <!-- Active filter chips -->
      <?php if ($hasFilters): ?>
      <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        <span class="small chip-label">Active filters:</span>
        <?php if (!empty($activeCatSlug) && isset($cat)): ?>
          <a href="<?= url('products') ?>" class="filter-chip"><i class="fas fa-th me-1"></i><?= e($cat['name']) ?> <i class="fas fa-times ms-1 chip-x"></i></a>
        <?php endif; ?>
        <?php if (!empty($activeBrand) && isset($brand)): ?>
          <a href="<?= url('products') ?>" class="filter-chip"><i class="fas fa-certificate me-1"></i><?= e($brand['name']) ?> <i class="fas fa-times ms-1 chip-x"></i></a>
        <?php endif; ?>
        <?php if (!empty($filters['min_price']) || !empty($filters['max_price'])): ?>
          <a href="?<?= http_build_query(array_filter(array_merge($_GET,['min_price'=>'','max_price'=>'']))) ?>" class="filter-chip">
            <?= setting('currency','LKR') ?> <?= $filters['min_price']??'0' ?> – <?= $filters['max_price']??'∞' ?> <i class="fas fa-times ms-1 chip-x"></i>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Toolbar -->
      <div class="listing-toolbar">
        <span class="toolbar-count">
          <strong><?= count($result['products']) ?></strong> of <strong><?= number_format($result['total']) ?></strong> products
          <?php if (isset($q) && $q): ?> for "<strong><?= e($q) ?></strong>"<?php endif; ?>
        </span>
        <div class="toolbar-right">
          <button class="mobile-filter-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileFilters">
            <i class="fa fa-sliders me-1"></i>Filters
          </button>
          <select class="toolbar-sort" onchange="window.location.href=updateQueryParam('sort',this.value)">
            <option value="newest"    <?= ($filters['sort']??'newest')==='newest'   ?'selected':'' ?>>Newest</option>
            <option value="price_asc" <?= ($filters['sort']??'')==='price_asc'  ?'selected':'' ?>>Price ↑</option>
            <option value="price_desc"<?= ($filters['sort']??'')==='price_desc' ?'selected':'' ?>>Price ↓</option>
            <option value="popular"  <?= ($filters['sort']??'')==='popular'   ?'selected':'' ?>>Popular</option>
            <option value="rating"   <?= ($filters['sort']??'')==='rating'    ?'selected':'' ?>>Top Rated</option>
          </select>
          <div class="view-toggles" role="group" aria-label="Product view">
            <button class="vbtn" data-vbtn="grid"    title="Grid view">    <i class="fa fa-th"></i></button>
            <button class="vbtn" data-vbtn="list"    title="List view">    <i class="fa fa-list"></i></button>
            <button class="vbtn" data-vbtn="compact" title="Compact view"> <i class="fa fa-th-large"></i></button>
          </div>
        </div>
      </div>

      <!-- Products -->
      <?php if (empty($result['products'])): ?>
        <div class="empty-state">
          <i class="fa fa-search fa-3x mb-3 d-block" style="color:var(--text-muted);opacity:.4;"></i>
          <h5>No products found</h5>
          <p class="small">Try adjusting your filters or search term.</p>
          <a href="<?= url('products') ?>" class="btn btn-primary mt-2">Browse All Products</a>
        </div>
      <?php else: ?>

        <div id="productsWrap" data-view="grid">
          <div class="products-row row">
            <?php foreach ($result['products'] as $p): ?>
              <div class="col"><?php include __DIR__ . '/_product_card.php'; ?></div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if (($pagData['total_pages']??1) > 1): ?>
          <nav class="mt-4" aria-label="Products pagination">
            <ul class="pagination justify-content-center">
              <?php if ($pagData['has_prev']): ?>
                <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$pagData['current_page']-1])) ?>">‹</a></li>
              <?php endif; ?>
              <?php
              $start = max(1, $pagData['current_page']-2);
              $end   = min($pagData['total_pages'], $start+4);
              for ($i=$start; $i<=$end; $i++): ?>
                <li class="page-item<?= $i===$pagData['current_page']?' active':'' ?>">
                  <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$i])) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <?php if ($pagData['has_next']): ?>
                <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$pagData['current_page']+1])) ?>">›</a></li>
              <?php endif; ?>
            </ul>
          </nav>
          <?php endif; ?>
        </div>

      <?php endif; ?>
    </div><!-- /col-lg-9 -->
  </div><!-- /row -->
</div><!-- /container -->

<!-- Mobile filters offcanvas -->
<div class="offcanvas offcanvas-start d-lg-none" id="mobileFilters" tabindex="-1" style="max-width:300px;">
  <div class="offcanvas-header" style="background:var(--bg-card);border-bottom:1px solid var(--border);">
    <h5 class="fw-bold mb-0" style="color:var(--text);">Filters</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" style="background:var(--bg-card);">
    <form method="GET">
      <?php if (!empty($q)): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
      <div class="filter-group">
        <div class="filter-label">Sort By</div>
        <select name="sort" class="filter-select">
          <option value="newest">Newest</option>
          <option value="price_asc">Price: Low → High</option>
          <option value="price_desc">Price: High → Low</option>
          <option value="popular">Most Popular</option>
          <option value="rating">Best Rated</option>
        </select>
      </div>
      <div class="filter-group">
        <div class="filter-label">Price Range</div>
        <div class="price-range-row">
          <input type="number" name="min_price" class="filter-input" placeholder="Min" value="<?= e($filters['min_price']??'') ?>">
          <span class="price-sep">–</span>
          <input type="number" name="max_price" class="filter-input" placeholder="Max" value="<?= e($filters['max_price']??'') ?>">
        </div>
      </div>
      <?php if (!empty($catTree)): ?>
      <div class="filter-group">
        <div class="filter-label">Categories</div>
        <div class="cat-tree-root">
          <?php renderCatTree($catTree, $activeCatSlug, $_openSlugs); ?>
        </div>
      </div>
      <?php endif; ?>
      <?php if (!empty($brands)): ?>
      <div class="filter-group" style="margin-bottom:0;">
        <div class="filter-label">Brands</div>
        <?php foreach ($brands as $br): ?>
          <a href="<?= url('brand/'.$br['slug']) ?>"
             class="filter-item<?= $activeBrand===$br['slug']?' active':'' ?>">
            <span><?= e($br['name']) ?></span><span class="filter-count"><?= (int)$br['cnt'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <button class="filter-apply-btn" style="margin-top:1rem;">Apply Filters</button>
    </form>
  </div>
</div>

<style>
/* ── Breadcrumb ────────────────────────────────────── */
.listing-breadcrumb {
  background: var(--bg-card);
  border-bottom: 1px solid var(--border);
  padding: .6rem 0;
}
.listing-breadcrumb .breadcrumb-item a { color: var(--text-muted); text-decoration: none; }
.listing-breadcrumb .breadcrumb-item a:hover { color: var(--brand); }
.listing-breadcrumb .breadcrumb-item.active { color: var(--text); }
.listing-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }

/* ── Sidebar ───────────────────────────────────────── */
.sidebar-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 1.1rem 1rem;
}
.sidebar-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: .75rem;
  margin-bottom: .75rem;
  border-bottom: 1px solid var(--border);
}
.sidebar-title { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); }
.sidebar-clear { font-size: .75rem; color: var(--brand); text-decoration: none; font-weight: 600; }
.sidebar-clear:hover { text-decoration: underline; }

/* ── Filter groups ─────────────────────────────────── */
.filter-group { margin-bottom: 1.1rem; }
.filter-label {
  font-size: 10.5px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; color: var(--text-muted); margin-bottom: 7px;
}
.filter-select {
  width: 100%;
  padding: 7px 10px;
  font-size: .82rem;
  color: var(--text);
  background: var(--bg-soft);
  border: 1px solid var(--border);
  border-radius: 8px;
  outline: none;
  cursor: pointer;
  transition: border-color .18s;
  appearance: auto;
}
.filter-select:focus { border-color: var(--brand); }

.price-range-row { display: flex; align-items: center; gap: 8px; }
.filter-input {
  flex: 1; padding: 7px 9px; font-size: .8rem;
  color: var(--text); background: var(--bg-soft);
  border: 1px solid var(--border); border-radius: 8px; outline: none; min-width: 0;
  transition: border-color .18s;
}
.filter-input:focus { border-color: var(--brand); }
.price-sep { color: var(--text-muted); font-size: 12px; flex-shrink: 0; }

.filter-apply-btn {
  width: 100%; padding: 8px; border-radius: 8px; border: none;
  background: var(--brand); color: #fff; font-size: .78rem;
  font-weight: 700; letter-spacing: .03em; cursor: pointer;
  transition: opacity .18s;
  margin-top: 8px;
}
.filter-apply-btn:hover { opacity: .88; }

.filter-list { display: flex; flex-direction: column; gap: 1px; max-height: 220px; overflow-y: auto; }
.filter-list::-webkit-scrollbar { width: 3px; }
.filter-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

.filter-item {
  display: flex; justify-content: space-between; align-items: center;
  padding: 6px 8px; border-radius: 7px; font-size: .81rem;
  color: var(--text); text-decoration: none;
  transition: background .15s, color .15s;
}
.filter-item:hover { background: var(--bg-soft); color: var(--brand); }
.filter-item.active { background: rgba(230,57,70,.08); color: var(--brand); font-weight: 600; }
.filter-count {
  font-size: 10px; color: var(--text-muted); background: var(--bg-soft);
  padding: 1px 6px; border-radius: 10px; flex-shrink: 0; min-width: 20px; text-align: center;
}
.filter-item.active .filter-count { background: rgba(230,57,70,.12); color: var(--brand); }

/* ── Category tree ─────────────────────────────────── */
.cat-tree-root { display: flex; flex-direction: column; }

.ctn-row {
  display: flex;
  align-items: center;
  gap: 3px;
  border-radius: 7px;
  transition: background .15s;
}
.ctn-row:hover { background: var(--bg-soft); }
.ctn-row.ctn-active { background: rgba(230,57,70,.07); }

.ctn-toggle {
  flex-shrink: 0;
  width: 28px; height: 28px;
  display: flex; align-items: center; justify-content: center;
  background: none; border: none; padding: 0;
  color: var(--text-muted);
  cursor: pointer;
  border-radius: 5px;
  transition: color .15s, background .15s;
  /* Enlarge clickable area without affecting layout */
  touch-action: manipulation;
}
.ctn-toggle:hover { color: var(--brand); background: rgba(230,57,70,.08); }
.ctn-toggle i {
  font-size: 9px;
  transition: transform .22s cubic-bezier(.4,0,.2,1);
  display: block;
  pointer-events: none;   /* prevent icon from being the click target */
}
.ctn-toggle.open i { transform: rotate(90deg); }

/* Larger touch target on mobile/offcanvas */
@media (max-width: 991px) {
  .ctn-toggle {
    width: 36px; height: 36px;
    margin: -4px -4px -4px 0;   /* compensate layout shift */
  }
}

.ctn-spacer { flex-shrink: 0; width: 22px; }

.ctn-link {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 5px 6px 5px 2px;
  font-size: .81rem;
  color: var(--text);
  text-decoration: none;
  min-width: 0;
  border-radius: 6px;
  transition: color .15s;
}
.ctn-link:hover { color: var(--brand); }
.ctn-link.active { color: var(--brand); font-weight: 600; }

.ctn-icon {
  font-size: 11px;
  width: 14px;
  text-align: center;
  flex-shrink: 0;
  color: var(--text-muted);
  transition: color .15s;
}
.ctn-link:hover .ctn-icon,
.ctn-link.active .ctn-icon { color: var(--brand); }

.ctn-name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.ctn-link.active .filter-count { background: rgba(230,57,70,.12); color: var(--brand); }

.ctn-children {
  display: none;
  overflow: hidden;
}
.ctn-children.open { display: block; }

/* ── Filter chips ──────────────────────────────────── */
.chip-label { color: var(--text-muted); }
.filter-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 500;
  background: rgba(230,57,70,.08); color: var(--brand);
  border: 1px solid rgba(230,57,70,.2); text-decoration: none;
  transition: background .18s, color .18s;
}
.filter-chip:hover { background: var(--brand); color: #fff; }
.chip-x { font-size: 8px; opacity: .7; }

/* ── Toolbar ───────────────────────────────────────── */
.listing-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 9px 14px;
  margin-bottom: 16px;
}
.toolbar-count { font-size: .82rem; color: var(--text-muted); }
.toolbar-count strong { color: var(--text); }
.toolbar-right { display: flex; align-items: center; gap: 8px; }

.mobile-filter-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 12px; border-radius: 7px; font-size: .78rem; font-weight: 600;
  background: var(--bg-soft); color: var(--text); border: 1px solid var(--border);
  cursor: pointer; transition: background .18s;
}
.mobile-filter-btn:hover { background: var(--border); }

.toolbar-sort {
  padding: 6px 10px; font-size: .8rem;
  color: var(--text); background: var(--bg-soft);
  border: 1px solid var(--border); border-radius: 8px; outline: none; cursor: pointer;
}
.toolbar-sort:focus { border-color: var(--brand); }

/* ── View toggle buttons ───────────────────────────── */
.view-toggles {
  display: flex;
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  background: var(--bg-soft);
}
.vbtn {
  width: 34px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  background: transparent; border: none;
  color: var(--text-muted); font-size: 12.5px; cursor: pointer;
  transition: background .18s, color .18s;
}
.vbtn + .vbtn { border-left: 1px solid var(--border); }
.vbtn:hover { color: var(--text); background: var(--bg-card); }
.vbtn.active { background: var(--brand); color: #fff; }

/* ── Empty state ───────────────────────────────────── */
.empty-state {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: 12px; padding: 3.5rem 2rem; text-align: center; color: var(--text);
}
.empty-state h5 { color: var(--text); margin-bottom: 6px; }
.empty-state p { color: var(--text-muted); }

/* ── Products wrap — base row ──────────────────────── */
.products-row {
  --bs-gutter-x: 1rem;
  --bs-gutter-y: 1rem;
}
@media (max-width: 575.98px) {
  .products-row { --bs-gutter-x: .625rem; --bs-gutter-y: .625rem; }
}

/* ════════════════════════════════════════════════════
   GRID VIEW (default)
════════════════════════════════════════════════════ */
#productsWrap[data-view="grid"] .products-row > .col {
  flex: 0 0 50%; max-width: 50%;
}
@media (min-width: 576px) {
  #productsWrap[data-view="grid"] .products-row > .col { flex: 0 0 50%; max-width: 50%; }
}
@media (min-width: 768px) {
  #productsWrap[data-view="grid"] .products-row > .col { flex: 0 0 33.333%; max-width: 33.333%; }
}
@media (min-width: 992px) {
  #productsWrap[data-view="grid"] .products-row > .col { flex: 0 0 25%; max-width: 25%; }
}

/* ════════════════════════════════════════════════════
   LIST VIEW
════════════════════════════════════════════════════ */
#productsWrap[data-view="list"] .products-row {
  --bs-gutter-x: 0;
  --bs-gutter-y: .5rem;
}
#productsWrap[data-view="list"] .products-row > .col {
  flex: 0 0 100%; max-width: 100%;
}

/* Card: horizontal */
#productsWrap[data-view="list"] .product-card {
  flex-direction: row;
  height: auto;
  min-height: 130px;
  border-radius: 10px;
  transform: none !important;
}

/* Image — fixed square, full card height */
#productsWrap[data-view="list"] .product-card .img-wrap {
  width: 150px;
  min-width: 150px;
  height: auto;
  align-self: stretch;
  aspect-ratio: unset;
  flex-shrink: 0;
  border-radius: 10px 0 0 10px;
}

/* Hide image overlays — actions live in body */
#productsWrap[data-view="list"] .product-card .img-wrap .pc-overlay,
#productsWrap[data-view="list"] .product-card .img-wrap .pc-wishlist,
#productsWrap[data-view="list"] .product-card .img-wrap .pc-badge-stack { display: none !important; }

/* Card body: flex-row — info left, actions right */
#productsWrap[data-view="list"] .product-card .card-body {
  flex-direction: row;
  align-items: center;
  padding: 14px 16px;
  gap: 14px;
  min-width: 0;
}

/* CRITICAL FIX: hide all direct grid-body children in list view */
#productsWrap[data-view="list"] .product-card .card-body > .product-name,
#productsWrap[data-view="list"] .product-card .card-body > .pc-price-block,
#productsWrap[data-view="list"] .product-card .card-body > .star-row,
#productsWrap[data-view="list"] .product-card .card-body > .pc-stock,
#productsWrap[data-view="list"] .product-card .card-body > .pc-cart-row { display: none !important; }

/* ── Info zone (left, flex:1) ── */
#productsWrap[data-view="list"] .product-card .lv-info {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
}
#productsWrap[data-view="list"] .product-card .lv-info .product-name {
  font-size: .88rem;
  font-weight: 600;
  min-height: auto;
  -webkit-line-clamp: 2;
  color: var(--text);
  text-decoration: none;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  transition: color .18s;
}
#productsWrap[data-view="list"] .product-card .lv-info .product-name:hover { color: var(--brand); }
#productsWrap[data-view="list"] .product-card .lv-info .pc-price-block { margin-top: 0; }
#productsWrap[data-view="list"] .product-card .lv-info .product-price  { font-size: .97rem; }
#productsWrap[data-view="list"] .product-card .lv-info .star-row       { margin-top: 0; }
#productsWrap[data-view="list"] .product-card .lv-info .pc-stock       { margin-top: 0; font-size: 11px; }

/* Description text in list view */
#productsWrap[data-view="list"] .product-card .lv-info .lv-desc {
  font-size: .78rem;
  color: var(--text-muted);
  line-height: 1.5;
  margin: 0;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
@media (max-width: 767px) {
  #productsWrap[data-view="list"] .product-card .lv-info .lv-desc { -webkit-line-clamp: 1; font-size: .73rem; }
}
@media (max-width: 575px) {
  #productsWrap[data-view="list"] .product-card .lv-info .lv-desc { display: none; }
}

/* ── Action zone (right rail) ── */
#productsWrap[data-view="list"] .product-card .lv-actions {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  gap: 7px;
  flex-shrink: 0;
  width: 144px;
}
#productsWrap[data-view="list"] .product-card .lv-actions .btn-add-cart {
  border-radius: 8px;
  font-size: .75rem;
  font-weight: 700;
  padding: 8px 10px;
  white-space: nowrap;
  justify-content: center;
}

/* Icon row (wishlist + quick view) */
.lv-icon-row { display: flex; gap: 6px; }
.lv-icon-btn {
  flex: 1; height: 32px; border-radius: 7px;
  background: var(--bg-soft); border: 1px solid var(--border);
  color: var(--text-muted); font-size: 12px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: background .18s, color .18s, border-color .18s;
}
.lv-icon-btn:hover        { background: var(--brand); border-color: var(--brand); color: #fff; }
.lv-icon-btn.active       { background: var(--brand); border-color: var(--brand); color: #fff; }
.lv-icon-btn.active .fa-heart-o::before { content: '\f004'; }

/* ── Tablet: 576–767px ── */
@media (max-width: 767px) {
  #productsWrap[data-view="list"] .product-card .img-wrap { width: 110px; min-width: 110px; }
  #productsWrap[data-view="list"] .product-card .card-body { padding: 12px 12px; gap: 10px; }
  #productsWrap[data-view="list"] .product-card .lv-actions { width: 120px; }
  #productsWrap[data-view="list"] .product-card .lv-info .product-name { font-size: .82rem; }
  #productsWrap[data-view="list"] .product-card .lv-info .product-price { font-size: .9rem; }
}

/* ── Mobile: <576px ── */
@media (max-width: 575px) {
  #productsWrap[data-view="list"] .product-card .img-wrap { width: 85px; min-width: 85px; }
  #productsWrap[data-view="list"] .product-card .card-body { padding: 10px; gap: 8px; }
  #productsWrap[data-view="list"] .product-card .lv-actions { width: auto; min-width: 76px; flex-shrink: 1; }
  #productsWrap[data-view="list"] .product-card .lv-icon-row { display: none; }
  #productsWrap[data-view="list"] .product-card .lv-info .product-name { font-size: .76rem; }
  #productsWrap[data-view="list"] .product-card .lv-info .product-price { font-size: .84rem; }
  #productsWrap[data-view="list"] .product-card .lv-info .star-row,
  #productsWrap[data-view="list"] .product-card .lv-info .pc-stock { display: none; }
  #productsWrap[data-view="list"] .product-card .lv-actions .btn-add-cart { font-size: .7rem; padding: 7px 8px; }
}

/* Show lv-* zones in list view */
#productsWrap[data-view="list"] .product-card .lv-info,
#productsWrap[data-view="list"] .product-card .lv-actions { display: flex !important; }

/* ════════════════════════════════════════════════════
   COMPACT VIEW
════════════════════════════════════════════════════ */
#productsWrap[data-view="compact"] .products-row {
  --bs-gutter-x: .625rem;
  --bs-gutter-y: .625rem;
}
#productsWrap[data-view="compact"] .products-row > .col {
  flex: 0 0 33.333%; max-width: 33.333%;
}
@media (min-width: 576px) {
  #productsWrap[data-view="compact"] .products-row > .col { flex: 0 0 25%; max-width: 25%; }
}
@media (min-width: 992px) {
  #productsWrap[data-view="compact"] .products-row > .col { flex: 0 0 20%; max-width: 20%; }
}

#productsWrap[data-view="compact"] .product-card { border-radius: 10px; }

#productsWrap[data-view="compact"] .product-card .card-body {
  padding: 8px 9px 9px;
  gap: 2px;
}
#productsWrap[data-view="compact"] .product-card .product-name {
  font-size: .74rem;
  min-height: calc(1.45em * 2);
}
#productsWrap[data-view="compact"] .product-card .product-price { font-size: .88rem; }
#productsWrap[data-view="compact"] .product-card .pc-price-block { margin-top: 5px; }
#productsWrap[data-view="compact"] .product-card .star-row i { font-size: 9px; }
#productsWrap[data-view="compact"] .product-card .rating-count { font-size: 9px; }
#productsWrap[data-view="compact"] .product-card .pc-stock { font-size: 9px; }
#productsWrap[data-view="compact"] .product-card .pc-cart-row { padding-top: 6px; }
#productsWrap[data-view="compact"] .product-card .btn-add-cart {
  padding: 7px 8px; font-size: .72rem; gap: 4px; border-radius: 7px;
}
#productsWrap[data-view="compact"] .product-card .pc-wishlist { width: 28px; height: 28px; font-size: 11px; }
#productsWrap[data-view="compact"] .product-card .pc-badge-stack { top: 6px; left: 6px; }
#productsWrap[data-view="compact"] .product-card .pc-label-badge,
#productsWrap[data-view="compact"] .product-card .pc-discount-badge { font-size: 8.5px; padding: 2px 6px; }

/* ── Hide lv-only zones in grid/compact ────────────── */
#productsWrap:not([data-view="list"]) .product-card .lv-info,
#productsWrap:not([data-view="list"]) .product-card .lv-actions { display: none !important; }

/* ── Pagination theme fix ──────────────────────────── */
.page-link {
  background: var(--bg-card);
  border-color: var(--border);
  color: var(--text);
}
.page-link:hover { background: var(--bg-soft); color: var(--brand); border-color: var(--border); }
.page-item.active .page-link { background: var(--brand); border-color: var(--brand); color: #fff; }
.page-item.disabled .page-link { background: var(--bg-soft); color: var(--text-muted); border-color: var(--border); }
</style>

<?php $extraScript = <<<'JSEOF'
<script>
/* ── Category tree toggle ─────────────────────────── */
// Use DOM traversal instead of getElementById.
// Root cause of the mobile bug: renderCatTree() is called twice — once for the
// desktop sidebar and once for the mobile offcanvas — producing duplicate IDs
// (cg-5, cg-12…). getElementById always returns the FIRST match (desktop,
// which is display:none on mobile), so the offcanvas tree never toggled.
document.querySelectorAll('.ctn-toggle').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    var row      = this.closest('.ctn-row');
    var children = row && row.nextElementSibling;
    if (!children || !children.classList.contains('ctn-children')) return;
    var isOpen = children.classList.contains('open');
    children.classList.toggle('open', !isOpen);
    this.classList.toggle('open', !isOpen);
    this.setAttribute('aria-expanded', String(!isOpen));
  });
});

function updateQueryParam(key, value) {
  const url = new URL(window.location.href);
  url.searchParams.set(key, value);
  url.searchParams.delete('page');
  return url.search;
}

(function () {
  const wrap = document.getElementById('productsWrap');
  if (!wrap) return;
  const btns = document.querySelectorAll('.vbtn');
  const KEY  = 'plv';

  function setView(v) {
    wrap.dataset.view = v;
    btns.forEach(b => b.classList.toggle('active', b.dataset.vbtn === v));
    try { localStorage.setItem(KEY, v); } catch(e) {}
  }

  btns.forEach(b => b.addEventListener('click', function () { setView(this.dataset.vbtn); }));

  // Restore saved view (default: grid)
  const saved = (function(){ try{ return localStorage.getItem(KEY); }catch(e){ return null; } })();
  setView(saved && ['grid','list','compact'].includes(saved) ? saved : 'grid');
})();
</script>
JSEOF;
?>
