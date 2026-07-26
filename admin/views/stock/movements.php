<?php /* admin/views/stock/movements.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   STOCK MOVEMENTS — RESPONSIVE
   Scoped to #movementsTable to avoid layout bleed.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Scroll wrapper ──────────────────────────────────────
   Prevents the .admin-table overflow:hidden from clipping
   horizontal scroll; wrapper scrolls instead.               */
.movements-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
}
.movements-table-wrap::-webkit-scrollbar       { height: 4px; }
.movements-table-wrap::-webkit-scrollbar-track { background: transparent; }
.movements-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Give the table a floor width so it never collapses below
   readable column widths on tablets                         */
@media (min-width: 768px) {
  #movementsTable { min-width: 860px; }
}

/* ── 2. Tablet (768–1100px): reduce text sizes slightly ─── */
@media (min-width: 768px) and (max-width: 1100px) {
  #movementsTable .table tbody td { font-size: 11px; padding: 8px 10px; }
  #movementsTable .table thead th { padding: 9px 10px; font-size: 10.5px; }
}

/* ── 3. Mobile (< 768px): table → stacked card layout ───── */
@media (max-width: 767px) {

  /* Release overflow:hidden so cards aren't clipped         */
  .movements-table-wrap .admin-table { overflow: visible !important; }

  #movementsTable { min-width: 0 !important; }

  /* Hide column headers */
  #movementsTable thead { display: none; }

  /* Each <tr> becomes a 3-column card grid:
     Col A (1fr)  : Product name + variation + notes
     Col B (auto) : Qty numbers (before → after)
     Col C (auto) : Type badge + date                        */
  #movementsTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto auto;
    column-gap: 10px;
    row-gap: 3px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
  }
  #movementsTable tbody tr:last-child { border-bottom: none; }
  #movementsTable tbody tr:hover td  { background: transparent !important; }

  /* Strip all td defaults */
  #movementsTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* td1: Product name — col A, row 1 */
  #movementsTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1;
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    align-self: center;
  }

  /* td2: Variation — col A, row 2 (small muted) */
  #movementsTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td3: Type badge — col C, row 1 */
  #movementsTable tbody td:nth-child(3) {
    grid-column: 3;
    grid-row: 1;
    align-self: center;
    text-align: right;
  }

  /* td4: Qty change — col B, row 1-2 (large number) */
  #movementsTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 1 / 3;
    align-self: center;
    text-align: right;
    font-size: 20px !important;
    font-weight: 800 !important;
    line-height: 1;
  }

  /* td5: Before qty — col B, row 3 */
  #movementsTable tbody td:nth-child(5) {
    grid-column: 2;
    grid-row: 3;
    text-align: right;
    font-size: 10px;
    color: var(--text-muted);
  }
  #movementsTable tbody td:nth-child(5)::before { content: 'was: '; }

  /* td6: After qty — col C, row 2 */
  #movementsTable tbody td:nth-child(6) {
    grid-column: 3;
    grid-row: 2;
    text-align: right;
    font-size: 11px;
    font-weight: 600;
  }
  #movementsTable tbody td:nth-child(6)::before {
    content: '→ ';
    color: var(--text-muted);
    font-weight: 400;
  }

  /* td7: Warehouse — col A, row 3 */
  #movementsTable tbody td:nth-child(7) {
    grid-column: 1;
    grid-row: 3;
    font-size: 10px;
    color: var(--text-dim);
  }
  #movementsTable tbody td:nth-child(7)::before {
    content: '📦 ';
    font-size: 9px;
  }

  /* td8: Reference — hide (too detailed for mobile) */
  #movementsTable tbody td:nth-child(8) { display: none !important; }

  /* td9: Notes — col A, row 4 */
  #movementsTable tbody td:nth-child(9) {
    grid-column: 1 / 3;
    grid-row: 4;
    font-size: 10px;
    color: var(--text-muted);
    font-style: italic;
  }

  /* td10: Created by — hide on mobile */
  #movementsTable tbody td:nth-child(10) { display: none !important; }

  /* td11: Date — col C, rows 3-4 */
  #movementsTable tbody td:nth-child(11) {
    grid-column: 3;
    grid-row: 3 / 5;
    align-self: center;
    text-align: right;
    font-size: 10px;
    color: var(--text-muted);
    white-space: nowrap;
  }

  /* Empty state: bypass grid */
  #movementsTable tbody tr.mvt-empty-row {
    display: block !important;
    border-bottom: none;
  }
  #movementsTable tbody tr.mvt-empty-row td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── 4. Very small phones (< 380 px) ───────────────────── */
@media (max-width: 379px) {
  #movementsTable tbody tr {
    grid-template-columns: 1fr auto 52px;
    column-gap: 7px;
    padding: 10px 10px;
  }
  #movementsTable tbody td:nth-child(4) { font-size: 17px !important; }
}

/* ── 5. Page-header stacks on very small phones ─────────── */
@media (max-width: 480px) {
  .mvt-page-header {
    flex-direction: column;
    align-items: flex-start !important;
  }
  .mvt-page-header > a {
    width: 100%;
    justify-content: center;
  }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mvt-page-header">
  <div>
    <h4>Stock Movement History</h4>
    <?php if (!empty($filterProduct)): ?>
    <p class="page-subtitle">
      Filtered by: <strong style="color:var(--text);"><?= e($filterProduct['name']) ?></strong>
      &nbsp;<a href="<?= url('admin/stock/movements') ?>" style="color:var(--cyan);font-size:12px;">Clear filter</a>
    </p>
    <?php else: ?>
    <p class="page-subtitle">Full audit trail of all inventory changes.</p>
    <?php endif; ?>
  </div>
  <a href="<?= url('admin/stock') ?>"
     class="btn btn-sm"
     style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);">
    <i class="fa fa-arrow-left me-1"></i>Back to Stock
  </a>
</div>

<div class="movements-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="movementsTable">
    <thead>
      <tr>
        <th>Product</th>
        <th style="width:90px;">Variation</th>
        <th style="width:110px;">Type</th>
        <th style="width:70px;text-align:right;">Qty</th>
        <th style="width:70px;text-align:right;">Before</th>
        <th style="width:70px;text-align:right;">After</th>
        <th style="width:100px;">Warehouse</th>
        <th style="width:110px;">Reference</th>
        <th style="width:160px;">Notes</th>
        <th style="width:80px;">By</th>
        <th style="width:120px;">Date</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $typeStyles = [
      'IN'         => ['bg'=>'var(--green-dim)',  'color'=>'var(--green)',  'sign'=>'+'],
      'OUT'        => ['bg'=>'var(--red-dim)',    'color'=>'var(--red)',    'sign'=>'-'],
      'ADJUSTMENT' => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)',  'sign'=>'±'],
      'RETURN'     => ['bg'=>'var(--cyan-dim)',   'color'=>'var(--cyan)',   'sign'=>'+'],
      'TRANSFER'   => ['bg'=>'var(--surface2)',   'color'=>'var(--text-dim)', 'sign'=>'→'],
    ];
    foreach ($movements as $m):
      $ts   = $typeStyles[$m['type']] ?? ['bg'=>'var(--surface2)','color'=>'var(--text-dim)','sign'=>''];
      $qty  = abs((int)$m['quantity']);
      $isNeg = in_array($m['type'], ['OUT']);
    ?>
    <tr>
      <!-- td1: Product -->
      <td>
        <span style="font-size:12px;font-weight:600;color:var(--text);">
          <?= e(Helper::truncate($m['product_name'], 34)) ?>
        </span>
      </td>
      <!-- td2: Variation -->
      <td style="font-size:11px;color:var(--text-muted);"><?= e($m['variation_name'] ?? '—') ?></td>
      <!-- td3: Type badge -->
      <td>
        <span class="badge" style="background:<?= $ts['bg'] ?>;color:<?= $ts['color'] ?>;">
          <?= e($m['type']) ?>
        </span>
      </td>
      <!-- td4: Qty change -->
      <td style="text-align:right;font-weight:700;font-size:13px;color:<?= $isNeg ? 'var(--red)' : 'var(--green)' ?>;">
        <?= $ts['sign'] ?><?= $qty ?>
      </td>
      <!-- td5: Before -->
      <td style="text-align:right;font-size:12px;color:var(--text-muted);"><?= (int)$m['quantity_before'] ?></td>
      <!-- td6: After -->
      <td style="text-align:right;font-weight:600;font-size:13px;"><?= (int)$m['quantity_after'] ?></td>
      <!-- td7: Warehouse -->
      <td style="font-size:12px;color:var(--text-dim);"><?= e($m['warehouse']) ?></td>
      <!-- td8: Reference -->
      <td style="font-size:11px;color:var(--text-muted);">
        <?php if (!empty($m['reference_type'])): ?>
        <span><?= e($m['reference_type']) ?><?= $m['reference_id'] ? ' #'.$m['reference_id'] : '' ?></span>
        <?php else: ?>—<?php endif; ?>
      </td>
      <!-- td9: Notes -->
      <td style="font-size:11px;color:var(--text-dim);"><?= e(Helper::truncate($m['notes'] ?? '', 45)) ?></td>
      <!-- td10: Created by -->
      <td style="font-size:11px;color:var(--text-muted);"><?= e($m['created_by_name'] ?? 'System') ?></td>
      <!-- td11: Date -->
      <td style="font-size:11px;color:var(--text-muted);white-space:nowrap;">
        <?= date('M j, Y', strtotime($m['created_at'])) ?>
        <div style="font-size:10px;opacity:.7;"><?= date('H:i', strtotime($m['created_at'])) ?></div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($movements)): ?>
    <tr class="mvt-empty-row">
      <td colspan="11" class="text-center py-5" style="color:var(--text-muted);">No movement records found.</td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.movements-table-wrap -->
