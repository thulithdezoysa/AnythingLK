<?php /* promotions/coupons.php — Unified Discount Management */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   DISCOUNTS & COUPONS — RESPONSIVE
   All selectors scoped to .dc-* / #cpnTable / #pdTable /
   #gdTable / #bogoTable to avoid bleed to other pages.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header ─────────────────────────────────────────*/
@media (max-width: 480px) {
  .dc-page-header { flex-direction: column; align-items: flex-start !important; }
}

/* ── 2. Tab bar: horizontal scroll on mobile ────────────────
   The admin layout sets .nav { flex-wrap: wrap } which causes
   4 tabs to wrap to 2 rows on phones. A scroll wrapper with
   nowrap keeps all tabs on one line.                         */
.dc-tabs-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  /* fade right edge to hint at scroll */
  -webkit-mask-image: linear-gradient(to right, #000 88%, transparent 100%);
          mask-image: linear-gradient(to right, #000 88%, transparent 100%);
}
.dc-tabs-wrap::-webkit-scrollbar { display: none; }
.dc-tabs-wrap .nav-tabs {
  flex-wrap: nowrap !important;
  width: max-content;
  min-width: 100%;
}
.dc-tabs-wrap .nav-tabs .nav-link { white-space: nowrap; }

/* ── 3. Tab-content card: release overflow ──────────────────
   .card has overflow:hidden for border-radius clipping.
   We override it here so the inner scroll wrappers can work.
   Corner clipping is re-applied via the scroll wrappers.    */
.dc-tab-content { overflow: visible !important; }

/* ── 4. Table scroll wrappers ───────────────────────────────
   Each table gets its own wrapper that owns horizontal scroll
   and re-clips the bottom corners with border-radius.       */
.dc-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
  border-radius: 0 0 var(--radius) var(--radius);
}
.dc-table-wrap::-webkit-scrollbar       { height: 4px; }
.dc-table-wrap::-webkit-scrollbar-track { background: transparent; }
.dc-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Enforce min-width on tablet+ so columns stay readable */
@media (min-width: 768px) {
  #cpnTable  { min-width: 720px; }
  #pdTable   { min-width: 680px; }
  #gdTable   { min-width: 760px; }
  #bogoTable { min-width: 700px; }
}

/* ── 5. "New X" button: stretch full-width on very small phones */
@media (max-width: 479px) {
  .dc-add-btn-wrap { justify-content: stretch !important; }
  .dc-add-btn-wrap .btn { width: 100%; }
}

/* ── 6. Mobile card layouts (< 768px) ──────────────────────
   All 4 tables collapse into stacked card rows.
   Shared structure: 3-col grid (content | badge col | actions)
   Table-specific nth-child rules handle per-table placement. */
@media (max-width: 767px) {

  /* Release .admin-table overflow so cards breathe */
  .dc-table-wrap .admin-table { overflow: visible !important; }

  /* All 4 tables: no forced min-width */
  #cpnTable, #pdTable, #gdTable, #bogoTable { min-width: 0 !important; }

  /* Hide column headers */
  #cpnTable thead, #pdTable thead,
  #gdTable thead, #bogoTable thead { display: none; }

  /* Each tr = 3-column card grid
     Col A (1fr)  : primary content (name/code/value)
     Col B (auto) : badges and secondary values
     Col C (auto) : action buttons (edit + delete)          */
  #cpnTable tbody tr,
  #pdTable tbody tr,
  #gdTable tbody tr,
  #bogoTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto;
    column-gap: 10px;
    row-gap: 5px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
  }
  #cpnTable tbody tr:last-child,
  #pdTable tbody tr:last-child,
  #gdTable tbody tr:last-child,
  #bogoTable tbody tr:last-child { border-bottom: none; }

  /* Strip all td defaults */
  #cpnTable tbody td,
  #pdTable tbody td,
  #gdTable tbody td,
  #bogoTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* ── Coupon table (#cpnTable) cell placement ──────────────
     Cols: Code(1) Type(2) Value(3) MinOrd(4·hide)
           Used/Limit(5) ValidUntil(6) Excl(7·hide) Status(8) Actions(9) */
  #cpnTable tbody td:nth-child(1) { /* Code */
    grid-column: 1; grid-row: 1; align-self: center;
    font-size: 13px; font-weight: 700;
  }
  #cpnTable tbody td:nth-child(2) { /* Type badge */
    grid-column: 2; grid-row: 1; align-self: center; text-align: right;
  }
  #cpnTable tbody td:nth-child(3) { /* Value */
    grid-column: 1; grid-row: 2; font-size: 12px;
  }
  #cpnTable tbody td:nth-child(3)::before {
    content: 'Value: '; font-size: 10px; color: var(--text-muted);
  }
  #cpnTable tbody td:nth-child(4) { display: none !important; } /* Min Order */
  #cpnTable tbody td:nth-child(5) { /* Used / Limit */
    grid-column: 2; grid-row: 3; font-size: 11px;
    color: var(--text-muted); text-align: right; align-self: center;
  }
  #cpnTable tbody td:nth-child(6) { /* Valid Until */
    grid-column: 1; grid-row: 3; font-size: 11px; color: var(--text-muted); align-self: center;
  }
  #cpnTable tbody td:nth-child(6)::before { content: 'Exp: '; font-size: 10px; }
  #cpnTable tbody td:nth-child(7) { display: none !important; } /* Excluded */
  #cpnTable tbody td:nth-child(8) { /* Status badge */
    grid-column: 2; grid-row: 2; align-self: center; text-align: right;
  }
  #cpnTable tbody td:nth-child(9) { /* Actions */
    grid-column: 3; grid-row: 1 / 4; align-self: center;
    display: flex !important; flex-direction: column; gap: 4px; align-items: center;
  }

  /* ── Product Discount table (#pdTable) cell placement ─────
     Cols: Product(1) Label(2) Type(3) Value(4)
           Dates(5) Excl(6·hide) Status(7) Actions(8)         */
  #pdTable tbody td:nth-child(1) { /* Product name */
    grid-column: 1; grid-row: 1; font-size: 12px; font-weight: 600; align-self: center;
  }
  #pdTable tbody td:nth-child(2) { /* Label */
    grid-column: 1; grid-row: 2; font-size: 11px; color: var(--text-muted);
  }
  #pdTable tbody td:nth-child(3) { /* Type badge */
    grid-column: 2; grid-row: 1; align-self: center; text-align: right;
  }
  #pdTable tbody td:nth-child(4) { /* Value */
    grid-column: 1; grid-row: 3; font-size: 12px; align-self: center;
  }
  #pdTable tbody td:nth-child(4)::before {
    content: 'Value: '; font-size: 10px; color: var(--text-muted);
  }
  #pdTable tbody td:nth-child(5) { /* Dates */
    grid-column: 2; grid-row: 3; font-size: 11px;
    color: var(--text-muted); text-align: right; align-self: center; white-space: nowrap;
  }
  #pdTable tbody td:nth-child(6) { display: none !important; } /* Excluded */
  #pdTable tbody td:nth-child(7) { /* Status badge */
    grid-column: 2; grid-row: 2; align-self: center; text-align: right;
  }
  #pdTable tbody td:nth-child(8) { /* Actions */
    grid-column: 3; grid-row: 1 / 4; align-self: center;
    display: flex !important; flex-direction: column; gap: 4px; align-items: center;
  }

  /* ── Global Discount table (#gdTable) cell placement ───────
     Cols: Name(1) Type(2) Value(3) MinOrd(4·hide)
           MaxDisc(5·hide) Priority(6) ValidUntil(7) Status(8) Actions(9) */
  #gdTable tbody td:nth-child(1) { /* Name */
    grid-column: 1; grid-row: 1; font-size: 12px; font-weight: 600; align-self: center;
  }
  #gdTable tbody td:nth-child(2) { /* Type badge */
    grid-column: 2; grid-row: 1; align-self: center; text-align: right;
  }
  #gdTable tbody td:nth-child(3) { /* Value */
    grid-column: 1; grid-row: 2; font-size: 12px; align-self: center;
  }
  #gdTable tbody td:nth-child(3)::before {
    content: 'Value: '; font-size: 10px; color: var(--text-muted);
  }
  #gdTable tbody td:nth-child(4) { display: none !important; } /* Min Order */
  #gdTable tbody td:nth-child(5) { display: none !important; } /* Max Discount */
  #gdTable tbody td:nth-child(6) { /* Priority */
    grid-column: 1; grid-row: 3; font-size: 11px; color: var(--text-muted); align-self: center;
  }
  #gdTable tbody td:nth-child(6)::before { content: 'Priority: '; font-size: 10px; }
  #gdTable tbody td:nth-child(7) { /* Valid Until */
    grid-column: 2; grid-row: 3; font-size: 11px;
    color: var(--text-muted); text-align: right; align-self: center;
  }
  #gdTable tbody td:nth-child(8) { /* Status badge */
    grid-column: 2; grid-row: 2; align-self: center; text-align: right;
  }
  #gdTable tbody td:nth-child(9) { /* Actions */
    grid-column: 3; grid-row: 1 / 4; align-self: center;
    display: flex !important; flex-direction: column; gap: 4px; align-items: center;
  }

  /* ── BOGO table (#bogoTable) cell placement ─────────────────
     Cols: Name(1) Buy(2) Get(3) Disc%(4) ValidUntil(5) Status(6) Actions(7) */
  #bogoTable tbody td:nth-child(1) { /* Name */
    grid-column: 1; grid-row: 1; font-size: 12px; font-weight: 600; align-self: center;
  }
  #bogoTable tbody td:nth-child(2) { /* Buy */
    grid-column: 1; grid-row: 2; font-size: 11px; color: var(--text-dim);
  }
  #bogoTable tbody td:nth-child(2)::before {
    content: 'Buy: '; font-size: 10px; color: var(--text-muted);
  }
  #bogoTable tbody td:nth-child(3) { /* Get */
    grid-column: 1; grid-row: 3; font-size: 11px; color: var(--text-dim);
  }
  #bogoTable tbody td:nth-child(3)::before {
    content: 'Get: '; font-size: 10px; color: var(--text-muted);
  }
  #bogoTable tbody td:nth-child(4) { /* Discount% badge */
    grid-column: 2; grid-row: 1; align-self: center; text-align: right;
  }
  #bogoTable tbody td:nth-child(5) { /* Valid Until */
    grid-column: 2; grid-row: 3; font-size: 11px;
    color: var(--text-muted); text-align: right; align-self: center;
  }
  #bogoTable tbody td:nth-child(6) { /* Status */
    grid-column: 2; grid-row: 2; align-self: center; text-align: right;
  }
  #bogoTable tbody td:nth-child(7) { /* Actions */
    grid-column: 3; grid-row: 1 / 4; align-self: center;
    display: flex !important; flex-direction: column; gap: 4px; align-items: center;
  }

  /* Empty state: bypass grid */
  .dc-empty-row { display: block !important; border-bottom: none !important; }
  .dc-empty-row td {
    display: block !important;
    padding: 32px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── 7. Very small phones (< 380px) ────────────────────────*/
@media (max-width: 379px) {
  #cpnTable tbody tr, #pdTable tbody tr,
  #gdTable tbody tr, #bogoTable tbody tr {
    grid-template-columns: 1fr auto 52px;
    column-gap: 7px;
    padding: 10px 10px;
  }
}

/* ── 8. Coupon modal: tighter body padding on very small phones ─*/
@media (max-width: 479px) {
  #couponModal .modal-body,
  #prodDiscModal .modal-body,
  #globalModal .modal-body,
  #bogoModal .modal-body { padding: 14px 14px; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 dc-page-header">
    <h4 class="mb-0">Discounts &amp; Coupons</h4>
</div>

<!-- Nav tabs — wrapped for horizontal scroll on mobile -->
<div class="dc-tabs-wrap">
  <ul class="nav nav-tabs mb-0" id="discountTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-coupons" type="button">
            <i class="fa fa-tag me-1"></i>Coupons
            <?php if (!empty($coupons)): ?><span class="badge bg-secondary ms-1"><?= count($coupons) ?></span><?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-product" type="button">
            <i class="fa fa-box me-1"></i>Product
            <?php if (!empty($productDiscounts)): ?><span class="badge bg-secondary ms-1"><?= count($productDiscounts) ?></span><?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-global" type="button">
            <i class="fa fa-globe me-1"></i>Global
            <?php if (!empty($globalDiscounts)): ?><span class="badge bg-secondary ms-1"><?= count($globalDiscounts) ?></span><?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bogo" type="button">
            <i class="fa fa-gift me-1"></i>BOGO
            <?php if (!empty($bogoOffers)): ?><span class="badge bg-secondary ms-1"><?= count($bogoOffers) ?></span><?php endif; ?>
        </button>
    </li>
  </ul>
</div>

<div class="tab-content card dc-tab-content border-top-0 rounded-0 rounded-bottom p-3">

    <!-- ══════════════════ TAB 1: COUPONS ══════════════════ -->
    <div class="tab-pane fade show active" id="tab-coupons" role="tabpanel">
        <div class="d-flex justify-content-end mb-3 dc-add-btn-wrap">
            <button class="btn btn-primary btn-sm" id="addCouponBtn">
                <i class="fa fa-plus me-1"></i>New Coupon
            </button>
        </div>
        <div class="dc-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="cpnTable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th style="width:100px;">Type</th>
                        <th style="width:110px;">Value</th>
                        <th style="width:110px;">Min Order</th>
                        <th style="width:90px;">Used / Limit</th>
                        <th style="width:100px;">Valid Until</th>
                        <th style="width:110px;">Excluded</th>
                        <th style="width:75px;">Status</th>
                        <th style="width:70px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($coupons as $c): ?>
                <tr>
                    <td><code class="fw-bold"><?= e($c['code']) ?></code></td>
                    <td><span class="badge" style="background:var(--cyan-dim);color:var(--cyan)"><?= ucfirst($c['type']) ?></span></td>
                    <td><?= $c['type']==='percentage' ? $c['value'].'%' : 'LKR '.number_format($c['value'],2) ?></td>
                    <td class="small text-muted"><?= $c['min_order']>0 ? 'LKR '.number_format($c['min_order'],2) : '—' ?></td>
                    <td class="small"><?= (int)$c['usage_count'] ?> / <?= $c['usage_limit'] ?? '∞' ?></td>
                    <td class="small text-muted"><?= $c['end_date'] ? date('M j, Y', strtotime($c['end_date'])) : '—' ?></td>
                    <td class="small text-muted"><?= $c['excluded_methods'] ? e($c['excluded_methods']) : '—' ?></td>
                    <td><span class="badge <?= $c['status'] ? 'bg-success' : 'bg-secondary' ?>"><?= $c['status'] ? 'Active' : 'Off' ?></span></td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-secondary py-0 me-1 edit-coupon"
                            data-row='<?= htmlspecialchars(json_encode($c), ENT_QUOTES) ?>'>
                            <i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger py-0 del-coupon" data-id="<?= $c['id'] ?>">
                            <i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($coupons)): ?>
                <tr class="dc-empty-row"><td colspan="9" class="text-center text-muted py-4">No coupons yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div><!-- /.admin-table -->
        </div><!-- /.dc-table-wrap -->
    </div>

    <!-- ══════════════════ TAB 2: PRODUCT DISCOUNTS ══════════════════ -->
    <div class="tab-pane fade" id="tab-product" role="tabpanel">
        <div class="d-flex justify-content-end mb-3 dc-add-btn-wrap">
            <button class="btn btn-primary btn-sm" id="addProdDiscBtn">
                <i class="fa fa-plus me-1"></i>New Product Discount
            </button>
        </div>
        <div class="dc-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="pdTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width:120px;">Label</th>
                        <th style="width:100px;">Type</th>
                        <th style="width:110px;">Value</th>
                        <th style="width:130px;">Dates</th>
                        <th style="width:110px;">Excluded</th>
                        <th style="width:75px;">Status</th>
                        <th style="width:70px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($productDiscounts as $d): ?>
                <tr>
                    <td class="small fw-bold"><?= e($d['product_name'] ?? '—') ?></td>
                    <td class="small text-muted"><?= e($d['name'] ?? '—') ?></td>
                    <td><span class="badge" style="background:var(--amber-dim);color:var(--amber)"><?= ucfirst($d['type']) ?></span></td>
                    <td><?= $d['type']==='percentage' ? $d['value'].'%' : 'LKR '.number_format($d['value'],2) ?></td>
                    <td class="small text-muted">
                        <?= ($d['start_date'] ? date('M j', strtotime($d['start_date'])) : '') ?>
                        – <?= ($d['end_date'] ? date('M j, Y', strtotime($d['end_date'])) : '∞') ?>
                    </td>
                    <td class="small text-muted"><?= $d['excluded_methods'] ? e($d['excluded_methods']) : '—' ?></td>
                    <td><span class="badge <?= $d['status'] ? 'bg-success' : 'bg-secondary' ?>"><?= $d['status'] ? 'Active' : 'Off' ?></span></td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-secondary py-0 me-1 edit-prod-disc"
                            data-row='<?= htmlspecialchars(json_encode($d), ENT_QUOTES) ?>'>
                            <i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger py-0 del-prod-disc" data-id="<?= $d['id'] ?>">
                            <i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($productDiscounts)): ?>
                <tr class="dc-empty-row"><td colspan="8" class="text-center text-muted py-4">No product discounts yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div><!-- /.admin-table -->
        </div><!-- /.dc-table-wrap -->
    </div>

    <!-- ══════════════════ TAB 3: GLOBAL DISCOUNTS ══════════════════ -->
    <div class="tab-pane fade" id="tab-global" role="tabpanel">
        <div class="d-flex justify-content-end mb-3 dc-add-btn-wrap">
            <button class="btn btn-primary btn-sm" id="addGlobalBtn">
                <i class="fa fa-plus me-1"></i>New Global Discount
            </button>
        </div>
        <div class="dc-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="gdTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th style="width:100px;">Type</th>
                        <th style="width:110px;">Value</th>
                        <th style="width:110px;">Min Order</th>
                        <th style="width:110px;">Max Disc.</th>
                        <th style="width:70px;">Priority</th>
                        <th style="width:100px;">Valid Until</th>
                        <th style="width:75px;">Status</th>
                        <th style="width:70px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($globalDiscounts as $g): ?>
                <tr>
                    <td class="fw-bold small"><?= e($g['name']) ?></td>
                    <td><span class="badge" style="background:var(--green-dim);color:var(--green)"><?= ucfirst($g['type']) ?></span></td>
                    <td><?= $g['type']==='percentage' ? $g['value'].'%' : 'LKR '.number_format($g['value'],2) ?></td>
                    <td class="small text-muted"><?= $g['min_order']>0 ? 'LKR '.number_format($g['min_order'],2) : '—' ?></td>
                    <td class="small text-muted"><?= $g['max_discount']>0 ? 'LKR '.number_format($g['max_discount'],2) : '—' ?></td>
                    <td><?= (int)$g['priority'] ?></td>
                    <td class="small text-muted"><?= $g['end_date'] ? date('M j, Y', strtotime($g['end_date'])) : '—' ?></td>
                    <td><span class="badge <?= $g['status'] ? 'bg-success' : 'bg-secondary' ?>"><?= $g['status'] ? 'Active' : 'Off' ?></span></td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-secondary py-0 me-1 edit-global"
                            data-row='<?= htmlspecialchars(json_encode($g), ENT_QUOTES) ?>'>
                            <i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger py-0 del-global" data-id="<?= $g['id'] ?>">
                            <i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($globalDiscounts)): ?>
                <tr class="dc-empty-row"><td colspan="9" class="text-center text-muted py-4">No global discounts yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div><!-- /.admin-table -->
        </div><!-- /.dc-table-wrap -->
    </div>

    <!-- ══════════════════ TAB 4: BOGO ══════════════════ -->
    <div class="tab-pane fade" id="tab-bogo" role="tabpanel">
        <div class="d-flex justify-content-end mb-3 dc-add-btn-wrap">
            <button class="btn btn-primary btn-sm" id="addBogoBtn">
                <i class="fa fa-plus me-1"></i>New BOGO Offer
            </button>
        </div>
        <div class="dc-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="bogoTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th style="width:160px;">Buy</th>
                        <th style="width:160px;">Get</th>
                        <th style="width:90px;">Discount %</th>
                        <th style="width:100px;">Valid Until</th>
                        <th style="width:75px;">Status</th>
                        <th style="width:70px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($bogoOffers as $b): ?>
                <tr>
                    <td class="fw-bold small"><?= e($b['name']) ?></td>
                    <td class="small"><?= (int)$b['buy_qty'] ?> × <span class="text-muted"><?= e($b['buy_product_name'] ?? 'Any') ?></span></td>
                    <td class="small"><?= (int)$b['get_qty'] ?> × <span class="text-muted"><?= e($b['get_product_name'] ?? 'Same') ?></span></td>
                    <td><span class="badge bg-warning text-dark"><?= (int)$b['get_discount_pct'] ?>% off</span></td>
                    <td class="small text-muted"><?= $b['end_date'] ? date('M j, Y', strtotime($b['end_date'])) : '—' ?></td>
                    <td><span class="badge <?= $b['status'] ? 'bg-success' : 'bg-secondary' ?>"><?= $b['status'] ? 'Active' : 'Off' ?></span></td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-sm btn-outline-secondary py-0 me-1 edit-bogo"
                            data-row='<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>'>
                            <i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger py-0 del-bogo" data-id="<?= $b['id'] ?>">
                            <i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($bogoOffers)): ?>
                <tr class="dc-empty-row"><td colspan="7" class="text-center text-muted py-4">No BOGO offers yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div><!-- /.admin-table -->
        </div><!-- /.dc-table-wrap -->
    </div>

</div><!-- /tab-content -->

<!-- ══════════════════════════════════════════════════════════
     COUPON MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="couponModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalTitle">New Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="couponForm">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="couponId">
                <div class="modal-body row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Code *</label>
                        <input type="text" name="code" id="couponCode" class="form-control text-uppercase"
                               required placeholder="SUMMER20" autocomplete="off">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Type</label>
                        <select name="type" id="couponType" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed (LKR)</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Value *</label>
                        <input type="number" name="value" id="couponValue" class="form-control"
                               step="0.01" min="0" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Min Order (LKR)</label>
                        <input type="number" name="min_order" id="couponMinOrder" class="form-control"
                               step="0.01" min="0" value="0">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Max Discount (LKR)</label>
                        <input type="number" name="max_discount" id="couponMaxDisc" class="form-control"
                               step="0.01" min="0" placeholder="No limit">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" id="couponUsageLimit" class="form-control"
                               min="1" placeholder="Unlimited">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Per User Limit</label>
                        <input type="number" name="per_user_limit" id="couponPerUser" class="form-control"
                               min="1" value="1">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Status</label>
                        <select name="status" id="couponStatus" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="couponStart" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="couponEnd" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Exclude from payment methods</label>
                        <div class="d-flex gap-3 flex-wrap mt-1">
                            <div class="form-check">
                                <input class="form-check-input c-excl" type="checkbox" value="koko" id="cExclKoko">
                                <label class="form-check-label" for="cExclKoko">KOKO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input c-excl" type="checkbox" value="payzy" id="cExclPayzy">
                                <label class="form-check-label" for="cExclPayzy">Payzy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input c-excl" type="checkbox" value="mintpay" id="cExclMintpay">
                                <label class="form-check-label" for="cExclMintpay">MintPay</label>
                            </div>
                        </div>
                        <input type="hidden" name="excluded_methods" id="couponExcl">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="couponSubmitBtn">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     PRODUCT DISCOUNT MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="prodDiscModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prodDiscModalTitle">New Product Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="prodDiscForm">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="prodDiscId">
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Product *</label>
                        <select name="product_id" id="prodDiscProduct" class="form-select" required>
                            <option value="">— Select product —</option>
                            <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Discount Label</label>
                        <input type="text" name="name" id="prodDiscName" class="form-control"
                               placeholder="e.g. Summer Sale">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Type</label>
                        <select name="type" id="prodDiscType" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed (LKR)</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Value *</label>
                        <input type="number" name="value" id="prodDiscValue" class="form-control"
                               step="0.01" min="0" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="prodDiscStart" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="prodDiscEnd" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Status</label>
                        <select name="status" id="prodDiscStatus" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Exclude from payment methods</label>
                        <div class="d-flex gap-3 flex-wrap mt-1">
                            <div class="form-check">
                                <input class="form-check-input pd-excl" type="checkbox" value="koko" id="pdExclKoko">
                                <label class="form-check-label" for="pdExclKoko">KOKO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input pd-excl" type="checkbox" value="payzy" id="pdExclPayzy">
                                <label class="form-check-label" for="pdExclPayzy">Payzy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input pd-excl" type="checkbox" value="mintpay" id="pdExclMintpay">
                                <label class="form-check-label" for="pdExclMintpay">MintPay</label>
                            </div>
                        </div>
                        <input type="hidden" name="excluded_methods" id="prodDiscExcl">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Discount</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     GLOBAL DISCOUNT MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="globalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="globalModalTitle">New Global Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="globalForm">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="globalId">
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" id="globalName" class="form-control"
                               required placeholder="e.g. Festive Season Sale">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Type</label>
                        <select name="type" id="globalType" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed (LKR)</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Value *</label>
                        <input type="number" name="value" id="globalValue" class="form-control"
                               step="0.01" min="0" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Min Order (LKR)</label>
                        <input type="number" name="min_order" id="globalMinOrder" class="form-control"
                               step="0.01" min="0" value="0">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Max Discount (LKR)</label>
                        <input type="number" name="max_discount" id="globalMaxDisc" class="form-control"
                               step="0.01" min="0" placeholder="No limit">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Priority</label>
                        <input type="number" name="priority" id="globalPriority" class="form-control"
                               min="0" value="0">
                        <div class="form-text">Higher number = applied first</div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Status</label>
                        <select name="status" id="globalStatus" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="globalStart" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="globalEnd" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Exclude from payment methods</label>
                        <div class="d-flex gap-3 flex-wrap mt-1">
                            <div class="form-check">
                                <input class="form-check-input g-excl" type="checkbox" value="koko" id="gExclKoko">
                                <label class="form-check-label" for="gExclKoko">KOKO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input g-excl" type="checkbox" value="payzy" id="gExclPayzy">
                                <label class="form-check-label" for="gExclPayzy">Payzy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input g-excl" type="checkbox" value="mintpay" id="gExclMintpay">
                                <label class="form-check-label" for="gExclMintpay">MintPay</label>
                            </div>
                        </div>
                        <input type="hidden" name="excluded_methods" id="globalExcl">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Discount</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     BOGO MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="bogoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bogoModalTitle">New BOGO Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bogoForm">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="bogoId">
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Offer Name *</label>
                        <input type="text" name="name" id="bogoName" class="form-control"
                               required placeholder="e.g. Buy 2 Get 1 Free">
                    </div>
                    <div class="col-12 col-sm-8">
                        <label class="form-label">Buy Product <small class="text-muted">(leave blank = any)</small></label>
                        <select name="buy_product_id" id="bogoBuyProduct" class="form-select">
                            <option value="">— Any product —</option>
                            <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">Buy Qty *</label>
                        <input type="number" name="buy_qty" id="bogoBuyQty" class="form-control"
                               min="1" value="1" required>
                    </div>
                    <div class="col-12 col-sm-8">
                        <label class="form-label">Get Product <small class="text-muted">(blank = same as buy)</small></label>
                        <select name="get_product_id" id="bogoGetProduct" class="form-select">
                            <option value="">— Same as buy —</option>
                            <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label class="form-label">Get Qty *</label>
                        <input type="number" name="get_qty" id="bogoGetQty" class="form-control"
                               min="1" value="1" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Discount on Get items % *</label>
                        <input type="number" name="get_discount_pct" id="bogoDisc" class="form-control"
                               min="1" max="100" value="100" required>
                        <div class="form-text">100 = completely free</div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Status</label>
                        <select name="status" id="bogoStatus" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="bogoStart" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="bogoEnd" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Exclude from payment methods</label>
                        <div class="d-flex gap-3 flex-wrap mt-1">
                            <div class="form-check">
                                <input class="form-check-input b-excl" type="checkbox" value="koko" id="bExclKoko">
                                <label class="form-check-label" for="bExclKoko">KOKO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input b-excl" type="checkbox" value="payzy" id="bExclPayzy">
                                <label class="form-check-label" for="bExclPayzy">Payzy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input b-excl" type="checkbox" value="mintpay" id="bExclMintpay">
                                <label class="form-check-label" for="bExclMintpay">MintPay</label>
                            </div>
                        </div>
                        <input type="hidden" name="excluded_methods" id="bogoExcl">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $extraScript = <<<'ENDJS'
<script>
(function () {

    // ── Helpers ──────────────────────────────────────────────────────

    /** Set checkboxes matching a comma-separated string */
    function setExcl(selector, val) {
        var methods = val ? val.split(',').map(function(s){ return s.trim(); }) : [];
        $(selector).each(function() {
            $(this).prop('checked', methods.indexOf($(this).val()) !== -1);
        });
    }

    /** Read checked checkboxes into hidden field */
    function syncExcl(selector, hiddenId) {
        var vals = [];
        $(selector + ':checked').each(function(){ vals.push($(this).val()); });
        $(hiddenId).val(vals.join(','));
    }

    /** Generic AJAX submit: store or update depending on hidden id */
    function handleSubmit(formId, storeUrl, updateUrl, modalId, exclSelector, hiddenExclId) {
        $(formId).on('submit', function(e) {
            e.preventDefault();
            syncExcl(exclSelector, hiddenExclId);
            var id  = $(formId + ' [name=id]').val();
            var url = id ? updateUrl : storeUrl;
            var btn = $(formId + ' [type=submit]');
            btn.prop('disabled', true);
            ajaxPost(url, $(formId).serialize(), function(res) {
                btn.prop('disabled', false);
                if (res.success) {
                    SwalDark.fire({ icon:'success', title:res.message, timer:1200, showConfirmButton:false })
                        .then(function(){ location.reload(); });
                } else {
                    SwalDark.fire({ icon:'error', title:'Error', text:res.message });
                }
            });
        });
    }

    /** Generic delete */
    function handleDelete(selector, deleteUrl, rowTarget) {
        $(document).on('click', selector, function() {
            var id  = $(this).data('id');
            var row = $(this).closest('tr');
            SwalDark.fire({
                title: 'Delete this entry?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                confirmButtonText: 'Delete'
            }).then(function(r) {
                if (!r.isConfirmed) return;
                ajaxPost(deleteUrl, { id: id, _csrf: CSRF_TOKEN }, function(res) {
                    if (res.success) {
                        row.fadeOut(300, function(){ $(this).remove(); });
                    } else {
                        SwalDark.fire({ icon:'error', title:'Error', text:res.message });
                    }
                });
            });
        });
    }

    // ── COUPONS ──────────────────────────────────────────────────────

    $('#addCouponBtn').on('click', function() {
        $('#couponModalTitle').text('New Coupon');
        $('#couponSubmitBtn').text('Create Coupon');
        $('#couponForm')[0].reset();
        $('#couponId').val('');
        setExcl('.c-excl', 'koko,payzy,mintpay');
        $('#couponModal').modal('show');
    });

    $(document).on('click', '.edit-coupon', function() {
        var r = $(this).data('row');
        $('#couponModalTitle').text('Edit Coupon');
        $('#couponSubmitBtn').text('Update Coupon');
        $('#couponId').val(r.id);
        $('#couponCode').val(r.code);
        $('#couponType').val(r.type);
        $('#couponValue').val(r.value);
        $('#couponMinOrder').val(r.min_order || 0);
        $('#couponMaxDisc').val(r.max_discount || '');
        $('#couponUsageLimit').val(r.usage_limit || '');
        $('#couponPerUser').val(r.per_user_limit || 1);
        $('#couponStatus').val(r.status);
        $('#couponStart').val(r.start_date || '');
        $('#couponEnd').val(r.end_date || '');
        setExcl('.c-excl', r.excluded_methods || '');
        $('#couponModal').modal('show');
    });

    handleSubmit('#couponForm', 'admin/coupons/coupon-store', 'admin/coupons/coupon-update',
        '#couponModal', '.c-excl', '#couponExcl');
    handleDelete('.del-coupon', 'admin/coupons/coupon-delete');

    // ── PRODUCT DISCOUNTS ─────────────────────────────────────────────

    $('#addProdDiscBtn').on('click', function() {
        $('#prodDiscModalTitle').text('New Product Discount');
        $('#prodDiscForm')[0].reset();
        $('#prodDiscId').val('');
        setExcl('.pd-excl', 'koko,payzy,mintpay');
        $('#prodDiscModal').modal('show');
    });

    $(document).on('click', '.edit-prod-disc', function() {
        var r = $(this).data('row');
        $('#prodDiscModalTitle').text('Edit Product Discount');
        $('#prodDiscId').val(r.id);
        $('#prodDiscProduct').val(r.product_id);
        $('#prodDiscName').val(r.name || '');
        $('#prodDiscType').val(r.type);
        $('#prodDiscValue').val(r.value);
        $('#prodDiscStart').val(r.start_date || '');
        $('#prodDiscEnd').val(r.end_date || '');
        $('#prodDiscStatus').val(r.status);
        setExcl('.pd-excl', r.excluded_methods || '');
        $('#prodDiscModal').modal('show');
    });

    handleSubmit('#prodDiscForm', 'admin/coupons/product-store', 'admin/coupons/product-update',
        '#prodDiscModal', '.pd-excl', '#prodDiscExcl');
    handleDelete('.del-prod-disc', 'admin/coupons/product-delete');

    // ── GLOBAL DISCOUNTS ─────────────────────────────────────────────

    $('#addGlobalBtn').on('click', function() {
        $('#globalModalTitle').text('New Global Discount');
        $('#globalForm')[0].reset();
        $('#globalId').val('');
        $('#globalPriority').val(0);
        setExcl('.g-excl', 'koko,payzy,mintpay');
        $('#globalModal').modal('show');
    });

    $(document).on('click', '.edit-global', function() {
        var r = $(this).data('row');
        $('#globalModalTitle').text('Edit Global Discount');
        $('#globalId').val(r.id);
        $('#globalName').val(r.name);
        $('#globalType').val(r.type);
        $('#globalValue').val(r.value);
        $('#globalMinOrder').val(r.min_order || 0);
        $('#globalMaxDisc').val(r.max_discount || '');
        $('#globalPriority').val(r.priority || 0);
        $('#globalStatus').val(r.status);
        $('#globalStart').val(r.start_date || '');
        $('#globalEnd').val(r.end_date || '');
        setExcl('.g-excl', r.excluded_methods || '');
        $('#globalModal').modal('show');
    });

    handleSubmit('#globalForm', 'admin/coupons/global-store', 'admin/coupons/global-update',
        '#globalModal', '.g-excl', '#globalExcl');
    handleDelete('.del-global', 'admin/coupons/global-delete');

    // ── BOGO OFFERS ───────────────────────────────────────────────────

    $('#addBogoBtn').on('click', function() {
        $('#bogoModalTitle').text('New BOGO Offer');
        $('#bogoForm')[0].reset();
        $('#bogoId').val('');
        $('#bogoBuyQty').val(1);
        $('#bogoGetQty').val(1);
        $('#bogoDisc').val(100);
        setExcl('.b-excl', 'koko,payzy,mintpay');
        $('#bogoModal').modal('show');
    });

    $(document).on('click', '.edit-bogo', function() {
        var r = $(this).data('row');
        $('#bogoModalTitle').text('Edit BOGO Offer');
        $('#bogoId').val(r.id);
        $('#bogoName').val(r.name);
        $('#bogoBuyProduct').val(r.buy_product_id || '');
        $('#bogoBuyQty').val(r.buy_qty || 1);
        $('#bogoGetProduct').val(r.get_product_id || '');
        $('#bogoGetQty').val(r.get_qty || 1);
        $('#bogoDisc').val(r.get_discount_pct || 100);
        $('#bogoStatus').val(r.status);
        $('#bogoStart').val(r.start_date || '');
        $('#bogoEnd').val(r.end_date || '');
        setExcl('.b-excl', r.excluded_methods || '');
        $('#bogoModal').modal('show');
    });

    handleSubmit('#bogoForm', 'admin/coupons/bogo-store', 'admin/coupons/bogo-update',
        '#bogoModal', '.b-excl', '#bogoExcl');
    handleDelete('.del-bogo', 'admin/coupons/bogo-delete');

})();
</script>
ENDJS;
?>
