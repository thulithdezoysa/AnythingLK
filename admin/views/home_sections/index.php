<?php $pageTitle = 'Homepage Sections'; ?>
<style>
/* ── Tabs ─────────────────────────────────────── */
.hs-tabs{display:flex;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:4px;margin-bottom:1.5rem;flex-wrap:wrap;}
.hs-tab{padding:8px 18px;border-radius:6px;font-size:13px;font-weight:600;color:var(--text-muted);cursor:pointer;border:none;background:none;transition:var(--transition);white-space:nowrap;touch-action:manipulation;}
.hs-tab.active{background:var(--cyan);color:#000;}
.hs-tab:not(.active):hover{background:var(--surface2);color:var(--text);}
.hs-panel{display:none;}.hs-panel.active{display:block;}

/* ── Table ───────────────────────────────────── */
.hs-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
.hs-table{width:100%;border-collapse:separate;border-spacing:0;min-width:540px;}
.hs-table th{background:var(--surface2);padding:10px 14px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);border-bottom:1px solid var(--border);white-space:nowrap;}
.hs-table td{padding:11px 14px;border-bottom:1px solid var(--border);font-size:13px;color:var(--text);vertical-align:middle;}
.hs-table tr:last-child td{border-bottom:none;}
.hs-table tr:hover td{background:var(--surface);}

/* ── Thumbnails / status badges ──────────────── */
.hs-thumb{width:56px;height:40px;object-fit:cover;border-radius:6px;background:var(--surface2);flex-shrink:0;}
.hs-no-img{width:56px;height:40px;background:var(--surface2);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:18px;flex-shrink:0;}
.hs-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:600;cursor:pointer;touch-action:manipulation;white-space:nowrap;}
.hs-badge.on{background:var(--green-dim);color:var(--green);}
.hs-badge.off{background:var(--surface2);color:var(--text-muted);}

/* ── Drag handle ─────────────────────────────── */
.hs-drag-handle{cursor:grab;color:var(--text-muted);padding:4px 6px;font-size:14px;touch-action:none;}
.hs-drag-handle:active{cursor:grabbing;}
.hs-star{color:#f59e0b;}

/* ── Panel header (desc + add btn) ───────────── */
.hs-panel-header{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem;flex-wrap:wrap;}
.hs-panel-desc{font-size:.8rem;color:var(--text-muted);flex:1 1 auto;}

/* ── Add button ──────────────────────────────── */
.add-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:9px 18px;background:linear-gradient(135deg,var(--cyan),#0098d4);color:#000;font-weight:700;font-size:13px;border:none;border-radius:var(--radius-sm);cursor:pointer;transition:var(--transition);white-space:nowrap;touch-action:manipulation;flex-shrink:0;}
.add-btn:hover{transform:translateY(-1px);box-shadow:var(--glow-cyan);}

/* ── Action buttons in table ─────────────────── */
.hs-action-group{display:flex;gap:4px;align-items:center;}
.hs-action-group .btn-sm{min-width:30px;min-height:28px;display:inline-flex;align-items:center;justify-content:center;}

/* ── Modal overlay ───────────────────────────── */
.hs-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);z-index:1050;align-items:flex-start;justify-content:center;padding:1.5rem 1rem;overflow-y:auto;}
.hs-modal-overlay.open{display:flex;}

/* ── Modal card ──────────────────────────────── */
.hs-modal{background:var(--bg2);border:1px solid var(--border2);border-radius:var(--radius);width:100%;max-width:540px;box-shadow:0 24px 60px rgba(0,0,0,.55);overscroll-behavior:contain;margin:auto;}
.hs-modal-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border);}
.hs-modal-head h5{font-size:15px;font-weight:700;color:var(--text);margin:0;}
.hs-modal-body{padding:20px;}
.hs-modal-footer{display:flex;justify-content:flex-end;gap:.5rem;padding:14px 20px;border-top:1px solid var(--border);}
.hs-close{background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer;line-height:1;padding:4px 8px;border-radius:4px;touch-action:manipulation;}
.hs-close:hover{background:var(--surface2);color:var(--text);}

/* ── Form rows & inputs ──────────────────────── */
.hs-form-row{margin-bottom:14px;}
.hs-label{display:block;font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:6px;letter-spacing:.04em;}
.hs-input{width:100%;background:var(--surface2);border:1px solid var(--border);color:var(--text);border-radius:var(--radius-sm);padding:9px 12px;font-size:13px;outline:none;transition:var(--transition);}
.hs-input:focus{border-color:var(--cyan);box-shadow:0 0 0 3px var(--cyan-dim);}
.hs-input::placeholder{color:var(--text-muted);}
textarea.hs-input{resize:vertical;min-height:80px;}
.hs-img-preview{width:100%;max-height:160px;object-fit:cover;border-radius:8px;margin-top:8px;display:none;}
.hs-img-preview.show{display:block;}

/* ── Color swatches ──────────────────────────── */
.hs-color-row{display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;}
.hs-color-swatch{width:30px;height:30px;border-radius:50%;border:2px solid transparent;cursor:pointer;transition:var(--transition);touch-action:manipulation;}
.hs-color-swatch.selected{border-color:#fff;transform:scale(1.2);}

/* ── Star rating ─────────────────────────────── */
.star-rating-input{display:flex;gap:4px;flex-direction:row-reverse;justify-content:flex-end;}
.star-rating-input input{display:none;}
.star-rating-input label{color:var(--text-muted);font-size:24px;cursor:pointer;transition:var(--transition);touch-action:manipulation;}
.star-rating-input label:hover,.star-rating-input label:hover~label,
.star-rating-input input:checked~label{color:#f59e0b;}

/* ══ RESPONSIVE ════════════════════════════════ */

/* Tablet (≤ 992px) */
@media (max-width:991px) {
  .hs-tab { padding: 7px 14px; font-size: 12.5px; }
}

/* Mobile landscape + small tablet (≤ 767px) */
@media (max-width:767px) {
  .hs-tab { padding: 7px 12px; font-size: 12px; }
  .hs-panel-header { flex-direction: column; align-items: flex-start; }
  .add-btn { width: 100%; }
  .hs-table th,
  .hs-table td { padding: 9px 10px; font-size: 12px; }
  .hs-modal-overlay { padding: 1rem .75rem; }
  .hs-modal-head { padding: 14px 16px; }
  .hs-modal-body { padding: 14px 16px; }
  .hs-modal-footer { padding: 10px 16px; }
  .hs-modal-footer .btn { flex: 1; text-align: center; justify-content: center; }
}

/* Small mobile (≤ 575px) */
@media (max-width:575px) {
  .hs-tabs { padding: 3px; gap: 2px; }
  .hs-tab { padding: 6px 11px; font-size: 11.5px; }
  .hs-modal-overlay { padding: .5rem; }
  .hs-table { min-width: 460px; }
  .hs-modal-head h5 { font-size: 14px; }
  .hs-label { font-size: 11.5px; }
  .hs-input { font-size: 12.5px; padding: 8px 11px; }
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h4 style="margin:0;font-size:1.2rem;font-weight:700;">Homepage Sections</h4>
    <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">Manage dynamic sections shown on the homepage</div>
  </div>
</div>

<!-- Tabs -->
<div class="hs-tabs">
  <button class="hs-tab <?= $activeTab==='hero'?'active':'' ?>" data-panel="panel-hero">
    <i class="fa fa-image me-1"></i>Hero Sections
    <span style="opacity:.6;font-size:.8em;">(<?= count($heroItems) ?>)</span>
  </button>
  <button class="hs-tab <?= $activeTab==='collections'?'active':'' ?>" data-panel="panel-collections">
    <i class="fa fa-th-large me-1"></i>Collections
    <span style="opacity:.6;font-size:.8em;">(<?= count($collections) ?>)</span>
  </button>
  <button class="hs-tab <?= $activeTab==='reviews'?'active':'' ?>" data-panel="panel-reviews">
    <i class="fa fa-star me-1"></i>Reviews
    <span style="opacity:.6;font-size:.8em;">(<?= count($reviews) ?>)</span>
  </button>
</div>


<!-- ═══════════════════════════════════════════
     PANEL: HERO SECTIONS
═══════════════════════════════════════════ -->
<div id="panel-hero" class="hs-panel <?= $activeTab==='hero'?'active':'' ?>">
  <div class="hs-panel-header">
    <div class="hs-panel-desc">Shown in the green promo panel after Featured Products.</div>
    <button class="add-btn" onclick="openHeroModal()"><i class="fa fa-plus"></i> Add Hero</button>
  </div>

  <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if (empty($heroItems)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);"><i class="fa fa-image fa-2x mb-2 d-block opacity-25"></i>No hero sections yet.</div>
    <?php else: ?>
    <div class="hs-table-wrap">
    <table class="hs-table">
      <?php
        $_catMap = [];
        foreach ($categories as $c) { $_catMap[(int)$c['id']] = $c['name']; }
      ?>
      <thead><tr>
        <th style="width:32px;"></th>
        <th>Title</th>
        <th>Category</th>
        <th>Badge</th>
        <th style="min-width:100px;">Link</th>
        <th>Status</th>
        <th style="min-width:50px;">Sort</th>
        <th style="min-width:76px;"></th>
      </tr></thead>
      <tbody id="heroList">
        <?php foreach ($heroItems as $h): ?>
        <tr data-id="<?= $h['id'] ?>">
          <td><span class="hs-drag-handle"><i class="fa fa-bars"></i></span></td>
          <td style="min-width:140px;">
            <div style="font-weight:600;"><?= e($h['title']) ?></div>
            <?php if ($h['subtitle']): ?>
            <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px;"><?= e(mb_strimwidth($h['subtitle'],0,55,'…')) ?></div>
            <?php endif; ?>
          </td>
          <td style="font-size:.78rem;min-width:100px;">
            <?php if (!empty($h['category_id']) && isset($_catMap[(int)$h['category_id']])): ?>
              <span style="background:var(--cyan-dim);color:var(--cyan);padding:3px 9px;border-radius:20px;font-size:.7rem;font-weight:600;white-space:nowrap;">
                <?= e($_catMap[(int)$h['category_id']]) ?>
              </span>
            <?php else: ?>
              <span style="color:var(--text-muted);">—</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.75rem;white-space:nowrap;"><?= e($h['badge_text']) ?: '—' ?></td>
          <td style="font-size:.72rem;color:var(--text-muted);max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($h['link']??'—') ?></td>
          <td>
            <span class="hs-badge <?= $h['status']?'on':'off' ?>" onclick="toggleItem('hero',<?= $h['id'] ?>,this)">
              <i class="fa fa-circle" style="font-size:6px;"></i><?= $h['status']?'Active':'Inactive' ?>
            </span>
          </td>
          <td style="font-size:.75rem;color:var(--text-muted);text-align:center;"><?= $h['sort_order'] ?></td>
          <td>
            <div class="hs-action-group">
              <button class="btn btn-sm btn-outline-primary" onclick='editHero(<?= json_encode($h) ?>)' title="Edit"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteItem('hero',<?= $h['id'] ?>,this)" title="Delete"><i class="fa fa-trash"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endif; ?>
  </div>
</div>


<!-- ═══════════════════════════════════════════
     PANEL: COLLECTIONS
═══════════════════════════════════════════ -->
<div id="panel-collections" class="hs-panel <?= $activeTab==='collections'?'active':'' ?>">
  <div class="hs-panel-header">
    <div class="hs-panel-desc">Shown in the 2×2 "Shop by Collection" grid.</div>
    <button class="add-btn" onclick="openCollModal()"><i class="fa fa-plus"></i> Add Collection</button>
  </div>

  <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if (empty($collections)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);"><i class="fa fa-th-large fa-2x mb-2 d-block opacity-25"></i>No collections yet.</div>
    <?php else: ?>
    <div class="hs-table-wrap">
    <table class="hs-table">
      <thead><tr>
        <th style="width:32px;"></th>
        <th style="min-width:68px;">Preview</th>
        <th style="min-width:130px;">Name</th>
        <th style="min-width:120px;">Tagline</th>
        <th style="min-width:60px;">Color</th>
        <th>Status</th>
        <th style="min-width:76px;"></th>
      </tr></thead>
      <tbody id="collList">
        <?php foreach ($collections as $c): ?>
        <tr data-id="<?= $c['id'] ?>">
          <td><span class="hs-drag-handle"><i class="fa fa-bars"></i></span></td>
          <td>
            <?php if ($c['image']): ?>
              <img src="<?= url('uploads/home/'.e($c['image'])) ?>" class="hs-thumb" alt="">
            <?php else: ?>
              <div class="hs-no-img" style="color:<?= e($c['color']) ?>;"><i class="fa fa-image"></i></div>
            <?php endif; ?>
          </td>
          <td style="font-weight:600;"><?= e($c['name']) ?></td>
          <td style="font-size:.75rem;color:var(--text-muted);"><?= e($c['tagline']??'—') ?></td>
          <td><span style="display:inline-block;width:22px;height:22px;border-radius:50%;background:<?= e($c['color']) ?>;border:2px solid rgba(255,255,255,.2);vertical-align:middle;"></span></td>
          <td>
            <span class="hs-badge <?= $c['status']?'on':'off' ?>" onclick="toggleItem('collection',<?= $c['id'] ?>,this)">
              <i class="fa fa-circle" style="font-size:6px;"></i><?= $c['status']?'Active':'Inactive' ?>
            </span>
          </td>
          <td>
            <div class="hs-action-group">
              <button class="btn btn-sm btn-outline-primary" onclick='editColl(<?= json_encode($c) ?>)' title="Edit"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteItem('collection',<?= $c['id'] ?>,this)" title="Delete"><i class="fa fa-trash"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endif; ?>
  </div>
</div>


<!-- ═══════════════════════════════════════════
     PANEL: REVIEWS
═══════════════════════════════════════════ -->
<div id="panel-reviews" class="hs-panel <?= $activeTab==='reviews'?'active':'' ?>">
  <div class="hs-panel-header">
    <div class="hs-panel-desc">Shown in the "What People Say" review slider.</div>
    <button class="add-btn" onclick="openRevModal()"><i class="fa fa-plus"></i> Add Review</button>
  </div>

  <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if (empty($reviews)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);"><i class="fa fa-star fa-2x mb-2 d-block opacity-25"></i>No reviews yet.</div>
    <?php else: ?>
    <div class="hs-table-wrap">
    <table class="hs-table">
      <thead><tr>
        <th style="width:32px;"></th>
        <th style="min-width:120px;">Reviewer</th>
        <th style="min-width:80px;">Rating</th>
        <th style="min-width:180px;">Review</th>
        <th style="min-width:90px;">Source</th>
        <th>Status</th>
        <th style="min-width:76px;"></th>
      </tr></thead>
      <tbody id="revList">
        <?php foreach ($reviews as $r): ?>
        <tr data-id="<?= $r['id'] ?>">
          <td><span class="hs-drag-handle"><i class="fa fa-bars"></i></span></td>
          <td style="font-weight:600;"><?= e($r['name']) ?></td>
          <td style="white-space:nowrap;">
            <?php for($i=0;$i<$r['rating'];$i++): ?><i class="fa fa-star hs-star" style="font-size:11px;"></i><?php endfor; ?>
          </td>
          <td style="font-size:.78rem;color:var(--text-muted);max-width:220px;"><?= e(mb_strimwidth($r['review'],0,80,'…')) ?></td>
          <td>
            <span style="font-size:.72rem;text-transform:capitalize;background:var(--surface2);padding:3px 9px;border-radius:20px;white-space:nowrap;"><?= e($r['source']) ?></span>
          </td>
          <td>
            <span class="hs-badge <?= $r['status']?'on':'off' ?>" onclick="toggleItem('review',<?= $r['id'] ?>,this)">
              <i class="fa fa-circle" style="font-size:6px;"></i><?= $r['status']?'Active':'Inactive' ?>
            </span>
          </td>
          <td>
            <div class="hs-action-group">
              <button class="btn btn-sm btn-outline-primary" onclick='editRev(<?= json_encode($r) ?>)' title="Edit"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteItem('review',<?= $r['id'] ?>,this)" title="Delete"><i class="fa fa-trash"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endif; ?>
  </div>
</div>


<!-- ═══════════════════════════════════════════
     MODALS
═══════════════════════════════════════════ -->

<!-- Hero Modal -->
<div class="hs-modal-overlay" id="heroModal">
  <div class="hs-modal">
    <div class="hs-modal-head">
      <h5 id="heroModalTitle">Add Hero Section</h5>
      <button class="hs-close" onclick="closeModal('heroModal')">×</button>
    </div>
    <form id="heroForm" enctype="multipart/form-data">
      <div class="hs-modal-body">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" id="heroId" value="">
        <div class="hs-form-row">
          <label class="hs-label">Title *</label>
          <input type="text" name="title" class="hs-input" placeholder="Experience Every Beat…" required>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Subtitle</label>
          <textarea name="subtitle" class="hs-input" placeholder="Short description shown below the title…"></textarea>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Badge Text</label>
          <input type="text" name="badge_text" class="hs-input" placeholder="e.g. 1 Year Warranty">
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Product Category <span style="color:var(--text-muted);font-weight:400;">(products from this category will be shown)</span></label>
          <select name="category_id" class="hs-input">
            <option value="">— Select Category —</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= str_repeat('— ', (int)($c['depth']??0)) . e($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row g-2">
          <div class="col-12 col-sm-8">
            <div class="hs-form-row">
              <label class="hs-label">CTA Link <span style="color:var(--text-muted);font-weight:400;">(optional override)</span></label>
              <input type="text" name="link" class="hs-input" placeholder="/products">
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="hs-form-row">
              <label class="hs-label">Sort Order</label>
              <input type="number" name="sort_order" class="hs-input" value="0" min="0">
            </div>
          </div>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Status</label>
          <select name="status" class="hs-input">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="hs-modal-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeModal('heroModal')">Cancel</button>
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Collection Modal -->
<div class="hs-modal-overlay" id="collModal">
  <div class="hs-modal">
    <div class="hs-modal-head">
      <h5 id="collModalTitle">Add Collection</h5>
      <button class="hs-close" onclick="closeModal('collModal')">×</button>
    </div>
    <form id="collForm" enctype="multipart/form-data">
      <div class="hs-modal-body">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" id="collId" value="">
        <div class="hs-form-row">
          <label class="hs-label">Name *</label>
          <input type="text" name="name" class="hs-input" placeholder="e.g. Charging Adaptors" required>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Tagline</label>
          <input type="text" name="tagline" class="hs-input" placeholder="e.g. Power Faster">
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Card Image</label>
          <input type="file" name="image" class="hs-input" accept="image/*" onchange="previewImg(this,'collImgPrev')">
          <img id="collImgPrev" class="hs-img-preview" alt="">
        </div>
        <div class="row g-2">
          <div class="col-12 col-sm-8">
            <div class="hs-form-row">
              <label class="hs-label">Link</label>
              <input type="text" name="link" class="hs-input" placeholder="/category/charging-adaptors">
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="hs-form-row">
              <label class="hs-label">Sort Order</label>
              <input type="number" name="sort_order" class="hs-input" value="0" min="0">
            </div>
          </div>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Accent Color</label>
          <input type="color" name="color" id="collColorInput" class="hs-input" value="#2dc653" style="height:42px;padding:4px 8px;cursor:pointer;">
          <div class="hs-color-row" id="colorSwatches">
            <?php foreach (['#2dc653','#38bdf8','#a78bfa','#fb923c','#f43f5e','#facc15','#64748b'] as $col): ?>
            <div class="hs-color-swatch" style="background:<?= $col ?>;" data-color="<?= $col ?>" onclick="pickColor('<?= $col ?>')"></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Status</label>
          <select name="status" class="hs-input">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="hs-modal-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeModal('collModal')">Cancel</button>
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Review Modal -->
<div class="hs-modal-overlay" id="revModal">
  <div class="hs-modal">
    <div class="hs-modal-head">
      <h5 id="revModalTitle">Add Review</h5>
      <button class="hs-close" onclick="closeModal('revModal')">×</button>
    </div>
    <form id="revForm">
      <div class="hs-modal-body">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" id="revId" value="">
        <div class="row g-2">
          <div class="col-12 col-sm-7">
            <div class="hs-form-row">
              <label class="hs-label">Reviewer Name *</label>
              <input type="text" name="name" class="hs-input" placeholder="e.g. Kavindu P." required>
            </div>
          </div>
          <div class="col-12 col-sm-5">
            <div class="hs-form-row">
              <label class="hs-label">Source</label>
              <select name="source" class="hs-input">
                <option value="google">Google</option>
                <option value="facebook">Facebook</option>
                <option value="trustpilot">Trustpilot</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Rating</label>
          <div class="star-rating-input" id="starInput">
            <?php for ($i=5;$i>=1;$i--): ?>
            <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i===5?'checked':'' ?>>
            <label for="star<?= $i ?>" title="<?= $i ?> stars"><i class="fa fa-star"></i></label>
            <?php endfor; ?>
          </div>
        </div>
        <div class="hs-form-row">
          <label class="hs-label">Review Text *</label>
          <textarea name="review" class="hs-input" placeholder="Customer review…" required style="min-height:100px;"></textarea>
        </div>
        <div class="row g-2">
          <div class="col-6">
            <div class="hs-form-row">
              <label class="hs-label">Sort Order</label>
              <input type="number" name="sort_order" class="hs-input" value="0" min="0">
            </div>
          </div>
          <div class="col-6">
            <div class="hs-form-row">
              <label class="hs-label">Status</label>
              <select name="status" class="hs-input">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="hs-modal-footer">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeModal('revModal')">Cancel</button>
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>


<?php $extraScript = <<<'JS'
<script>
// ── Tabs ─────────────────────────────────────────
document.querySelectorAll('.hs-tab').forEach(function(tab) {
  tab.addEventListener('click', function() {
    document.querySelectorAll('.hs-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.hs-panel').forEach(p => p.classList.remove('active'));
    this.classList.add('active');
    document.getElementById(this.dataset.panel).classList.add('active');
  });
});

// ── Modal helpers ────────────────────────────────
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openModal(id)  { document.getElementById(id).classList.add('open'); }
document.querySelectorAll('.hs-modal-overlay').forEach(function(m) {
  m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
});

function previewImg(input, previewId) {
  var prev = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) { prev.src = e.target.result; prev.classList.add('show'); };
    reader.readAsDataURL(input.files[0]);
  }
}

// ── Hero ─────────────────────────────────────────
function openHeroModal() {
  document.getElementById('heroModalTitle').textContent = 'Add Hero Section';
  document.getElementById('heroForm').reset();
  document.getElementById('heroId').value = '';
  openModal('heroModal');
}
function editHero(h) {
  document.getElementById('heroModalTitle').textContent = 'Edit Hero Section';
  var f = document.getElementById('heroForm');
  f.reset();
  document.getElementById('heroId').value          = h.id;
  f.querySelector('[name="title"]').value           = h.title || '';
  f.querySelector('[name="subtitle"]').value        = h.subtitle || '';
  f.querySelector('[name="badge_text"]').value      = h.badge_text || '';
  f.querySelector('[name="category_id"]').value     = h.category_id || '';
  f.querySelector('[name="link"]').value            = h.link || '';
  f.querySelector('[name="sort_order"]').value      = h.sort_order || 0;
  f.querySelector('[name="status"]').value          = h.status;
  openModal('heroModal');
}
document.getElementById('heroForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var id  = document.getElementById('heroId').value;
  var url = id ? 'admin/home-sections/hero/update' : 'admin/home-sections/hero/store';
  var fd  = new FormData(this);
  fetch(SITE_URL + '/' + url, {method:'POST', body: fd})
    .then(r => r.json()).then(function(res) {
      if (res.success) { Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.href=SITE_URL+'/admin/home-sections?tab=hero'); }
      else Swal.fire({icon:'error',title:res.message||'Error'});
    });
});

// ── Collections ───────────────────────────────────
function openCollModal() {
  document.getElementById('collModalTitle').textContent = 'Add Collection';
  document.getElementById('collForm').reset();
  document.getElementById('collId').value = '';
  document.getElementById('collImgPrev').classList.remove('show');
  openModal('collModal');
}
function editColl(c) {
  document.getElementById('collModalTitle').textContent = 'Edit Collection';
  var f = document.getElementById('collForm');
  f.reset();
  document.getElementById('collId').value   = c.id;
  f.querySelector('[name="name"]').value     = c.name || '';
  f.querySelector('[name="tagline"]').value  = c.tagline || '';
  f.querySelector('[name="link"]').value     = c.link || '';
  f.querySelector('[name="color"]').value    = c.color || '#2dc653';
  f.querySelector('[name="sort_order"]').value = c.sort_order || 0;
  f.querySelector('[name="status"]').value   = c.status;
  var prev = document.getElementById('collImgPrev');
  if (c.image) { prev.src = SITE_URL + '/uploads/home/' + c.image; prev.classList.add('show'); }
  else         { prev.classList.remove('show'); }
  document.querySelectorAll('.hs-color-swatch').forEach(s => s.classList.toggle('selected', s.dataset.color === c.color));
  openModal('collModal');
}
function pickColor(col) {
  document.getElementById('collColorInput').value = col;
  document.querySelectorAll('.hs-color-swatch').forEach(s => s.classList.toggle('selected', s.dataset.color === col));
}
document.getElementById('collForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var id  = document.getElementById('collId').value;
  var url = id ? 'admin/home-sections/collection/update' : 'admin/home-sections/collection/store';
  fetch(SITE_URL + '/' + url, {method:'POST', body: new FormData(this)})
    .then(r => r.json()).then(function(res) {
      if (res.success) { Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.href=SITE_URL+'/admin/home-sections?tab=collections'); }
      else Swal.fire({icon:'error',title:res.message||'Error'});
    });
});

// ── Reviews ───────────────────────────────────────
function openRevModal() {
  document.getElementById('revModalTitle').textContent = 'Add Review';
  document.getElementById('revForm').reset();
  document.getElementById('revId').value = '';
  document.querySelector('#revForm [name="rating"][value="5"]').checked = true;
  openModal('revModal');
}
function editRev(r) {
  document.getElementById('revModalTitle').textContent = 'Edit Review';
  var f = document.getElementById('revForm');
  f.reset();
  document.getElementById('revId').value      = r.id;
  f.querySelector('[name="name"]').value       = r.name || '';
  f.querySelector('[name="review"]').value     = r.review || '';
  f.querySelector('[name="source"]').value     = r.source || 'google';
  f.querySelector('[name="sort_order"]').value = r.sort_order || 0;
  f.querySelector('[name="status"]').value     = r.status;
  var starEl = f.querySelector('[name="rating"][value="'+r.rating+'"]');
  if (starEl) starEl.checked = true;
  openModal('revModal');
}
document.getElementById('revForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var id  = document.getElementById('revId').value;
  var url = id ? 'admin/home-sections/review/update' : 'admin/home-sections/review/store';
  var fd  = new FormData(this);
  fetch(SITE_URL + '/' + url, {method:'POST', body: fd})
    .then(r => r.json()).then(function(res) {
      if (res.success) { Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.href=SITE_URL+'/admin/home-sections?tab=reviews'); }
      else Swal.fire({icon:'error',title:res.message||'Error'});
    });
});

// ── Toggle ────────────────────────────────────────
function toggleItem(type, id, el) {
  ajaxPost('admin/home-sections/'+type+'/toggle', {id: id}, function(res) {
    if (res.success) {
      var isOn = el.classList.contains('on');
      el.classList.toggle('on', !isOn);
      el.classList.toggle('off', isOn);
      el.innerHTML = '<i class="fa fa-circle" style="font-size:6px;"></i>' + (!isOn ? 'Active' : 'Inactive');
    }
  });
}

// ── Delete ────────────────────────────────────────
function deleteItem(type, id, btn) {
  Swal.fire({title:'Delete?',text:'This cannot be undone.',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Delete'})
  .then(function(r) {
    if (!r.isConfirmed) return;
    ajaxPost('admin/home-sections/'+type+'/delete', {id: id}, function(res) {
      if (res.success) { btn.closest('tr').remove(); }
    });
  });
}

// ── Drag-drop sort ────────────────────────────────
function initDragSort(tbodyId, tableName) {
  var tbody = document.getElementById(tbodyId);
  if (!tbody) return;
  var dragged = null;
  Array.from(tbody.querySelectorAll('tr')).forEach(function(row) {
    row.setAttribute('draggable', true);
    row.addEventListener('dragstart', function() { dragged = this; this.style.opacity='.4'; });
    row.addEventListener('dragend',   function() { this.style.opacity=''; saveOrder(tbody, tableName); });
    row.addEventListener('dragover',  function(e) { e.preventDefault(); var rect=this.getBoundingClientRect(); var mid=rect.top+rect.height/2; if(e.clientY<mid) tbody.insertBefore(dragged,this); else if(this.nextSibling) tbody.insertBefore(dragged,this.nextSibling); else tbody.appendChild(dragged); });
  });
}

function saveOrder(tbody, tableName) {
  var ids = Array.from(tbody.querySelectorAll('tr')).map(r => r.dataset.id);
  ajaxPost('admin/home-sections/reorder', {table: tableName, ids: JSON.stringify(ids)});
}

initDragSort('heroList', 'home_hero_sections');
initDragSort('collList', 'home_collections');
initDragSort('revList',  'home_reviews');
</script>
JS;
?>
