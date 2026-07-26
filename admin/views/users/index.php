<?php /* users/index.php */ $pageTitle = 'Customers'; ?>
<style>
/* ══ USERS PAGE — RESPONSIVE ════════════════════════════════
   All selectors scoped to .usr-* / #usrTable to avoid bleed. */

/* ── 1. Filter bar form layout ──────────────────────────── */
.usr-filter-form {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  width: 100%;
  align-items: center;
}
.usr-search-wrap { flex: 1 1 200px; }

/* ── 2. Table scroll wrapper (desktop / tablet) ─────────── */
.usr-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
  border-radius: var(--radius);
}
.usr-table-wrap::-webkit-scrollbar       { height: 4px; }
.usr-table-wrap::-webkit-scrollbar-track { background: transparent; }
.usr-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 768px) {
  #usrTable { min-width: 700px; }
}

/* ── 3. Action button — touch-friendly ─────────────────── */
.usr-toggle-btn { white-space: nowrap; }

/* ── 4. Pagination ──────────────────────────────────────── */
.usr-pag-wrap {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-top: 16px;
  padding: 0 2px;
}
.usr-pag-info { font-size: 12px; color: var(--text-muted); }
.usr-pag-wrap .pagination {
  margin: 0;
  display: flex;
  gap: 3px;
  list-style: none;
  padding: 0;
}
.usr-pag-wrap .page-link {
  display: block;
  background: var(--surface2);
  border: 1px solid var(--border);
  color: var(--text-muted);
  font-size: 12px;
  padding: 5px 11px;
  border-radius: var(--radius-sm);
  text-decoration: none;
  line-height: 1.5;
  transition: var(--transition);
}
.usr-pag-wrap .page-link:hover  { background: var(--cyan-dim); color: var(--cyan); border-color: rgba(0,212,255,.3); }
.usr-pag-wrap .page-item.active .page-link {
  background: var(--cyan);
  border-color: var(--cyan);
  color: #000;
  font-weight: 700;
}

/* ══ MOBILE CARD LAYOUT (≤ 767px) ══════════════════════════
   8 columns: Name(1) Email(2) Phone(3) Orders(4)
              Spent(5) Status(6) Joined(7) Actions(8)

   Card grid — 3 columns:
     Col A (1fr)  : name / email / phone / orders label
     Col B (auto) : status badge / joined / spent amount
     Col C (auto) : suspend/activate button (spans all rows)  */
@media (max-width: 767px) {

  /* Allow cards to breathe — remove the admin-table clip */
  .usr-table-wrap           { overflow-x: visible; }
  .usr-table-wrap .admin-table { overflow: visible !important; }

  /* Hide column headers */
  #usrTable thead { display: none; }

  /* tr becomes a 3-col card */
  #usrTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto auto;
    column-gap: 10px;
    row-gap: 5px;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
  }
  #usrTable tbody tr:last-child { border-bottom: none; }

  /* Strip td defaults */
  #usrTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* ── Cell placement ──────────────────────────────────── */

  /* (1) Name — A1 */
  #usrTable tbody td:nth-child(1) {
    grid-column: 1; grid-row: 1;
    font-size: 13px; font-weight: 700; align-self: center;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }

  /* (2) Email — A+B, row 2 */
  #usrTable tbody td:nth-child(2) {
    grid-column: 1 / 3; grid-row: 2;
    font-size: 11px !important; color: var(--text-muted);
    word-break: break-all;
  }

  /* (3) Phone — A3 */
  #usrTable tbody td:nth-child(3) {
    grid-column: 1; grid-row: 3;
    font-size: 11px !important; color: var(--text-dim);
  }

  /* (4) Orders — A4 (with label prefix) */
  #usrTable tbody td:nth-child(4) {
    grid-column: 1; grid-row: 4;
    font-size: 11px !important; color: var(--text-muted); align-self: center;
  }
  #usrTable tbody td:nth-child(4)::before {
    content: 'Orders: '; font-size: 10px; opacity: .75;
  }

  /* (5) Total Spent — B4 */
  #usrTable tbody td:nth-child(5) {
    grid-column: 2; grid-row: 4;
    font-size: 11px !important; font-weight: 700; color: var(--cyan);
    align-self: center; text-align: right; white-space: nowrap;
  }

  /* (6) Status badge — B1 */
  #usrTable tbody td:nth-child(6) {
    grid-column: 2; grid-row: 1;
    align-self: center; text-align: right;
  }

  /* (7) Joined — B3 */
  #usrTable tbody td:nth-child(7) {
    grid-column: 2; grid-row: 3;
    font-size: 10px !important; color: var(--text-muted);
    align-self: center; text-align: right; white-space: nowrap;
  }
  #usrTable tbody td:nth-child(7)::before {
    content: ''; /* no label needed — position implies it's a date */
  }

  /* (8) Actions — C1-4 (spans all rows) */
  #usrTable tbody td:nth-child(8) {
    grid-column: 3; grid-row: 1 / 5;
    align-self: center;
    display: flex !important;
    align-items: center;
    justify-content: center;
  }

  /* Shrink button text to fit narrow column */
  .usr-toggle-btn { font-size: 11px !important; padding: 5px 8px !important; }

  /* Filter: search stretches, buttons side-by-side */
  .usr-search-wrap { flex: 1 1 100%; }

  /* Pagination: stack on mobile */
  .usr-pag-wrap {
    flex-direction: column;
    align-items: center;
  }
}

/* ── Very small phones (< 380px) ────────────────────────── */
@media (max-width: 379px) {
  #usrTable tbody tr {
    grid-template-columns: 1fr auto auto;
    column-gap: 7px;
    padding: 10px 10px;
  }
  .usr-toggle-btn { font-size: 10px !important; padding: 4px 6px !important; }
}
</style>

<!-- ═══ PAGE HEADER ══════════════════════════════════════ -->
<div class="page-header mb-3">
    <h4>Customers <span class="badge bg-secondary ms-1"><?= $total ?></span></h4>
</div>

<!-- ═══ FILTER BAR ═══════════════════════════════════════ -->
<div class="admin-filter-bar mb-3">
    <form method="GET" class="usr-filter-form">
        <div class="usr-search-wrap">
            <input type="text" name="search" class="form-control afb-input"
                   placeholder="Search name, email, phone…" value="<?= e($search) ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa fa-search me-1"></i>Search
        </button>
        <a href="<?= url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-times me-1"></i>Reset
        </a>
    </form>
</div>

<!-- ═══ TABLE ════════════════════════════════════════════ -->
<div class="usr-table-wrap">
<div class="admin-table">
    <table class="table table-sm align-middle mb-0" id="usrTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Status</th>
                <th>Joined</th>
                <th style="width:90px;"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td class="fw-semibold small"><?= e($u['full_name']) ?></td>
            <td class="small"><?= e($u['email']) ?></td>
            <td class="small"><?= e($u['phone'] ?? '—') ?></td>
            <td class="small"><?= (int)$u['order_count'] ?></td>
            <td class="small fw-bold">LKR <?= number_format($u['total_spent'] ?? 0, 0) ?></td>
            <td>
                <span class="badge <?= $u['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= ucfirst($u['status']) ?>
                </span>
            </td>
            <td class="small text-muted"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-warning usr-toggle-btn toggle-user"
                        data-id="<?= $u['id'] ?>">
                    <?= $u['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($users)): ?>
        <tr>
            <td colspan="8" class="text-center text-muted" style="padding:2.5rem 1rem;">
                <i class="fa fa-users fa-2x mb-2 d-block" style="opacity:.25;"></i>
                No customers found.
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<!-- ═══ PAGINATION ═══════════════════════════════════════ -->
<?php if (($pagData['total_pages'] ?? 1) > 1): ?>
<div class="usr-pag-wrap">
    <small class="usr-pag-info">
        Showing <?= ($pagData['offset'] + 1) ?>–<?= min($pagData['offset'] + $pagData['per_page'], $total) ?>
        of <?= $total ?> customers
    </small>
    <ul class="pagination mb-0">
        <?php if ($pagData['has_prev']): ?>
        <li class="page-item">
            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagData['current_page'] - 1])) ?>">‹</a>
        </li>
        <?php endif; ?>
        <?php
        $start = max(1, $pagData['current_page'] - 2);
        $end   = min($pagData['total_pages'], $start + 4);
        for ($i = $start; $i <= $end; $i++):
        ?>
        <li class="page-item <?= $i === $pagData['current_page'] ? 'active' : '' ?>">
            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        <?php if ($pagData['has_next']): ?>
        <li class="page-item">
            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagData['current_page'] + 1])) ?>">›</a>
        </li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>

<?php $extraScript = <<<'JS'
<script>
$(document).on('click', '.toggle-user', function() {
    const btn = $(this);
    const id  = btn.data('id');
    ajaxPost('admin/users/toggle-status', { id }, function(res) {
        if (res.success) {
            btn.text(res.status === 'active' ? 'Suspend' : 'Activate')
               .closest('tr').find('.badge')
               .text(res.status)
               .toggleClass('bg-success', res.status === 'active')
               .toggleClass('bg-secondary', res.status !== 'active');
        }
    });
});
</script>
JS;
?>
