<?php $pageTitle = 'Products'; ?>
<style>
/* ═══════════════════════════════════════════════════════════════
   PRODUCTS PAGE — RESPONSIVE SYSTEM
   Desktop  ≥ 1024px : full 8-column table
   Tablet   768–1023px: 5-column table (sidebar is 260px wide)
   Mobile   < 768px  : rich card list
   ═══════════════════════════════════════════════════════════════ */

/* ── Count badge in page header ── */
.prod-count-badge {
    display: inline-flex;
    align-items: center;
    background: var(--surface2);
    border: 1px solid var(--border2);
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    border-radius: 8px;
    padding: 2px 9px;
    line-height: 1.6;
}

/* ── Product thumbnail + name cell (shared across all breakpoints) ── */
.prod-cell-main {
    display: flex;
    align-items: center;
    gap: 10px;
}
.prod-thumb {
    width: 42px; height: 42px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
    border: 1px solid var(--border2);
    background: var(--bg2);
}
.prod-info {
    min-width: 0;
    flex: 1;
}
.prod-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);

    display: -webkit-box;
    -webkit-line-clamp: 2;   /* max 2 lines */
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.prod-brand {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Mobile-only inner content — hidden on tablet/desktop */
.prod-mobile-meta, .prod-mobile-actions { display: none; }

/* ── Semantic column classes ── */
.prod-col-sku,
.prod-col-cat,
.prod-col-price,
.prod-col-stock,
.prod-col-status,
.prod-col-feat,
.prod-col-actions { white-space: nowrap; }

/* ── Filter bar always wraps ── */
.admin-filter-bar { flex-wrap: wrap; }
.prod-cat-filter  { min-width: 165px; }

/* ═══════════════════════════════════════════════════════════════
   TABLET: 768 – 1023px
   Available width ≈ viewport − 260px sidebar − 56px padding
   At 768px that's ~450px; at 1023px it's ~707px.
   Show: Product · Price · Stock · Status · Actions
   Hide: SKU · Category · Featured
   ═══════════════════════════════════════════════════════════════ */
@media (min-width: 768px) and (max-width: 1023px) {

    /* Hide 3 columns */
    .prod-col-sku,
    .prod-col-cat,
    .prod-col-feat { display: none !important; }

    /* Tighten cell padding */
    .admin-table .table thead th,
    .admin-table .table tbody td { padding: 9px 10px; }

    /* Allow product name to breathe */
    .prod-col-product { width: 44%; }

    /* Shrink the category filter in toolbar */
    .prod-cat-filter { min-width: 120px; }
}

/* ═══════════════════════════════════════════════════════════════
   MOBILE: < 768px
   Sidebar is off-canvas. Each product becomes a rich card.
   ═══════════════════════════════════════════════════════════════ */
@media (max-width: 767px) {

    /* ── Page header: keep title left, button right ── */
    .page-header {
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* ── Filter bar layout ── */
    /* Row 1: search stretches full width */
    .afb-search  { flex: 1 1 100%; min-width: 0; order: 1; }
    /* Row 2: category grows, per-page shrinks, then buttons */
    .prod-cat-filter { flex: 1 1 0; min-width: 0; order: 2; }
    .afb-per-page    { flex: 0 0 auto; min-width: 0; order: 3; }
    .admin-filter-bar > button[type="submit"]    { order: 4; }
    .admin-filter-bar > a.btn-outline-secondary  { order: 5; }

    /* ── Strip the table wrapper chrome ── */
    .admin-table {
        background: transparent;
        border: none;
        border-radius: 0;
        overflow: visible;
    }
    .admin-table table { display: block; }
    .admin-table thead { display: none; }
    .admin-table tbody { display: flex; flex-direction: column; gap: 8px; }

    /* ── Row → rich card ── */
    .admin-table tbody tr {
        display: block;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color .18s, box-shadow .18s;
    }
    .admin-table tbody tr:hover {
        border-color: rgba(0, 212, 255, .22);
        box-shadow: 0 4px 22px rgba(0, 0, 0, .28);
    }

    /* Hide every cell except the first */
    .admin-table tbody td            { display: none !important; }
    .admin-table tbody td.prod-col-product { display: block !important; padding: 0 !important; }

    /* ── Card section 1: image + name ── */
    .prod-cell-main {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border);
    }
    .prod-thumb { width: 46px; height: 46px; }
    .prod-name  {
        font-size: 13.5px;
        white-space: normal;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .prod-brand { font-size: 11.5px; margin-top: 3px; }

    /* ── Card section 2: meta pills ── */
    .prod-mobile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 10px 12px;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,.015);
    }
    .prod-meta-pill {
        display: flex;
        flex-direction: column;
        gap: 2px;
        background: var(--surface2);
        border: 1px solid var(--border2);
        border-radius: 8px;
        padding: 5px 10px;
        min-width: 72px;
    }
    .prod-meta-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--text-muted);
    }
    .prod-meta-val {
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.3;
    }

    /* ── Card section 3: action buttons ── */
    .prod-mobile-actions {
        display: flex;
        gap: 6px;
        padding: 10px 12px;
    }
    .prod-mobile-actions .btn {
        flex: 1;
        justify-content: center;
        padding: 7px 6px;
        font-size: 12.5px;
        gap: 5px;
    }
}

</style>
<?php

// Build id→category map
$_allCats = [];
foreach (Helper::categories() as $c) { $_allCats[(int)$c['id']] = $c; }
function adminCatPath(int $id, array &$map): string {
    $chain = []; $cur = $id; $guard = 0;
    while ($cur && isset($map[$cur]) && $guard++ < 10) {
        array_unshift($chain, $map[$cur]['name']);
        $cur = (int)($map[$cur]['parent_id'] ?? 0);
    }
    return implode(' › ', $chain);
}
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-2">
        <h4>Products</h4>
        <span class="prod-count-badge"><?= number_format($total) ?></span>
    </div>
    <a href="<?= url('admin/products/create') ?>" class="btn btn-primary btn-sm">
        <i class="fa fa-plus me-1"></i>Add Product
    </a>
</div>

<?php
// Category filter for the toolbar
ob_start(); ?>
<div class="afb-select prod-cat-filter">
    <select name="category" class="form-select form-select-sm afb-input">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $catFilter == $cat['id'] ? 'selected' : '' ?>>
            <?= str_repeat('&nbsp;&nbsp;', $cat['depth']) . ($cat['depth'] ? '↳ ' : '') . e($cat['name']) ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<?php
$_catSelectHtml = ob_get_clean();

$searchFields = [['name' => 'search', 'value' => $search, 'placeholder' => 'Search name or SKU…']];
$filterFields = [];
$extraButtons = $_catSelectHtml;
$itemLabel    = 'products';
include __DIR__ . '/../layouts/_pagination.php';
?>

<div class="admin-table">
    <table class="table table-sm align-middle mb-0">
        <thead>
            <tr>
                <th class="prod-col-product">Product</th>
                <th class="prod-col-sku">SKU</th>
                <th class="prod-col-cat">Category</th>
                <th class="prod-col-price">Price</th>
                <th class="prod-col-stock">Stock</th>
                <th class="prod-col-status">Status</th>
                <th class="prod-col-feat" style="text-align:center">Featured</th>
                <th class="prod-col-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p):
            $qty = (int)$p['stock_qty'];
            $sc  = ['active' => 'success', 'inactive' => 'secondary', 'draft' => 'warning'];
            if ($qty === 0)      { $stockBadge = "<span class='badge bg-danger'>Out of stock</span>";          $stockShort = "<span class='badge bg-danger'>Out</span>"; }
            elseif ($qty <= 5)   { $stockBadge = "<span class='badge bg-warning text-dark'>Low · $qty</span>"; $stockShort = "<span class='badge bg-warning text-dark'>$qty</span>"; }
            else                 { $stockBadge = "<span class='badge bg-success'>$qty</span>";                 $stockShort = "<span class='badge bg-success'>$qty</span>"; }
            $statusBadge = "<span class='badge bg-" . ($sc[$p['status']] ?? 'secondary') . "'>" . ucfirst($p['status']) . "</span>";
        ?>
        <tr>
            <!-- ═══ PRODUCT CELL — also hosts mobile card content ═══ -->
            <td class="prod-col-product">

                <!-- Thumbnail + name: visible on all sizes -->
                <div class="prod-cell-main">
                    <img class="prod-thumb"
                         src="<?= $p['thumbnail'] ? url('uploads/products/' . $p['thumbnail']) : asset('img/placeholder.webp') ?>"
                         alt="<?= e($p['name']) ?>">
                    <div class="prod-info">
                        <div class="prod-name"><?= e($p['name']) ?></div>
                        <?php if ($p['brand_name']): ?>
                        <div class="prod-brand"><?= e($p['brand_name']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Meta pills: mobile only -->
                <div class="prod-mobile-meta">
                    <div class="prod-meta-pill">
                        <span class="prod-meta-label">Price</span>
                        <span class="prod-meta-val">LKR <?= number_format($p['price'], 2) ?></span>
                        <?php if ($p['sale_price']): ?>
                        <span class="prod-meta-val text-danger" style="font-size:10px;">↓ <?= number_format($p['sale_price'], 2) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="prod-meta-pill">
                        <span class="prod-meta-label">Stock</span>
                        <?= $stockBadge ?>
                    </div>
                    <div class="prod-meta-pill">
                        <span class="prod-meta-label">Status</span>
                        <?= $statusBadge ?>
                    </div>
                    <?php if (!empty($p['sku'])): ?>
                    <div class="prod-meta-pill">
                        <span class="prod-meta-label">SKU</span>
                        <span class="prod-meta-val"><?= e($p['sku']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Action buttons: mobile only -->
                <div class="prod-mobile-actions">
                    <a href="<?= url('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a href="<?= url('product/' . $p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <button class="btn btn-sm btn-outline-danger btn-delete-product" data-id="<?= $p['id'] ?>">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </td>

            <!-- Remaining columns: visible on tablet/desktop only -->
            <td class="prod-col-sku small text-muted"><?= e($p['sku'] ?? '—') ?></td>
            <td class="prod-col-cat small">
                <?php if ($p['category_id']): ?>
                    <?= e(adminCatPath((int)$p['category_id'], $_allCats)) ?>
                <?php else: ?>
                    <span class="text-muted">—</span>
                <?php endif; ?>
            </td>
            <td class="prod-col-price small">
                <div class="fw-bold">LKR <?= number_format($p['price'], 2) ?></div>
                <?php if ($p['sale_price']): ?>
                <div class="text-danger" style="font-size:.72rem;">↓ LKR <?= number_format($p['sale_price'], 2) ?></div>
                <?php endif; ?>
            </td>
            <td class="prod-col-stock"><?= $stockShort ?></td>
            <td class="prod-col-status"><?= $statusBadge ?></td>
            <td class="prod-col-feat" style="text-align:center">
                <?= $p['is_featured'] ? '<i class="fa fa-star text-warning"></i>' : '<i class="fa fa-star-o text-muted"></i>' ?>
            </td>
            <td class="prod-col-actions">
                <div class="d-flex gap-1">
                    <a href="<?= url('admin/products/edit/' . $p['id']) ?>"
                       class="btn btn-sm btn-outline-primary py-0" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="<?= url('product/' . $p['slug']) ?>" target="_blank"
                       class="btn btn-sm btn-outline-secondary py-0" title="View">
                        <i class="fa fa-eye"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger py-0 btn-delete-product"
                            data-id="<?= $p['id'] ?>" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $itemLabel = 'products'; include __DIR__ . '/../layouts/_pagnav.php'; ?>

<?php $extraScript = <<<'JS'
<script>
$(document).on('click', '.btn-delete-product', function () {
    const id  = $(this).data('id');
    const $tr = $(this).closest('tr');
    Swal.fire({
        title: 'Delete this product?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#ef4444'
    }).then(r => {
        if (!r.isConfirmed) return;
        ajaxPost('admin/products/delete', { id }, res => {
            if (res.success) {
                $tr.fadeOut(300);
                Swal.fire({ icon: 'success', title: res.message, timer: 1400, showConfirmButton: false });
            }
        });
    });
});
</script>
JS;
?>
