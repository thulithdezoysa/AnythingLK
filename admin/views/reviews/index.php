<?php /* admin/views/reviews/index.php */
$statusStyles = [
    'pending'  => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
    'approved' => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
    'rejected' => ['bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
];
?>
<style>
/* ══ REVIEWS PAGE — RESPONSIVE ══════════════════════════════════
   All selectors scoped to .rv-* / #revTable to avoid bleed.    */

/* ── 1. Status filter pills ───────────────────────────────── */
.rev-filter-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
    text-decoration: none; border: 1.5px solid transparent;
    transition: border-color .15s, background .15s, color .15s;
    white-space: nowrap;
}
.rev-filter-pill.active {
    border-color: var(--cyan); background: var(--cyan-dim); color: var(--cyan);
}
.rev-filter-pill:not(.active) {
    background: var(--surface2); color: var(--text-dim); border-color: var(--border2);
}
.rev-filter-pill:not(.active):hover { border-color: var(--cyan); color: var(--cyan); }

/* ── 2. Page header layout ────────────────────────────────── */
.rv-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px 16px;
    margin-bottom: 16px;
}
.rv-page-header h4 { margin: 0; }
.rv-page-header .page-subtitle { margin: 4px 0 0; }
.rv-pills-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
}

/* ── 3. Review cell helpers ───────────────────────────────── */
.star-filled { color: #f59e0b; }
.star-empty  { color: var(--border2, #e5e7eb); }
.rev-body  { font-size: 12px; color: var(--text-dim); line-height: 1.5; margin-top: 3px; }
.rev-title { font-size: 13px; font-weight: 600; color: var(--text-dim); }

/* ── 4. Table scroll wrapper (tablet / desktop) ───────────── */
.rv-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
    border-radius: var(--radius);
}
.rv-table-wrap::-webkit-scrollbar       { height: 4px; }
.rv-table-wrap::-webkit-scrollbar-track { background: transparent; }
.rv-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 768px) {
    #revTable { min-width: 680px; }
}

/* ── 5. Action buttons ────────────────────────────────────── */
.rv-action-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    border: none; border-radius: 6px; cursor: pointer;
    min-width: 30px; min-height: 28px;
    padding: 3px 10px; font-size: 11px; font-weight: 600;
    transition: opacity .15s;
}
.rv-action-btn:hover   { opacity: .8; }
.rv-action-btn:disabled { opacity: .45; cursor: default; }
.rv-btn-approve { background: var(--green-dim); color: var(--green); }
.rv-btn-reject  { background: var(--red-dim);   color: var(--red);   }

/* Button text label: hidden on desktop, shown on mobile */
.rv-btn-label { display: none; }

/* ══ MOBILE CARD LAYOUT (≤ 767px) ═══════════════════════════
   7 columns in DOM order:
     (1) Product   (2) Customer  (3) Rating
     (4) Review    (5) Date      (6) Status  (7) Actions

   Card grid — 2 columns:
     Col A (1fr)  : product / customer / rating / review / actions
     Col B (auto) : status / date                                  */
@media (max-width: 767px) {

    /* Let cards sit flush — no horizontal scroll clip */
    .rv-table-wrap           { overflow-x: visible; }
    .rv-table-wrap .admin-table { overflow: visible !important; }

    /* Hide column headers */
    #revTable thead { display: none; }

    /* tr → 2-col card */
    #revTable tbody tr {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto auto auto;
        column-gap: 10px;
        row-gap: 4px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
    }
    #revTable tbody tr:last-child { border-bottom: none; }

    /* Strip td defaults */
    #revTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* ── Cell placement ──────────────────────────────────── */

    /* (1) Product — A1 */
    #revTable tbody td:nth-child(1) {
        grid-column: 1; grid-row: 1;
        align-self: center;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    #revTable tbody td:nth-child(1) a { font-size: 13px !important; }

    /* (2) Customer — A2 */
    #revTable tbody td:nth-child(2) {
        grid-column: 1; grid-row: 2;
        font-size: 11px !important;
        color: var(--text-dim);
        align-self: center;
    }

    /* (3) Rating — A3 (hide the numeric "x/5" sub-line) */
    #revTable tbody td:nth-child(3) {
        grid-column: 1; grid-row: 3;
        align-self: center;
    }
    #revTable tbody td:nth-child(3) > div:last-child { display: none; }

    /* (4) Review text — spans full width, row 4 */
    #revTable tbody td:nth-child(4) {
        grid-column: 1 / 3; grid-row: 4;
    }

    /* (5) Date — B2 */
    #revTable tbody td:nth-child(5) {
        grid-column: 2; grid-row: 2;
        font-size: 10px !important;
        color: var(--text-muted);
        text-align: right;
        white-space: nowrap;
        align-self: center;
    }

    /* (6) Status badge — B1 */
    #revTable tbody td:nth-child(6) {
        grid-column: 2; grid-row: 1;
        text-align: right;
        align-self: center;
    }

    /* (7) Actions — spans full width, row 5 */
    #revTable tbody td:nth-child(7) {
        grid-column: 1 / 3; grid-row: 5;
        display: flex !important;
        gap: 8px;
        margin-top: 8px;
        padding-top: 10px !important;
        border-top: 1px dashed var(--border) !important;
        white-space: normal;
        text-align: unset;
    }

    /* Buttons: stretch to fill the actions row */
    .rv-action-btn {
        flex: 1;
        min-height: 36px;
        font-size: 12px !important;
        padding: 6px 10px !important;
        border-radius: 7px;
    }

    /* Show button text labels on mobile */
    .rv-btn-label { display: inline; }
}

/* ── Very small phones (< 380px) ────────────────────────── */
@media (max-width: 379px) {
    #revTable tbody tr {
        padding: 12px 12px;
        column-gap: 7px;
    }
}
</style>

<!-- ═══ PAGE HEADER ════════════════════════════════════════ -->
<div class="rv-page-header page-header">
    <div>
        <h4>Reviews <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span></h4>
        <p class="page-subtitle">Moderate customer product reviews.</p>
    </div>
    <div class="rv-pills-wrap">
        <?php foreach (['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $s => $l): ?>
        <a href="?status=<?= $s ?>&per_page=<?= $perPage ?>" class="rev-filter-pill <?= $status===$s?'active':'' ?>">
            <?= $l ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- ═══ SEARCH + PER-PAGE FILTER BAR ════════════════════════ -->
<?php
$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Search product, customer…']];
$filterFields = [];
$extraButtons = '';
include __DIR__ . '/../layouts/_pagination.php';
?>

<!-- ═══ REVIEWS TABLE / EMPTY STATE ═════════════════════════ -->
<?php if (empty($reviews)): ?>
<div class="card">
    <div class="card-body text-center py-5" style="color:var(--text-muted);">
        <i class="fa fa-star-o fa-3x mb-3 d-block"></i>
        <p>No <?= $status ?> reviews found.</p>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body p-0">
        <div class="rv-table-wrap">
        <div class="admin-table">
            <table class="table table-sm align-middle mb-0" id="revTable">
                <thead>
                    <tr>
                        <th style="width:22%;">Product</th>
                        <th style="width:13%;">Customer</th>
                        <th style="width:90px;">Rating</th>
                        <th>Review</th>
                        <th style="width:88px;">Date</th>
                        <th style="width:90px;text-align:center;">Status</th>
                        <th style="width:96px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($reviews as $r):
                    $ss = $statusStyles[$r['status']] ?? $statusStyles['pending'];
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= '<i class="fa fa-star' . ($i <= $r['rating'] ? ' star-filled' : ' star-empty') . '" style="font-size:11px;"></i>';
                    }
                ?>
                <tr id="rev-row-<?= $r['id'] ?>">
                    <!-- (1) Product -->
                    <td>
                        <a href="<?= url('product/'.$r['slug']) ?>" target="_blank"
                           style="font-size:12px;font-weight:600;color:var(--cyan);text-decoration:none;"
                           title="<?= e($r['product_name']) ?>">
                            <?= e(Helper::truncate($r['product_name'], 32)) ?>
                        </a>
                    </td>
                    <!-- (2) Customer -->
                    <td style="font-size:12px;color:var(--text-dim);"><?= e($r['full_name']) ?></td>
                    <!-- (3) Rating -->
                    <td>
                        <div><?= $stars ?></div>
                        <div style="font-size:10px;color:var(--text-muted);margin-top:1px;"><?= $r['rating'] ?>/5</div>
                    </td>
                    <!-- (4) Review -->
                    <td>
                        <?php if ($r['title']): ?><div class="rev-title"><?= e(Helper::truncate($r['title'], 50)) ?></div><?php endif; ?>
                        <?php if ($r['body']):  ?><div class="rev-body"><?= e(Helper::truncate($r['body'],  90)) ?></div><?php endif; ?>
                        <?php if (!$r['title'] && !$r['body']): ?><span style="color:var(--text-muted);font-size:12px;">—</span><?php endif; ?>
                    </td>
                    <!-- (5) Date -->
                    <td style="font-size:11px;color:var(--text-muted);white-space:nowrap;">
                        <?= date('M j, Y', strtotime($r['created_at'])) ?>
                    </td>
                    <!-- (6) Status -->
                    <td style="text-align:center;">
                        <span style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;">
                            <?= ucfirst($r['status']) ?>
                        </span>
                    </td>
                    <!-- (7) Actions -->
                    <td style="text-align:right;white-space:nowrap;">
                        <?php if ($r['status'] !== 'approved'): ?>
                        <button class="btn btn-sm review-action rv-action-btn rv-btn-approve"
                                data-id="<?= $r['id'] ?>" data-action="approved">
                            <i class="fa fa-check"></i>
                            <span class="rv-btn-label">Approve</span>
                        </button>
                        <?php endif; ?>
                        <?php if ($r['status'] !== 'rejected'): ?>
                        <button class="btn btn-sm review-action rv-action-btn rv-btn-reject"
                                data-id="<?= $r['id'] ?>" data-action="rejected">
                            <i class="fa fa-times"></i>
                            <span class="rv-btn-label">Reject</span>
                        </button>
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
<?php $itemLabel = 'reviews'; include __DIR__ . '/../layouts/_pagnav.php'; ?>
<?php endif; ?>

<?php $extraScript = <<<'JS'
<script>
$(document).on('click', '.review-action', function() {
    var btn = $(this);
    var id  = btn.data('id');
    var act = btn.data('action');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    ajaxPost('admin/reviews/update-status', { id: id, status: act }, function(res) {
        if (res.success) {
            $('#rev-row-' + id).fadeOut(300, function() { $(this).remove(); });
        } else {
            var icon = act === 'approved'
                ? '<i class="fa fa-check"></i><span class="rv-btn-label">Approve</span>'
                : '<i class="fa fa-times"></i><span class="rv-btn-label">Reject</span>';
            btn.prop('disabled', false).html(icon);
            SwalDark.fire('Error', res.message || 'Action failed.', 'error');
        }
    });
});
</script>
JS;
?>
