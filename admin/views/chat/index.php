<?php $pageTitle = 'Chat Conversations'; ?>
<style>
/* ══ CHAT LIST — RESPONSIVE ═══════════════════════════════════
   Scoped to .cl-* to avoid bleed.                            */

/* ── 1. Page header ──────────────────────────────────────── */
.cl-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem;
}
.cl-title { font-size: 1.25rem; font-weight: 700; color: var(--text); letter-spacing: -.02em; }

/* ── 2. Toolbar: search + filters ────────────────────────── */
.cl-toolbar {
    display: flex; align-items: center;
    gap: .5rem; flex-wrap: wrap; margin-bottom: 1.25rem;
}

/* Search */
.cl-search {
    position: relative;
    flex: 1 1 180px;            /* grows, min 180px, wraps if needed */
    min-width: 0;
    max-width: 360px;
}
.cl-search i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 13px; pointer-events: none;
}
.cl-search input {
    width: 100%; background: var(--surface2); border: 1px solid var(--border);
    color: var(--text); border-radius: var(--radius-sm);
    padding: 8px 12px 8px 34px; font-size: 13px; outline: none;
    transition: var(--transition);
}
.cl-search input:focus { border-color: var(--cyan); box-shadow: 0 0 0 3px var(--cyan-dim); }
.cl-search input::placeholder { color: var(--text-muted); }

/* Filter pill group */
.cl-filters {
    display: flex; background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;
}
.cl-filter-btn {
    padding: 7px 14px; font-size: 12px; font-weight: 600; color: var(--text-muted);
    border: none; background: none; cursor: pointer; transition: var(--transition);
    white-space: nowrap;
}
.cl-filter-btn.active { background: var(--cyan); color: #000; }
.cl-filter-btn:not(.active):hover { background: var(--surface2); color: var(--text); }

/* Auto-reply link */
.cl-auto-btn {
    margin-left: auto; flex-shrink: 0;
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 7px 14px; background: var(--surface2);
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    color: var(--text-dim); font-size: 12px; font-weight: 500;
    text-decoration: none; transition: var(--transition); white-space: nowrap;
}
.cl-auto-btn:hover { border-color: var(--cyan); color: var(--cyan); }

/* ── 3. Conversation cards ───────────────────────────────── */
.cl-list { display: flex; flex-direction: column; gap: 6px; }
.cl-card {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px; background: var(--surface);
    border: 1px solid var(--border); border-radius: var(--radius);
    cursor: pointer; text-decoration: none; transition: var(--transition);
    position: relative; overflow: hidden;
}
.cl-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--cyan); transform: scaleY(0);
    transition: var(--transition); border-radius: 3px 0 0 3px;
}
.cl-card:hover { background: var(--surface2); border-color: var(--border2); transform: translateX(2px); }
.cl-card:hover::before { transform: scaleY(1); }
.cl-card:hover .cl-name { color: var(--cyan); }

.cl-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1rem; flex-shrink: 0; letter-spacing: -.02em;
}

.cl-body { flex: 1; min-width: 0; }
.cl-name { font-size: .875rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: var(--transition); }
.cl-sub { font-size: .72rem; color: var(--text-muted); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cl-preview { font-size: .78rem; color: var(--text-dim); margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.cl-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
.cl-time { font-size: .68rem; color: var(--text-muted); white-space: nowrap; }
.cl-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px; font-size: .68rem; font-weight: 600;
}
.cl-badge.open { background: var(--green-dim); color: var(--green); }
.cl-badge.open::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: cl-pulse 1.5s infinite; }
.cl-badge.closed { background: var(--surface2); color: var(--text-muted); }
.cl-badge-id { font-size: .65rem; color: var(--text-muted); background: var(--surface2); border: 1px solid var(--border); border-radius: 4px; padding: 1px 5px; }

@keyframes cl-pulse { 0%,100% { opacity: 1; } 50% { opacity: .4; } }

/* Empty states */
.cl-empty { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
.cl-empty i { font-size: 2.5rem; opacity: .2; display: block; margin-bottom: .75rem; }

/* ── 4. Mobile (≤ 575px) ─────────────────────────────────── */
@media (max-width: 575px) {

    /* Search takes own full row, then filters wrap below */
    .cl-search {
        flex: 1 1 100%;     /* full row */
        max-width: 100%;
        order: -1;          /* search always first */
    }

    /* Filter buttons: stretch equally */
    .cl-filters { flex: 1; }
    .cl-filter-btn { flex: 1; text-align: center; padding: 7px 8px; }

    /* Auto-reply button: icon only */
    .cl-auto-btn span { display: none; }
    .cl-auto-btn { padding: 7px 11px; }

    /* Card: tighter padding */
    .cl-card { padding: 12px; gap: 10px; }
    .cl-avatar { width: 38px; height: 38px; font-size: .875rem; }

    /* Right section: move below avatar+body as a horizontal row */
    .cl-right {
        flex-direction: row;
        align-items: center;
        width: 100%;
        padding-left: 48px;   /* align with cl-body (avatar 38px + gap 10px) */
        margin-top: -2px;
        gap: 6px;
    }
    /* Push time to the right */
    .cl-time { margin-left: auto; }
}
</style>

<?php
$avatarColors = [
    ['bg'=>'rgba(0,212,255,.18)','color'=>'var(--cyan)'],
    ['bg'=>'rgba(124,58,237,.18)','color'=>'var(--purple)'],
    ['bg'=>'rgba(16,185,129,.18)','color'=>'var(--green)'],
    ['bg'=>'rgba(245,158,11,.18)','color'=>'var(--amber)'],
    ['bg'=>'rgba(59,130,246,.18)','color'=>'var(--blue)'],
    ['bg'=>'rgba(239,68,68,.18)','color'=>'var(--red)'],
];
$openCount   = count(array_filter($sessions ?? [], fn($s) => $s['status'] === 'open'));
$closedCount = count(array_filter($sessions ?? [], fn($s) => $s['status'] !== 'open'));
$totalCount  = count($sessions ?? []);
?>

<!-- ═══ PAGE HEADER ══════════════════════════════════════════ -->
<div class="cl-header">
    <div>
        <div class="cl-title"><i class="fa fa-comments me-2" style="color:var(--cyan);"></i>Chat Conversations</div>
        <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">
            <?= $totalCount ?> total &nbsp;·&nbsp;
            <span style="color:var(--green);"><?= $openCount ?> open</span> &nbsp;·&nbsp;
            <?= $closedCount ?> closed
        </div>
    </div>
    <a href="<?= Helper::url('admin/chat/auto-replies') ?>" class="cl-auto-btn">
        <i class="fa fa-robot"></i><span>Auto-Reply Rules</span>
    </a>
</div>

<!-- ═══ TOOLBAR ══════════════════════════════════════════════ -->
<div class="cl-toolbar">
    <div class="cl-search">
        <i class="fa fa-search"></i>
        <input type="text" id="clSearch" placeholder="Search name, email, message…"
               value="<?= e($search) ?>" autocomplete="off">
    </div>
    <div class="cl-filters">
        <button class="cl-filter-btn active" data-filter="all">All <span style="opacity:.6;">(<?= $totalCount ?>)</span></button>
        <button class="cl-filter-btn" data-filter="open">Open <span style="opacity:.6;">(<?= $openCount ?>)</span></button>
        <button class="cl-filter-btn" data-filter="closed">Closed <span style="opacity:.6;">(<?= $closedCount ?>)</span></button>
    </div>
</div>

<!-- ═══ CONVERSATION LIST ════════════════════════════════════ -->
<?php if (empty($sessions)): ?>
<div class="cl-empty">
    <i class="fa fa-comments"></i>
    No conversations yet.
</div>
<?php else: ?>
<div class="cl-list" id="clList">
    <?php foreach ($sessions as $i => $s):
        $isGuest = empty($s['user_name']);
        $name    = $isGuest ? 'Guest' : $s['user_name'];
        $initial = strtoupper(substr($name, 0, 1));
        $isOpen  = $s['status'] === 'open';
        $color   = $avatarColors[$i % count($avatarColors)];
        $preview = $s['last_msg'] ?? '—';
        $timeStr = $s['last_at'] ? date('M j, g:i A', strtotime($s['last_at'])) : '—';
    ?>
    <a href="<?= Helper::url('admin/chat/view/'.$s['id']) ?>"
       class="cl-card"
       data-status="<?= $isOpen ? 'open' : 'closed' ?>"
       data-search="<?= e(strtolower($name . ' ' . ($s['user_email'] ?? '') . ' ' . $preview)) ?>">

        <div class="cl-avatar" style="background:<?= $color['bg'] ?>;color:<?= $color['color'] ?>;"><?= $initial ?></div>

        <div class="cl-body">
            <div class="cl-name"><?= e($name) ?></div>
            <?php if ($isGuest && !empty($s['guest_id'])): ?>
                <div class="cl-sub">Guest &nbsp;·&nbsp; <?= e(substr($s['guest_id'], 0, 18)) ?>…</div>
            <?php elseif (!empty($s['user_email'])): ?>
                <div class="cl-sub"><?= e($s['user_email']) ?></div>
            <?php endif; ?>
            <div class="cl-preview"><?= e(mb_strimwidth($preview, 0, 80, '…')) ?></div>
        </div>

        <div class="cl-right">
            <span class="cl-badge <?= $isOpen ? 'open' : 'closed' ?>"><?= $isOpen ? 'Open' : 'Closed' ?></span>
            <span class="cl-time"><?= $timeStr ?></span>
            <span class="cl-badge-id">#<?= $s['id'] ?></span>
        </div>

    </a>
    <?php endforeach; ?>
</div>

<div id="clNoResults" style="display:none;" class="cl-empty">
    <i class="fa fa-search"></i>
    No conversations match your search.
</div>
<?php endif; ?>

<?php $extraScript = <<<'JS'
<script>
(function() {
    const list    = document.getElementById('clList');
    const noRes   = document.getElementById('clNoResults');
    if (!list) return;

    const cards   = Array.from(list.querySelectorAll('.cl-card'));
    const search  = document.getElementById('clSearch');
    const filters = document.querySelectorAll('.cl-filter-btn');
    let   curFilter = 'all';

    function applyFilter() {
        const q = (search?.value || '').toLowerCase().trim();
        let visible = 0;
        cards.forEach(c => {
            const statusMatch = curFilter === 'all' || c.dataset.status === curFilter;
            const searchMatch = !q || (c.dataset.search || '').includes(q);
            const show = statusMatch && searchMatch;
            c.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noRes) noRes.style.display = visible === 0 ? '' : 'none';
    }

    filters.forEach(btn => {
        btn.addEventListener('click', function() {
            filters.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            curFilter = this.dataset.filter;
            applyFilter();
        });
    });

    let searchTimer;
    search?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilter, 200);
    });

    if (search?.value) applyFilter();
})();
</script>
JS;
?>
