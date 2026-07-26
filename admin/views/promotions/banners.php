<?php /* admin/views/promotions/banners.php */
$positions = [
    'hero'       => ['label'=>'Hero Slider',    'icon'=>'fa-desktop',   'desc'=>'Full-width top slider'],
    'home_mid'   => ['label'=>'Home Middle',    'icon'=>'fa-th-large',  'desc'=>'Mid-page feature strip'],
    'home_promo' => ['label'=>'Promo Banners',  'icon'=>'fa-bullhorn',  'desc'=>'Promotional grid'],
];
$posColors = [
    'hero'       => ['bg'=>'var(--cyan-dim)',   'color'=>'var(--cyan)',   'border'=>'rgba(6,182,212,.25)'],
    'home_mid'   => ['bg'=>'var(--amber-dim)',  'color'=>'var(--amber)',  'border'=>'rgba(245,158,11,.25)'],
    'home_promo' => ['bg'=>'var(--green-dim)',  'color'=>'var(--green)',  'border'=>'rgba(16,185,129,.25)'],
];
$grouped = [];
foreach ($banners as $b) $grouped[$b['position']][] = $b;

// Stats
$totalActive = count(array_filter($banners, fn($b) => $b['status'] == 1));
$now = date('Y-m-d');

function bannerStatus(array $b, string $now): array {
    if (!$b['status']) return ['label'=>'Hidden',    'bg'=>'var(--surface2)',   'color'=>'var(--text-muted)', 'icon'=>'fa-eye-slash'];
    if ($b['start_date'] && $b['start_date'] > $now) return ['label'=>'Scheduled','bg'=>'var(--amber-dim)',  'color'=>'var(--amber)',      'icon'=>'fa-clock-o'];
    if ($b['end_date']   && $b['end_date']   < $now) return ['label'=>'Expired',  'bg'=>'var(--red-dim)',    'color'=>'var(--red)',         'icon'=>'fa-calendar-times-o'];
    return ['label'=>'Live', 'bg'=>'var(--green-dim)', 'color'=>'var(--green)', 'icon'=>'fa-circle'];
}
?>

<style>
/* ── Stats bar ── */
.bn-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:14px; margin-bottom:24px; }
.bn-stat { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:16px 18px;
           display:flex; align-items:center; gap:14px; }
.bn-stat-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.bn-stat-val  { font-size:22px; font-weight:800; color:var(--text); line-height:1; }
.bn-stat-lbl  { font-size:11px; color:var(--text-muted); margin-top:2px; }

/* ── Section header ── */
.bn-section { margin-bottom:32px; }
.bn-section-head { display:flex; align-items:center; gap:10px; margin-bottom:16px; padding-bottom:12px;
                   border-bottom:2px solid var(--border); }
.bn-section-pill { display:inline-flex; align-items:center; gap:6px; padding:5px 13px;
                   border-radius:20px; font-size:12px; font-weight:700; }
.bn-section-meta { font-size:12px; color:var(--text-muted); }
.bn-section-add  { margin-left:auto; }

/* ── Banner card ── */
.bn-card { background:var(--surface); border:1px solid var(--border); border-radius:14px;
           overflow:hidden; transition:.2s ease; position:relative; }
.bn-card:hover { box-shadow:0 8px 28px rgba(0,0,0,.18); transform:translateY(-2px); }

/* Image with 16:5 aspect ratio (banner proportion) */
.bn-card__img-wrap { position:relative; width:100%; padding-top:31.25%; /* 16:5 */ overflow:hidden; background:var(--surface2); }
.bn-card__img-wrap img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; transition:.3s ease; }
.bn-card:hover .bn-card__img-wrap img { transform:scale(1.04); }
.bn-card__img-placeholder { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--text-muted); gap:8px; }

/* Hover overlay */
.bn-card__overlay { position:absolute; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(2px);
                    display:flex; align-items:center; justify-content:center; gap:8px;
                    opacity:0; transition:.2s ease; }
.bn-card:hover .bn-card__overlay { opacity:1; }
.bn-ov-btn { border:none; border-radius:8px; padding:8px 14px; font-size:12px; font-weight:700;
             cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:.15s; }
.bn-ov-edit   { background:#fff; color:#1a1a1a; }
.bn-ov-toggle { background:rgba(255,255,255,.18); color:#fff; border:1px solid rgba(255,255,255,.3); }
.bn-ov-del    { background:rgba(239,68,68,.85); color:#fff; }
.bn-ov-btn:hover { transform:scale(1.05); }

/* Status badge on image */
.bn-status-chip { position:absolute; top:10px; left:10px; z-index:2;
                  display:inline-flex; align-items:center; gap:5px;
                  padding:3px 9px; border-radius:20px; font-size:10px; font-weight:700;
                  backdrop-filter:blur(8px); }
/* Position accent bar */
.bn-pos-bar { position:absolute; top:0; right:0; width:4px; height:100%; opacity:.7; }

/* Card body */
.bn-card__body { padding:13px 15px; }
.bn-card__title { font-size:14px; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:3px; }
.bn-card__sub   { font-size:11px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:8px; }
.bn-card__meta  { display:flex; flex-wrap:wrap; gap:5px; }
.bn-meta-chip   { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:20px;
                  font-size:10px; font-weight:600; background:var(--surface2); color:var(--text-muted); }
.bn-meta-chip i { font-size:9px; }

/* Mobile actions strip (always visible) */
.bn-card__actions { display:flex; gap:6px; padding:10px 15px; border-top:1px solid var(--border); }
.bn-act-btn { flex:1; border:none; border-radius:7px; padding:6px 0; font-size:11px; font-weight:700;
              cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:4px; transition:.15s; }
.bn-act-edit   { background:var(--cyan-dim);  color:var(--cyan); }
.bn-act-toggle { background:var(--surface2);  color:var(--text-muted); }
.bn-act-toggle.is-live { background:var(--green-dim); color:var(--green); }
.bn-act-del    { background:var(--red-dim);   color:var(--red); flex:0; padding:6px 12px; }

/* Empty state */
.bn-empty { text-align:center; padding:52px 24px; background:var(--surface); border:2px dashed var(--border);
            border-radius:14px; color:var(--text-muted); }
.bn-empty i { font-size:40px; opacity:.3; margin-bottom:16px; display:block; }

/* ── Position picker in modal ── */
.pos-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:6px; }
.pos-btn  { border:2px solid var(--border); background:var(--surface2); border-radius:8px;
            padding:10px 4px; cursor:pointer; font-size:11px; text-align:center;
            color:var(--text-muted); transition:.15s; line-height:1.4; user-select:none; }
.pos-btn:hover  { border-color:var(--cyan); color:var(--cyan); }
.pos-btn.active { border-color:var(--cyan); background:var(--cyan-dim); color:var(--cyan); font-weight:700; }

/* ── Upload drop zone ── */
.bn-dropzone { border:2px dashed var(--border); border-radius:10px; padding:20px; text-align:center;
               cursor:pointer; transition:.2s; color:var(--text-muted); position:relative; }
.bn-dropzone:hover, .bn-dropzone.drag-over { border-color:var(--cyan); background:var(--cyan-dim); color:var(--cyan); }
.bn-dropzone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
.bn-dropzone-preview { width:100%; max-height:180px; object-fit:cover; border-radius:8px;
                       border:1px solid var(--border); display:block; margin-bottom:8px; }
</style>

<!-- ── Stats bar ── -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Banners</h4>
    <p class="page-subtitle">Manage hero sliders, promo strips and featured banners.</p>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openAddModal()">
    <i class="fa fa-plus me-1"></i>Add Banner
  </button>
</div>

<div class="bn-stats">
  <div class="bn-stat">
    <div class="bn-stat-icon" style="background:var(--cyan-dim);color:var(--cyan);"><i class="fa fa-image"></i></div>
    <div>
      <div class="bn-stat-val"><?= count($banners) ?></div>
      <div class="bn-stat-lbl">Total Banners</div>
    </div>
  </div>
  <div class="bn-stat">
    <div class="bn-stat-icon" style="background:var(--green-dim);color:var(--green);"><i class="fa fa-circle"></i></div>
    <div>
      <div class="bn-stat-val"><?= $totalActive ?></div>
      <div class="bn-stat-lbl">Live</div>
    </div>
  </div>
  <?php foreach ($positions as $pk => $pv): ?>
  <div class="bn-stat">
    <div class="bn-stat-icon" style="background:<?= $posColors[$pk]['bg'] ?>;color:<?= $posColors[$pk]['color'] ?>;"><i class="fa <?= $pv['icon'] ?>"></i></div>
    <div>
      <div class="bn-stat-val"><?= count($grouped[$pk] ?? []) ?></div>
      <div class="bn-stat-lbl"><?= $pv['label'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ── Banners by position ── -->
<?php if (empty($banners)): ?>
<div class="bn-empty">
  <i class="fa fa-image"></i>
  <p style="font-size:15px;font-weight:600;margin-bottom:6px;">No banners yet</p>
  <p style="font-size:13px;margin-bottom:20px;">Add your first banner to start showcasing deals and promotions.</p>
  <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fa fa-plus me-1"></i>Add First Banner</button>
</div>

<?php else: foreach ($positions as $posKey => $posInfo):
  if (empty($grouped[$posKey])) continue;
  $pc = $posColors[$posKey];
?>
<div class="bn-section">
  <div class="bn-section-head">
    <span class="bn-section-pill" style="background:<?= $pc['bg'] ?>;color:<?= $pc['color'] ?>;border:1px solid <?= $pc['border'] ?>;">
      <i class="fa <?= $posInfo['icon'] ?>"></i><?= $posInfo['label'] ?>
    </span>
    <span class="bn-section-meta"><?= $posInfo['desc'] ?> &nbsp;·&nbsp; <?= count($grouped[$posKey]) ?> banner<?= count($grouped[$posKey])!==1?'s':'' ?></span>
    <button class="btn btn-sm bn-section-add" style="background:<?= $pc['bg'] ?>;color:<?= $pc['color'] ?>;border:1px solid <?= $pc['border'] ?>;"
            onclick="openAddModal('<?= $posKey ?>')">
      <i class="fa fa-plus me-1"></i>Add
    </button>
  </div>

  <div class="row g-3">
    <?php foreach ($grouped[$posKey] as $b):
      $bs  = bannerStatus($b, $now);
      $isLive = $bs['label'] === 'Live';
    ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
      <div class="bn-card" id="bn-card-<?= $b['id'] ?>">

        <!-- Image -->
        <div class="bn-card__img-wrap">
          <?php if ($b['image']): ?>
          <img src="<?= url('uploads/banners/'.e($b['image'])) ?>" alt="<?= e($b['title'] ?: 'Banner') ?>">
          <?php else: ?>
          <div class="bn-card__img-placeholder">
            <i class="fa fa-image" style="font-size:28px;opacity:.3;"></i>
            <span style="font-size:11px;">No image</span>
          </div>
          <?php endif; ?>

          <!-- Status chip -->
          <span class="bn-status-chip" style="background:<?= $bs['bg'] ?>;color:<?= $bs['color'] ?>;">
            <i class="fa <?= $bs['icon'] ?>" style="font-size:8px;"></i><?= $bs['label'] ?>
          </span>

          <!-- Position accent bar -->
          <div class="bn-pos-bar" style="background:<?= $pc['color'] ?>;"></div>

          <!-- Hover overlay -->
          <div class="bn-card__overlay">
            <button class="bn-ov-btn bn-ov-edit" onclick='openEditModal(<?= json_encode($b) ?>)'>
              <i class="fa fa-pencil"></i>Edit
            </button>
            <button class="bn-ov-btn bn-ov-toggle toggle-banner" data-id="<?= $b['id'] ?>" data-status="<?= $b['status'] ?>">
              <i class="fa fa-<?= $isLive ? 'eye-slash' : 'eye' ?>"></i><?= $isLive ? 'Hide' : 'Show' ?>
            </button>
            <button class="bn-ov-btn bn-ov-del del-banner" data-id="<?= $b['id'] ?>">
              <i class="fa fa-trash"></i>
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="bn-card__body">
          <div class="bn-card__title"><?= $b['title'] ? e($b['title']) : '<span style="color:var(--text-muted);font-weight:400;">Untitled Banner</span>' ?></div>
          <?php if ($b['subtitle']): ?>
          <div class="bn-card__sub"><?= e($b['subtitle']) ?></div>
          <?php endif; ?>
          <div class="bn-card__meta">
            <span class="bn-meta-chip"><i class="fa fa-crosshairs"></i><?= e($b['desc_position'] ?? 'mid-left') ?></span>
            <span class="bn-meta-chip"><i class="fa fa-sort-numeric-asc"></i><?= $b['sort_order'] ?></span>
            <?php if ($b['link']): ?>
            <span class="bn-meta-chip" style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= e($b['link']) ?>">
              <i class="fa fa-link"></i><?= e($b['link']) ?>
            </span>
            <?php endif; ?>
            <?php if ($b['start_date'] || $b['end_date']): ?>
            <span class="bn-meta-chip"><i class="fa fa-calendar"></i>
              <?= $b['start_date'] ? date('M j', strtotime($b['start_date'])) : '∞' ?>
              → <?= $b['end_date']   ? date('M j', strtotime($b['end_date']))   : '∞' ?>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Mobile actions -->
        <div class="bn-card__actions">
          <button class="bn-act-btn bn-act-edit" onclick='openEditModal(<?= json_encode($b) ?>)'>
            <i class="fa fa-pencil"></i>Edit
          </button>
          <button class="bn-act-btn bn-act-toggle toggle-banner <?= $isLive ? 'is-live' : '' ?>" data-id="<?= $b['id'] ?>" data-status="<?= $b['status'] ?>">
            <i class="fa fa-<?= $isLive ? 'eye' : 'eye-slash' ?>"></i><?= $isLive ? 'Live' : 'Hidden' ?>
          </button>
          <button class="bn-act-btn bn-act-del del-banner" data-id="<?= $b['id'] ?>">
            <i class="fa fa-trash"></i>
          </button>
        </div>

      </div>
    </div>
    <?php endforeach; ?>

    <!-- Add-to-section card -->
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
      <div class="bn-empty" style="min-height:160px;padding:24px;border-color:<?= $pc['border'] ?>;" onclick="openAddModal('<?= $posKey ?>')" role="button" tabindex="0">
        <i class="fa fa-plus-circle" style="font-size:24px;opacity:.4;margin-bottom:8px;"></i>
        <p style="font-size:12px;margin:0;">Add <?= $posInfo['label'] ?> banner</p>
      </div>
    </div>
  </div>
</div>
<?php endforeach; endif; ?>


<!-- ═══════════════════ ADD / EDIT MODAL ═══════════════════ -->
<div class="modal-overlay" id="bannerModal" style="display:none;">
  <div class="modal-box modal-box-lg">

    <div class="modal-header">
      <span class="modal-title" id="modalTitle">Add Banner</span>
      <button class="modal-close" onclick="closeBannerModal()">×</button>
    </div>

    <form id="bannerForm" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" id="fId" value="">
      <input type="hidden" name="desc_position" id="fDescPos" value="middle-left">

      <div class="modal-body" style="display:flex;flex-direction:column;gap:18px;">

        <!-- Drop-zone image upload -->
        <div>
          <label class="form-label">Banner Image</label>
          <div class="bn-dropzone" id="dropZone">
            <input type="file" name="image" id="fImage" accept="image/*">
            <img id="imgPreview" src="" class="bn-dropzone-preview" style="display:none;" alt="Preview">
            <div id="dropMsg">
              <i class="fa fa-cloud-upload" style="font-size:26px;display:block;margin-bottom:8px;opacity:.4;"></i>
              <div style="font-size:13px;font-weight:600;color:var(--text-dim);">Drop image here or click to browse</div>
              <div style="font-size:11px;margin-top:4px;color:var(--text-muted);">JPEG, PNG, WebP — recommended 1920×600 px</div>
            </div>
          </div>
          <p id="imgEditNote" style="display:none;font-size:11px;color:var(--text-muted);margin:6px 0 0;">
            <i class="fa fa-info-circle me-1"></i>Upload a new image to replace the current one.
          </p>
        </div>

        <!-- Title / Subtitle -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="form-label">Title</label>
            <input type="text" name="title" id="fTitle" class="form-control" placeholder="e.g. Summer Sale — 50% Off">
          </div>
          <div>
            <label class="form-label">Subtitle / Tag</label>
            <input type="text" name="subtitle" id="fSubtitle" class="form-control" placeholder="e.g. Limited Time Only">
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="form-label">Body Text <span style="text-transform:none;font-weight:400;">(optional)</span></label>
          <textarea name="description" id="fDescription" class="form-control" rows="2" placeholder="Short paragraph shown on the banner…"></textarea>
        </div>

        <!-- Text position picker -->
        <div>
          <label class="form-label">Text Position on Banner</label>
          <div class="pos-grid" id="posPicker">
            <?php
            $posOpts = [
              'top-left'=>'↖ Top Left','top-center'=>'↑ Top','top-right'=>'↗ Top Right',
              'middle-left'=>'← Mid Left','middle-center'=>'⊙ Centre','middle-right'=>'→ Mid Right',
              'bottom-left'=>'↙ Bot Left','bottom-center'=>'↓ Bottom','bottom-right'=>'↘ Bot Right',
            ];
            foreach ($posOpts as $pv => $pl): ?>
            <div class="pos-btn" data-val="<?= $pv ?>"><?= $pl ?></div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Text colour / Banner position / Status / Sort -->
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 80px;gap:12px;">
          <div>
            <label class="form-label">Text Colour</label>
            <select name="text_color" id="fTextColor" class="form-control">
              <option value="light">Light (white)</option>
              <option value="dark">Dark (black)</option>
            </select>
          </div>
          <div>
            <label class="form-label">Position</label>
            <select name="position" id="fPosition" class="form-control">
              <option value="hero">Hero Slider</option>
              <option value="home_mid">Home Middle</option>
              <option value="home_promo">Promo Banners</option>
            </select>
          </div>
          <div>
            <label class="form-label">Status</label>
            <select name="status" id="fStatus" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div>
            <label class="form-label">Sort</label>
            <input type="number" name="sort_order" id="fSort" class="form-control" value="0" min="0">
          </div>
        </div>

        <!-- Link URL -->
        <div>
          <label class="form-label">CTA Link URL</label>
          <input type="text" name="link" id="fLink" class="form-control" placeholder="/products or /category/electronics">
        </div>

        <!-- Schedule dates -->
        <div>
          <label class="form-label">Schedule <span style="text-transform:none;font-weight:400;">(optional — leave blank to always show)</span></label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label style="font-size:11px;color:var(--text-muted);margin-bottom:4px;display:block;text-transform:none;font-weight:normal;letter-spacing:0;">Start Date</label>
              <input type="date" name="start_date" id="fStartDate" class="form-control">
            </div>
            <div>
              <label style="font-size:11px;color:var(--text-muted);margin-bottom:4px;display:block;text-transform:none;font-weight:normal;letter-spacing:0;">End Date</label>
              <input type="date" name="end_date" id="fEndDate" class="form-control">
            </div>
          </div>
        </div>

      </div><!-- /modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeBannerModal()">Cancel</button>
        <button type="submit" id="formSubmitBtn" class="btn btn-primary">
          <i class="fa fa-save me-1"></i>Save Banner
        </button>
      </div>
    </form>

  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
// ── Position picker ───────────────────────────────────────────
function setPos(val) {
  $('#fDescPos').val(val);
  $('#posPicker .pos-btn').each(function() {
    $(this).toggleClass('active', $(this).data('val') === val);
  });
}
$('#posPicker').on('click', '.pos-btn', function() { setPos($(this).data('val')); });

// ── Drop-zone / file preview ──────────────────────────────────
var dropZone = document.getElementById('dropZone');
['dragover','dragenter'].forEach(e => dropZone.addEventListener(e, function(ev) {
  ev.preventDefault(); dropZone.classList.add('drag-over');
}));
['dragleave','drop'].forEach(e => dropZone.addEventListener(e, function(ev) {
  ev.preventDefault(); dropZone.classList.remove('drag-over');
  if (ev.type === 'drop' && ev.dataTransfer.files.length) {
    document.getElementById('fImage').files = ev.dataTransfer.files;
    previewImage(ev.dataTransfer.files[0]);
  }
}));
$('#fImage').on('change', function() {
  if (this.files && this.files[0]) previewImage(this.files[0]);
});
function previewImage(file) {
  var reader = new FileReader();
  reader.onload = function(e) {
    var img = document.getElementById('imgPreview');
    img.src = e.target.result;
    img.style.display = 'block';
    document.getElementById('dropMsg').style.display = 'none';
  };
  reader.readAsDataURL(file);
}

// ── Modal open / close ───────────────────────────────────────
function openBannerModal() { document.getElementById('bannerModal').style.display = 'flex'; }
function closeBannerModal() { document.getElementById('bannerModal').style.display = 'none'; }
document.getElementById('bannerModal').addEventListener('click', function(e) {
  if (e.target === this) closeBannerModal();
});

// ── Open Add modal ───────────────────────────────────────────
function openAddModal(pos) {
  $('#modalTitle').text('Add Banner');
  $('#bannerForm')[0].reset();
  $('#fId').val('');
  $('#fImage').prop('required', true);
  $('#imgPreview').hide().attr('src','');
  $('#dropMsg').show();
  $('#imgEditNote').hide();
  if (pos) $('#fPosition').val(pos);
  setPos('middle-left');
  openBannerModal();
}

// ── Open Edit modal ──────────────────────────────────────────
function openEditModal(b) {
  $('#modalTitle').text('Edit Banner');
  $('#fId').val(b.id);
  $('#fTitle').val(b.title || '');
  $('#fSubtitle').val(b.subtitle || '');
  $('#fDescription').val(b.description || '');
  $('#fLink').val(b.link || '');
  $('#fPosition').val(b.position || 'hero');
  $('#fSort').val(b.sort_order || 0);
  $('#fStatus').val(b.status != null ? b.status : 1);
  $('#fStartDate').val(b.start_date || '');
  $('#fEndDate').val(b.end_date || '');
  $('#fTextColor').val(b.text_color || 'light');
  $('#fImage').prop('required', false).val('');
  $('#dropMsg').show();
  if (b.image) {
    var img = document.getElementById('imgPreview');
    img.src = SITE_URL + '/uploads/banners/' + b.image;
    img.style.display = 'block';
    document.getElementById('dropMsg').style.display = 'none';
    $('#imgEditNote').show();
  } else {
    $('#imgPreview').hide().attr('src','');
    $('#imgEditNote').hide();
  }
  setPos(b.desc_position || 'middle-left');
  openBannerModal();
}

// ── Form submit ───────────────────────────────────────────────
$('#bannerForm').on('submit', function(e) {
  e.preventDefault();
  var btn    = $('#formSubmitBtn');
  var isEdit = !!$('#fId').val();
  var fd     = new FormData(this);
  fd.set('_csrf', CSRF_TOKEN);
  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Saving…');
  $.ajax({
    url: SITE_URL + '/admin/banners/' + (isEdit ? 'update' : 'store'),
    type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
    success: function(res) {
      btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save Banner');
      if (res.success) {
        SwalDark.fire({ icon:'success', title:res.message, timer:1300, showConfirmButton:false })
          .then(() => location.reload());
      } else {
        SwalDark.fire('Error', res.message, 'error');
      }
    },
    error: function() {
      btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save Banner');
      SwalDark.fire('Error', 'Request failed. Please try again.', 'error');
    }
  });
});

// ── Toggle status ─────────────────────────────────────────────
$(document).on('click', '.toggle-banner', function() {
  var id = $(this).data('id');
  ajaxPost('admin/banners/toggle', { id: id }, function(res) {
    if (res.success) location.reload();
    else SwalDark.fire('Error', res.message, 'error');
  });
});

// ── Delete ────────────────────────────────────────────────────
$(document).on('click', '.del-banner', function(e) {
  e.stopPropagation();
  var id = $(this).data('id');
  SwalDark.fire({
    title: 'Delete this banner?',
    text: 'The image file will also be permanently removed.',
    icon: 'warning', showCancelButton: true,
    confirmButtonText: 'Delete', confirmButtonColor: '#ef4444'
  }).then(function(r) {
    if (!r.isConfirmed) return;
    ajaxPost('admin/banners/delete', { id: id }, function(res) {
      if (res.success) {
        $('#bn-card-' + id).closest('.col-12').fadeOut(300, function() { $(this).remove(); });
        SwalDark.fire({ icon:'success', title: res.message, timer:1200, showConfirmButton:false });
      } else {
        SwalDark.fire('Error', res.message, 'error');
      }
    });
  });
});
</script>
JS;
?>
