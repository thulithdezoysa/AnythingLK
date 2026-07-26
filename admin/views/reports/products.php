<?php /* admin/views/reports/products.php */
$totalRevenue = array_sum(array_column($products, 'revenue'));
$totalSold    = array_sum(array_column($products, 'sold'));
$totalViews   = array_sum(array_column($products, 'views'));
?>
<style>
/* ══ PRODUCT REPORT — RESPONSIVE ════════════════════════════════
   Scoped to .rpp-* / #prodTable to avoid bleed.               */

/* ── 1. Summary stat cards ───────────────────────────────────── */
.rpt-stat {
    background: var(--surface1, #fff);
    border: 1px solid var(--border2, #e5e7eb);
    border-radius: 10px;
    padding: 16px 20px;
}
.rpt-stat .num {
    font-size: 22px; font-weight: 800; line-height: 1;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.rpt-stat .lbl {
    font-size: 11px; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .06em; margin-top: 4px;
}
@media (max-width: 575px) {
    .rpt-stat { padding: 12px 14px; }
    .rpt-stat .num { font-size: 17px; }
}

/* ── 2. Stock badge helpers ──────────────────────────────────── */
.stock-high  { background: var(--green-dim); color: var(--green); }
.stock-low   { background: var(--amber-dim); color: var(--amber); }
.stock-out   { background: var(--red-dim);   color: var(--red);   }
.stock-badge { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }

/* ── 3. Table scroll wrapper (tablet / desktop) ──────────────── */
.rpp-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
    border-radius: var(--radius);
}
.rpp-table-wrap::-webkit-scrollbar       { height: 4px; }
.rpp-table-wrap::-webkit-scrollbar-track { background: transparent; }
.rpp-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 768px) {
    #prodTable { min-width: 680px; }
}

/* ══ MOBILE CARD LAYOUT (≤ 767px) ═══════════════════════════════
   8 DOM columns:
     (1) Product  (2) SKU   (3) Price  (4) Views
     (5) Sold     (6) Revenue  (7) Stock  (8) Rating

   Card grid — 2 columns: 1fr auto
     Row 1 : Product name (A) | Stock badge (B)
     Row 2 : SKU (A)          | Price (B)
     Row 3 : Sold (A)         | Views (B)
     Row 4 : Revenue (A)      | Rating (B)             */
@media (max-width: 767px) {

    .rpp-table-wrap { overflow-x: visible; }
    .rpp-table-wrap .admin-table { overflow: visible !important; }

    #prodTable thead { display: none; }
    #prodTable { min-width: unset; }

    #prodTable tbody tr {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto auto;
        column-gap: 10px;
        row-gap: 4px;
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
    }
    #prodTable tbody tr:last-child { border-bottom: none; }

    #prodTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* ── Cell placement ──────────────────────────────────────── */

    /* (1) Product name — A1 */
    #prodTable tbody td:nth-child(1) {
        grid-column: 1; grid-row: 1;
        align-self: center;
    }
    #prodTable tbody td:nth-child(1) div {
        font-size: 13px !important;
        white-space: normal;
        line-height: 1.35;
    }

    /* (2) SKU — A2 */
    #prodTable tbody td:nth-child(2) {
        grid-column: 1; grid-row: 2;
        font-size: 10px !important;
        align-self: center;
    }
    #prodTable tbody td:nth-child(2)::before {
        content: 'SKU: ';
        opacity: .55;
    }

    /* (3) Price — B2 */
    #prodTable tbody td:nth-child(3) {
        grid-column: 2; grid-row: 2;
        font-size: 11px !important;
        text-align: right !important;
        align-self: center;
        white-space: nowrap;
    }

    /* (4) Views — B3 */
    #prodTable tbody td:nth-child(4) {
        grid-column: 2; grid-row: 3;
        font-size: 11px !important;
        text-align: right !important;
        align-self: center;
        color: var(--text-dim) !important;
    }
    #prodTable tbody td:nth-child(4)::before {
        content: 'Views: ';
        font-size: 10px;
        opacity: .6;
    }

    /* (5) Sold — A3 */
    #prodTable tbody td:nth-child(5) {
        grid-column: 1; grid-row: 3;
        font-size: 11px !important;
        text-align: left !important;
        align-self: center;
    }
    #prodTable tbody td:nth-child(5)::before {
        content: 'Sold: ';
        font-size: 10px;
        opacity: .6;
    }

    /* (6) Revenue — A4 */
    #prodTable tbody td:nth-child(6) {
        grid-column: 1; grid-row: 4;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-align: left !important;
        align-self: center;
    }
    #prodTable tbody td:nth-child(6)::before {
        content: 'Revenue: ';
        font-size: 10px;
        font-weight: 400;
        opacity: .6;
    }

    /* (7) Stock badge — B1 */
    #prodTable tbody td:nth-child(7) {
        grid-column: 2; grid-row: 1;
        text-align: right !important;
        align-self: center;
    }

    /* (8) Rating — B4 */
    #prodTable tbody td:nth-child(8) {
        grid-column: 2; grid-row: 4;
        text-align: right !important;
        align-self: center;
        font-size: 11px !important;
    }
}

/* ── Very small phones (< 380px) ────────────────────────────── */
@media (max-width: 379px) {
    #prodTable tbody tr {
        padding: 12px 12px;
        column-gap: 8px;
    }
}
</style>

<!-- ═══ PAGE HEADER ════════════════════════════════════════════ -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4>Product Performance</h4>
        <p class="page-subtitle"><?= count($products) ?> active product<?= count($products) !== 1 ? 's' : '' ?></p>
    </div>
    <a href="<?= url('admin/reports/sales') ?>"
       style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);padding:6px 14px;border-radius:8px;font-size:13px;text-decoration:none;white-space:nowrap;">
        <i class="fa fa-bar-chart me-1"></i>Sales Report
    </a>
</div>

<!-- ═══ SUMMARY STATS ══════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--green);">LKR <?= number_format($totalRevenue, 0) ?></div>
            <div class="lbl">Total Revenue</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--cyan);"><?= number_format($totalSold) ?></div>
            <div class="lbl">Units Sold</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--amber);"><?= number_format($totalViews) ?></div>
            <div class="lbl">Total Views</div>
        </div>
    </div>
</div>

<!-- ═══ PRODUCT PERFORMANCE TABLE ══════════════════════════════ -->
<div class="card">
    <div class="card-body p-0">
        <div class="rpp-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="prodTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width:90px;">SKU</th>
                        <th style="width:110px;text-align:right;">Price</th>
                        <th style="width:70px;text-align:right;">Views</th>
                        <th style="width:70px;text-align:right;">Sold</th>
                        <th style="width:120px;text-align:right;">Revenue</th>
                        <th style="width:70px;text-align:center;">Stock</th>
                        <th style="width:90px;text-align:center;">Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr><td colspan="8" class="text-center py-5" style="color:var(--text-muted);">No product data yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($products as $p):
                        $stockClass = $p['stock'] > 5 ? 'stock-high' : ($p['stock'] > 0 ? 'stock-low' : 'stock-out');
                    ?>
                    <tr>
                        <!-- (1) Product -->
                        <td>
                            <div style="font-size:13px;font-weight:600;color:var(--text-dim);"><?= e(Helper::truncate($p['name'], 40)) ?></div>
                        </td>
                        <!-- (2) SKU -->
                        <td style="font-size:11px;color:var(--text-muted);font-family:monospace;"><?= e($p['sku'] ?? '—') ?></td>
                        <!-- (3) Price -->
                        <td style="text-align:right;font-size:12px;">LKR <?= number_format($p['price'], 2) ?></td>
                        <!-- (4) Views -->
                        <td style="text-align:right;font-size:13px;color:var(--text-dim);"><?= number_format($p['views']) ?></td>
                        <!-- (5) Sold -->
                        <td style="text-align:right;font-size:13px;font-weight:700;color:var(--cyan);"><?= number_format($p['sold']) ?></td>
                        <!-- (6) Revenue -->
                        <td style="text-align:right;font-size:13px;font-weight:700;color:var(--green);">LKR <?= number_format($p['revenue'], 0) ?></td>
                        <!-- (7) Stock -->
                        <td style="text-align:center;">
                            <span class="stock-badge <?= $stockClass ?>"><?= $p['stock'] ?></span>
                        </td>
                        <!-- (8) Rating -->
                        <td style="text-align:center;font-size:12px;">
                            <?php if ($p['rating_avg'] > 0): ?>
                            <span style="color:#f59e0b;font-weight:700;"><?= number_format($p['rating_avg'], 1) ?>★</span>
                            <span style="color:var(--text-muted);font-size:10px;"> (<?= $p['rating_count'] ?>)</span>
                            <?php else: ?>
                            <span style="color:var(--text-muted);">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
