<?php $pageTitle = 'Dashboard'; ?>
<style>
/* ══ DASHBOARD — RESPONSIVE ════════════════════════════════════════
   Scoped to .dash-* to avoid bleed into other admin pages.      */

/* ── 1. Stat card content — CSS-class-driven sizing ────────────── */
.dash-stat-value {
    font-size: 18px;
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    word-break: break-word;   /* allows long LKR values to wrap cleanly */
}
.dash-stat-label {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 3px;
    font-weight: 500;
}

/* Mobile: shrink icon + text so "LKR 1,234,567" fits in col-6 */
@media (max-width: 575px) {
    .stat-card { padding: 13px 11px !important; }
    .stat-card.d-flex { gap: 8px !important; }
    .stat-icon {
        width: 34px !important;
        height: 34px !important;
        font-size: 13px !important;
        border-radius: 9px !important;
        flex-shrink: 0;
    }
    .dash-stat-value { font-size: 14px; }
}
@media (max-width: 380px) {
    .stat-card { padding: 10px 9px !important; }
    .stat-icon { width: 28px !important; height: 28px !important; font-size: 11px !important; }
    .dash-stat-value { font-size: 13px; }
}

/* ── 2. Chart card header ───────────────────────────────────────── */
.dash-card-hdr {
    font-size: 13px; font-weight: 700; color: #fff;
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
    flex-wrap: wrap;
}

/* Revenue chart: fixed-height container so Chart.js fills it */
.dash-chart-box { position: relative; height: 260px; }
@media (max-width: 991px) { .dash-chart-box { height: 230px; } }
@media (max-width: 575px) { .dash-chart-box { height: 190px; } }

/* ── 3. Top-products list — protect thumbnail on narrow screens ── */
.dash-tp-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
    overflow: hidden;
    min-width: 0;
}
.dash-tp-row:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
.dash-tp-thumb {
    width: 36px; height: 36px;
    object-fit: cover; border-radius: 8px;
    border: 1px solid var(--border2); flex-shrink: 0;
}
.dash-tp-info { flex: 1; min-width: 0; }
.dash-tp-name {
    font-size: 12.5px; font-weight: 600; color: #fff;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dash-tp-sold { font-size: 11px; color: var(--text-muted); }
.dash-tp-rev  { font-size: 12px; font-weight: 700; color: var(--cyan); white-space: nowrap; flex-shrink: 0; }

@media (max-width: 575px) {
    .dash-tp-thumb { width: 30px; height: 30px; }
    .dash-tp-name  { font-size: 12px; }
}

/* ── 4. Section header strip (Recent Orders / Low Stock) ────────── */
.dash-sec-hdr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 8px;
}
.dash-sec-title {
    font-weight: 700; font-size: 13.5px; color: #fff;
    display: flex; align-items: center; gap: 8px;
}

/* ── 5. Recent Orders table — scroll wrapper ────────────────────── */
.dash-orders-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
}
.dash-orders-wrap::-webkit-scrollbar       { height: 4px; }
.dash-orders-wrap::-webkit-scrollbar-track { background: transparent; }
.dash-orders-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }

@media (min-width: 576px) {
    #dashOrdersTable { min-width: 460px; }
}

/* ══ MOBILE CARD LAYOUT — Recent Orders (≤ 575px) ══════════════════
   5 DOM columns:
     (1) Order #  (2) Customer  (3) Total  (4) Status  (5) Date

   Card grid — 2 columns: 1fr auto
     Row 1 : Order # (A)    | Total amount (B)
     Row 2 : Customer (A)   | Status badge (B)
     Row 3 : Date (full width)                         */
@media (max-width: 575px) {
    .dash-orders-wrap { overflow-x: visible; }

    #dashOrdersTable thead { display: none; }
    #dashOrdersTable { min-width: unset; }

    #dashOrdersTable tbody tr {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto;
        column-gap: 10px;
        row-gap: 2px;
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
    }
    #dashOrdersTable tbody tr:last-child { border-bottom: none; }

    #dashOrdersTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* (1) Order # — A, row 1 */
    #dashOrdersTable tbody td:nth-child(1) {
        grid-column: 1; grid-row: 1;
        font-size: 13px !important; align-self: center;
    }
    /* (2) Customer — A, row 2 */
    #dashOrdersTable tbody td:nth-child(2) {
        grid-column: 1; grid-row: 2;
        font-size: 11px !important; color: var(--text-muted);
        align-self: center;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    /* (3) Total — B, row 1 */
    #dashOrdersTable tbody td:nth-child(3) {
        grid-column: 2; grid-row: 1;
        text-align: right; white-space: nowrap;
        font-size: 12px !important; align-self: center;
    }
    /* (4) Status — B, row 2 */
    #dashOrdersTable tbody td:nth-child(4) {
        grid-column: 2; grid-row: 2;
        text-align: right; align-self: center;
    }
    /* (5) Date — full width, row 3 */
    #dashOrdersTable tbody td:nth-child(5) {
        grid-column: 1 / 3; grid-row: 3;
        font-size: 10px !important; color: var(--text-muted);
        margin-top: 2px;
    }
    #dashOrdersTable tbody td:nth-child(5)::before {
        content: 'Date: '; opacity: .6;
    }
}

/* ── 6. Low Stock rows — flex-wrap so badge+button don't overflow ── */
.low-stock-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}
.low-stock-row:last-child { border-bottom: none; }
.low-stock-info { flex: 1 1 0; min-width: 0; }

@media (max-width: 479px) {
    .low-stock-row { gap: 6px; padding: 8px 12px; }
    /* On very small phones, info block stays full-width and badge+btn wrap */
    .low-stock-info { flex-basis: 100%; }
    .low-stock-row .badge,
    .low-stock-row .btn { flex-shrink: 0; }
}

/* ── 7. Bottom row (orders + low-stock) min-width guard ─────────── */
/* The admin-table has overflow:hidden — allow visible on mobile when
   card layout is active so grid rows render correctly. */
@media (max-width: 575px) {
    .dash-orders-wrap .admin-table { overflow: visible !important; }
}
</style>

<!-- ══ STAT CARDS ═════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
<?php
$cards = [
    ['label'=>'Total Orders',   'value'=> number_format($stats['total_orders']),                'icon'=>'fa-shopping-bag',        'color'=>'var(--cyan)',   'bg'=>'var(--cyan-dim)'],
    ['label'=>'Revenue',        'value'=>'LKR '.number_format($stats['total_revenue'],0),       'icon'=>'fa-money',               'color'=>'var(--green)',  'bg'=>'var(--green-dim)'],
    ['label'=>'Products',       'value'=> number_format($stats['total_products']),               'icon'=>'fa-cube',                'color'=>'var(--blue)',   'bg'=>'var(--blue-dim)'],
    ['label'=>'Customers',      'value'=> number_format($stats['total_customers']),              'icon'=>'fa-users',               'color'=>'#a78bfa',       'bg'=>'var(--purple-dim)'],
    ['label'=>'Pending Orders', 'value'=> number_format($stats['pending_orders']),               'icon'=>'fa-clock-o',             'color'=>'var(--amber)',  'bg'=>'var(--amber-dim)'],
    ['label'=>'Low Stock',      'value'=> number_format($stats['low_stock']),                    'icon'=>'fa-exclamation-triangle','color'=>'var(--red)',    'bg'=>'var(--red-dim)'],
];
foreach ($cards as $card): ?>
<div class="col-6 col-md-4 col-xl-2">
    <div class="stat-card d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:<?= $card['bg'] ?>;color:<?= $card['color'] ?>;flex-shrink:0;">
            <i class="fa <?= $card['icon'] ?>"></i>
        </div>
        <div style="min-width:0;">
            <div class="dash-stat-value"><?= $card['value'] ?></div>
            <div class="dash-stat-label"><?= $card['label'] ?></div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- ══ CHARTS ════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <!-- Revenue bar chart -->
    <div class="col-lg-8">
        <div class="stat-card">
            <div class="dash-card-hdr">
                <i class="fa fa-bar-chart" style="color:var(--cyan);"></i> Revenue — Last 6 Months
            </div>
            <div class="dash-chart-box">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top products -->
    <div class="col-lg-4">
        <div class="stat-card" style="height:100%;">
            <div class="dash-card-hdr">
                <i class="fa fa-fire" style="color:var(--amber);"></i> Top Products
            </div>
            <?php if (empty($topProducts)): ?>
            <p style="color:var(--text-muted);font-size:12.5px;">No data yet.</p>
            <?php else: ?>
            <?php foreach ($topProducts as $tp): ?>
            <div class="dash-tp-row">
                <img class="dash-tp-thumb"
                     src="<?= $tp['thumbnail'] ? url('uploads/products/'.$tp['thumbnail']) : asset('img/placeholder.webp') ?>"
                     alt="">
                <div class="dash-tp-info">
                    <div class="dash-tp-name"><?= e($tp['name']) ?></div>
                    <div class="dash-tp-sold"><?= $tp['sold'] ?> sold</div>
                </div>
                <div class="dash-tp-rev">LKR <?= number_format($tp['revenue'],0) ?></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ══ RECENT ORDERS + LOW STOCK ════════════════════════════════ -->
<div class="row g-3">
    <!-- Recent Orders -->
    <div class="col-lg-7">
        <div class="admin-table">
            <div class="dash-sec-hdr">
                <span class="dash-sec-title">
                    <i class="fa fa-shopping-bag" style="color:var(--cyan);"></i> Recent Orders
                </span>
                <a href="<?= url('admin/orders') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="dash-orders-wrap">
                <table class="table table-sm align-middle mb-0" id="dashOrdersTable">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentOrders as $o):
                        $sc = ['pending'=>'warning','confirmed'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger','refunded'=>'secondary'];
                    ?>
                    <tr>
                        <td><a href="<?= url('admin/orders/view/'.$o['id']) ?>" style="font-weight:700;color:var(--cyan);"><?= e($o['order_number']) ?></a></td>
                        <td style="font-size:12.5px;"><?= e($o['full_name'] ?? $o['guest_email'] ?? 'Guest') ?></td>
                        <td style="font-size:12.5px;font-weight:700;color:var(--green);white-space:nowrap;">LKR <?= number_format($o['total_amount'],2) ?></td>
                        <td><span class="badge bg-<?= $sc[$o['status']] ?? 'secondary' ?>"><?= ucfirst($o['status']) ?></span></td>
                        <td style="font-size:12px;color:var(--text-muted);white-space:nowrap;"><?= date('M j', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="col-lg-5">
        <div class="admin-table">
            <div class="dash-sec-hdr">
                <span class="dash-sec-title" style="color:var(--red);">
                    <i class="fa fa-exclamation-triangle"></i> Low Stock Alerts
                </span>
                <a href="<?= url('admin/stock/low-stock') ?>" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            <?php if (empty($lowStockItems)): ?>
            <div class="p-3" style="color:var(--green);font-size:13px;font-weight:500;">
                <i class="fa fa-check-circle me-2"></i>All stock levels healthy
            </div>
            <?php else: ?>
            <div style="max-height:320px;overflow-y:auto;">
            <?php foreach (array_slice($lowStockItems, 0, 8) as $ls): ?>
            <div class="low-stock-row">
                <div class="low-stock-info">
                    <div style="font-size:12.5px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($ls['product_name']) ?></div>
                    <?php if ($ls['variation_name']): ?>
                    <div style="font-size:11px;color:var(--text-muted);"><?= e($ls['variation_name']) ?></div>
                    <?php endif; ?>
                </div>
                <span class="badge bg-warning" style="flex-shrink:0;"><?= $ls['quantity'] ?> left</span>
                <a href="<?= url('admin/stock?product='.$ls['product_id']) ?>" class="btn btn-sm btn-outline-warning" style="flex-shrink:0;padding:3px 10px;">Restock</a>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $extraScript = '<script>const monthlyData=' . json_encode($monthlyRevenue ?: []) . ';</script>' . <<<'JS'
<script>
(function () {
    var canvas = document.getElementById('revenueChart');
    if (!canvas || !monthlyData.length) return;

    var labels  = monthlyData.map(function(d) { return d.month; });
    var revenue = monthlyData.map(function(d) { return parseFloat(d.revenue); });
    var orders  = monthlyData.map(function(d) { return parseInt(d.orders); });

    Chart.defaults.color       = '#64748b';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue (LKR)',
                    data: revenue,
                    backgroundColor: 'rgba(0,212,255,0.15)',
                    borderColor: 'rgba(0,212,255,0.6)',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Orders',
                    data: orders,
                    type: 'line',
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.1)',
                    fill: true,
                    yAxisID: 'y1',
                    tension: 0.4,
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,   /* fills .dash-chart-box height */
            plugins: {
                legend: { labels: { color: '#94a3b8', font: { family: 'Inter', size: 11 }, boxWidth: 10, padding: 12 } }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: '#64748b', font: { size: 11 }, maxRotation: 30 }
                },
                y: {
                    type: 'linear', position: 'left', beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                y1: {
                    type: 'linear', position: 'right', beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                }
            }
        }
    });
})();
</script>
JS;
?>
