<?php /* admin/views/reports/sales.php */ ?>
<style>
/* ══ SALES REPORT — RESPONSIVE ══════════════════════════════════
   Scoped to .rsp-* / #salesTopTable to avoid bleed.            */

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

/* ── 2. Date range filter form ───────────────────────────────── */
.rsp-date-form {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 8px 12px;
}
.rsp-date-field {
    flex: 1 1 130px;
    min-width: 120px;
}
.rsp-date-field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-dim);
    margin-bottom: 4px;
}
.rsp-date-btns {
    display: flex;
    gap: 6px;
    align-items: flex-end;
    flex-shrink: 0;
}

/* ── 3. Top-products table scroll wrapper ────────────────────── */
.rsp-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
    border-radius: var(--radius);
}
.rsp-table-wrap::-webkit-scrollbar       { height: 4px; }
.rsp-table-wrap::-webkit-scrollbar-track { background: transparent; }
.rsp-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 576px) {
    #salesTopTable { min-width: 380px; }
}

/* ── 4. Mobile card layout for top-products table (≤ 575px) ─── */
/*  4 cols: (1) Rank  (2) Product  (3) Units  (4) Revenue        */
@media (max-width: 575px) {

    .rsp-table-wrap { overflow-x: visible; }
    .rsp-table-wrap .admin-table { overflow: visible !important; }

    #salesTopTable thead { display: none; }
    #salesTopTable { min-width: unset; }

    #salesTopTable tbody tr {
        display: grid;
        grid-template-columns: auto 1fr auto;
        grid-template-rows: auto auto;
        column-gap: 8px;
        row-gap: 2px;
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        align-items: center;
    }
    #salesTopTable tbody tr:last-child { border-bottom: none; }

    #salesTopTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* (1) Rank — col 1, row 1+2 */
    #salesTopTable tbody td:nth-child(1) {
        grid-column: 1; grid-row: 1 / 3;
        font-size: 18px !important; font-weight: 900;
        color: var(--border2);
        align-self: center;
        min-width: 26px;
        text-align: center;
    }

    /* (2) Product — col 2, row 1 */
    #salesTopTable tbody td:nth-child(2) {
        grid-column: 2; grid-row: 1;
        font-size: 12px !important; font-weight: 700;
        color: var(--text-dim);
        white-space: normal;
    }

    /* (3) Units — col 2, row 2 (with label) */
    #salesTopTable tbody td:nth-child(3) {
        grid-column: 2; grid-row: 2;
        font-size: 11px !important;
        text-align: left !important;
    }
    #salesTopTable tbody td:nth-child(3)::before {
        content: 'Units: '; font-size: 10px; opacity: .65;
    }

    /* (4) Revenue — col 3, row 1+2 */
    #salesTopTable tbody td:nth-child(4) {
        grid-column: 3; grid-row: 1 / 3;
        font-size: 12px !important; font-weight: 700;
        text-align: right !important;
        white-space: nowrap;
        align-self: center;
    }
}
</style>

<!-- ═══ PAGE HEADER ════════════════════════════════════════════ -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4>Sales Report</h4>
        <p class="page-subtitle"><?= date('M j, Y', strtotime($from)) ?> — <?= date('M j, Y', strtotime($to)) ?></p>
    </div>
    <a href="<?= url('admin/reports/products') ?>"
       style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);padding:6px 14px;border-radius:8px;font-size:13px;text-decoration:none;white-space:nowrap;">
        <i class="fa fa-cube me-1"></i>Product Report
    </a>
</div>

<!-- ═══ DATE RANGE FILTER ══════════════════════════════════════ -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="rsp-date-form">
            <div class="rsp-date-field">
                <label for="rsp-from">From</label>
                <input type="date" id="rsp-from" name="from"
                       class="form-control form-control-sm" value="<?= e($from) ?>">
            </div>
            <div class="rsp-date-field">
                <label for="rsp-to">To</label>
                <input type="date" id="rsp-to" name="to"
                       class="form-control form-control-sm" value="<?= e($to) ?>">
            </div>
            <div class="rsp-date-btns">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-search me-1"></i>Apply
                </button>
                <a href="<?= url('admin/reports/sales') ?>" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- ═══ SUMMARY STATS ══════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--cyan);"><?= number_format($summary['total_orders'] ?? 0) ?></div>
            <div class="lbl">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--green);">LKR <?= number_format($summary['total_revenue'] ?? 0, 0) ?></div>
            <div class="lbl">Total Revenue</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="rpt-stat">
            <div class="num" style="color:var(--amber);">LKR <?= number_format($summary['avg_order'] ?? 0, 0) ?></div>
            <div class="lbl">Avg Order Value</div>
        </div>
    </div>
</div>

<!-- ═══ DAILY REVENUE CHART ════════════════════════════════════ -->
<div class="card mb-4">
    <div class="card-header">
        <span class="card-title"><i class="fa fa-line-chart me-2"></i>Daily Revenue</span>
    </div>
    <div class="card-body">
        <?php if (empty($daily)): ?>
        <div class="text-center py-4" style="color:var(--text-muted);">No data for selected period.</div>
        <?php else: ?>
        <canvas id="salesChart" style="max-height:280px;"></canvas>
        <?php endif; ?>
    </div>
</div>

<!-- ═══ TOP PRODUCTS TABLE ══════════════════════════════════════ -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fa fa-trophy me-2"></i>Top Products in Period</span>
    </div>
    <div class="card-body p-0">
        <div class="rsp-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="salesTopTable">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Product</th>
                        <th style="width:90px;text-align:right;">Units</th>
                        <th style="width:130px;text-align:right;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topProducts)): ?>
                    <tr><td colspan="4" class="text-center py-4" style="color:var(--text-muted);">No sales data for this period.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($topProducts as $i => $p): ?>
                    <tr>
                        <td style="font-size:12px;color:var(--text-muted);font-weight:700;"><?= $i + 1 ?></td>
                        <td style="font-size:13px;font-weight:600;color:var(--text-dim);"><?= e(Helper::truncate($p['name'] ?? '', 45)) ?></td>
                        <td style="text-align:right;font-size:13px;font-weight:700;color:var(--cyan);"><?= number_format($p['qty'] ?? 0) ?></td>
                        <td style="text-align:right;font-size:13px;font-weight:700;color:var(--green);">LKR <?= number_format($p['revenue'] ?? 0, 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<?php $dailyJson = json_encode(array_values($daily)); $extraScript = <<<JS
<script>
(function() {
  var canvas = document.getElementById('salesChart');
  if (!canvas) return;
  var data = {$dailyJson};
  if (!data.length) return;
  var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  var gridColor = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.06)';
  var textColor = isDark ? '#9ca3af' : '#6b7280';
  new Chart(canvas, {
    type: 'line',
    data: {
      labels: data.map(function(d) { return d.day; }),
      datasets: [
        {
          label: 'Revenue (LKR)',
          data: data.map(function(d) { return parseFloat(d.revenue || 0); }),
          borderColor: '#6a7964', backgroundColor: 'rgba(106,121,100,.08)',
          fill: true, tension: .35, pointRadius: 3, yAxisID: 'y'
        },
        {
          label: 'Orders',
          data: data.map(function(d) { return parseInt(d.orders || 0); }),
          borderColor: '#ffb83c', backgroundColor: 'transparent',
          tension: .35, pointRadius: 3, yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: true,
      plugins: { legend: { position: 'top', labels: { color: textColor, boxWidth: 12, padding: 16 } } },
      scales: {
        x:  { ticks: { color: textColor, maxRotation: 45, font: { size: 11 } }, grid: { color: gridColor } },
        y:  { type: 'linear', position: 'left',  beginAtZero: true, ticks: { color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
        y1: { type: 'linear', position: 'right', beginAtZero: true, ticks: { color: '#ffb83c', font: { size: 11 } }, grid: { drawOnChartArea: false } }
      }
    }
  });
})();
</script>
JS;
?>
