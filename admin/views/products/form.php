<?php
$isEdit   = !empty($product);
$pageTitle = $isEdit ? 'Edit: '.e($product['name']) : 'Add Product';
// $allPm and $activePm are passed by the controller
if (!isset($allPm))    $allPm    = [];
if (!isset($activePm)) $activePm = [];
?>
<style>
/* ── Variations: responsive grid ────────────────────────── */
.var-header-row {
    display: grid;
    grid-template-columns: 1fr 90px 110px 80px 36px;
    gap: 6px;
    padding: 0 0 6px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 8px;
    align-items: center;
}
.variation-row {
    display: grid;
    grid-template-columns: 1fr 90px 110px 80px 36px;
    gap: 6px;
    margin-bottom: 6px;
    align-items: center;
}
.variation-row input[type="hidden"] { display: none; }

.remove-variation {
    background: var(--red-dim);
    color: var(--red);
    border: none;
    border-radius: var(--radius-sm);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 31px;
    padding: 0;
    flex-shrink: 0;
    cursor: pointer;
    transition: var(--transition);
}
.remove-variation:hover { background: rgba(239,68,68,0.35); color: var(--red); }

/* Tablet with sidebar visible (768–991 px) — tighten columns */
@media (min-width: 768px) and (max-width: 991px) {
    .var-header-row,
    .variation-row { grid-template-columns: 1fr 76px 96px 66px 32px; gap: 4px; }
    .remove-variation { width: 32px; height: 32px; }
}

/* Mobile (<768 px) — stacked card per row */
@media (max-width: 767px) {
    .var-header-row { display: none; }

    .variation-row {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: auto auto;
        gap: 6px;
        padding: 10px 42px 10px 10px;
        background: rgba(255,255,255,0.035);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        margin-bottom: 10px;
    }
    .var-name-input { grid-column: 1 / -1; }

    .remove-variation {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
    }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><?= $pageTitle ?></h4>
    <a href="<?= url('admin/products') ?>" class="btn btn-secondary btn-sm">← Back to Products</a>
</div>

<form id="productForm" enctype="multipart/form-data">
    <?= CSRF::field() ?>
    <?php if ($isEdit): ?><input type="hidden" name="_product_id" value="<?= $product['id'] ?>"><?php endif; ?>

    <div class="row g-3">
        <!-- ── Left column ─────────────────────────────────── -->
        <div class="col-lg-8">

            <!-- Basic info (English only) -->
            <div class="card mb-3">
                <div class="card-header">
                    <span class="card-title"><i class="fa fa-info-circle me-2"></i>Basic Information</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= e($product['name'] ?? '') ?>" id="productNameInput"
                               placeholder="Enter product name">
                    </div>

                    <div class="form-group">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            URL Slug
                            <button type="button" id="btnRegenSlug" class="btn btn-secondary btn-sm" style="font-size:.72rem;padding:2px 8px;">↻ Regenerate</button>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="font-size:.78rem;opacity:.6;">/product/</span>
                            <input type="text" name="slug" id="slugInput" class="form-control"
                                   value="<?= e($product['slug'] ?? '') ?>" placeholder="auto-generated-from-name">
                        </div>
                        <small style="color:var(--text-muted);">Auto-generated from name. Used in the product URL.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_desc" class="form-control" rows="2"
                                  placeholder="Brief summary shown in product listings"><?= e($product['short_desc'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            Full Description
                            <button type="button" class="btn btn-primary btn-sm" id="aiDescBtn" style="font-size:.75rem;">
                                ✨ AI Generate
                            </button>
                        </label>
                        <textarea name="description" id="descriptionEn" class="form-control" rows="7"
                                  placeholder="Full product description..."><?= $product['description'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div class="card mb-3">
                <div class="card-header">
                    <span class="card-title"><i class="fa fa-dollar me-2"></i>Pricing & Stock</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 form-group">
                            <label class="form-label">Regular Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">LKR</span>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" required
                                       value="<?= $product['price'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Sale Price</label>
                            <div class="input-group">
                                <span class="input-group-text">LKR</span>
                                <input type="number" name="sale_price" class="form-control" step="0.01" min="0"
                                       value="<?= $product['sale_price'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">LKR</span>
                                <input type="number" name="cost_price" class="form-control" step="0.01" min="0"
                                       value="<?= $product['cost_price'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" class="form-control" step="0.1" min="0" max="100"
                                   value="<?= $product['tax_rate'] ?? 0 ?>">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                SKU
                                <button type="button" id="btnGenSKU" class="btn btn-secondary btn-sm" style="font-size:.72rem;padding:2px 6px;">↻</button>
                            </label>
                            <input type="text" name="sku" id="skuInput" class="form-control"
                                   value="<?= e($product['sku'] ?? '') ?>" placeholder="AUTO-0000">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" name="weight" class="form-control" step="0.01" min="0"
                                   value="<?= $product['weight'] ?? 0 ?>">
                        </div>
                        <?php if (!$isEdit): ?>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" name="initial_stock" class="form-control" min="0" value="0">
                        </div>
                        <?php else: ?>
                        <div class="col-md-3 form-group">
                            <label class="form-label">Current Stock</label>
                            <input type="text" class="form-control" value="<?= $product['stock'] ?>" disabled
                                   style="opacity:.7;">
                            <small style="color:var(--text-muted);">Adjust via <a href="<?= url('admin/stock?product='.$product['id']) ?>">Stock Manager</a></small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Variations -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="card-title"><i class="fa fa-list-ul me-2"></i>Variations <small style="font-weight:400;color:var(--text-muted);">(optional)</small></span>
                    <button type="button" class="btn btn-success btn-sm" id="addVariation">
                        <i class="fa fa-plus me-1"></i>Add Variation
                    </button>
                </div>
                <div class="card-body">
                    <div class="var-header-row">
                        <span style="font-size:11px;font-weight:600;color:var(--text-muted);">NAME</span>
                        <span style="font-size:11px;font-weight:600;color:var(--text-muted);">SKU</span>
                        <span style="font-size:11px;font-weight:600;color:var(--text-muted);">PRICE (LKR)</span>
                        <span style="font-size:11px;font-weight:600;color:var(--text-muted);">STOCK</span>
                        <span></span>
                    </div>
                    <div id="variationsContainer">
                        <?php foreach (($product['variations'] ?? []) as $vi => $var): ?>
                        <div class="variation-row">
                            <input type="hidden" name="variations[<?=$vi?>][id]" value="<?= (int)($var['id'] ?? 0) ?>">
                            <input type="text"   name="variations[<?=$vi?>][name]"  class="form-control form-control-sm var-name-input" placeholder="e.g. Blue - L" value="<?= e($var['name']) ?>">
                            <input type="text"   name="variations[<?=$vi?>][sku]"   class="form-control form-control-sm var-sku-input"  placeholder="SKU" value="<?= e($var['sku']??'') ?>">
                            <input type="number" name="variations[<?=$vi?>][price]" class="form-control form-control-sm var-price-input" placeholder="Price" step="0.01" value="<?= $var['price']??'' ?>">
                            <input type="number" name="variations[<?=$vi?>][stock]" class="form-control form-control-sm var-stock-input" placeholder="0" value="<?= $var['stock']??0 ?>">
                            <button type="button" class="btn btn-sm remove-variation"><i class="fa fa-trash"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p id="noVarsMsg" class="text-muted small mb-0" style="<?= !empty($product['variations']) ? 'display:none' : '' ?>">
                        No variations added. Click "Add Variation" to create size, colour or other options.
                    </p>
                </div>
            </div>

        </div>

        <!-- ── Right column ─────────────────────────────────── -->
        <div class="col-lg-4">

            <!-- Thumbnail -->
            <div class="card mb-3">
                <div class="card-header"><span class="card-title"><i class="fa fa-image me-2"></i>Thumbnail *</span></div>
                <div class="card-body">
                    <?php if (!empty($product['thumbnail'])): ?>
                    <img src="<?= url('uploads/products/'.$product['thumbnail']) ?>"
                         class="img-fluid rounded mb-2" id="thumbPreview"
                         style="max-height:160px;width:100%;object-fit:cover;border-radius:var(--radius-sm);">
                    <?php else: ?>
                    <div id="thumbPreview" style="height:120px;background:var(--surface);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;margin-bottom:8px;">
                        <i class="fa fa-image" style="font-size:32px;color:var(--text-muted);"></i>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*" id="thumbInput">
                    <small style="color:var(--text-muted);">Auto-converted to WebP. Max 10MB.</small>
                </div>
            </div>

            <!-- Gallery -->
            <div class="card mb-3">
                <div class="card-header"><span class="card-title"><i class="fa fa-photo me-2"></i>Gallery Images</span></div>
                <div class="card-body">
                    <?php if (!empty($product['images'])): ?>
                    <div class="d-flex flex-wrap gap-1 mb-2" id="galleryGrid">
                        <?php foreach ($product['images'] as $img): ?>
                        <div style="position:relative;width:58px;height:58px;" class="gallery-thumb-wrap">
                            <img src="<?= url('uploads/products/'.$img['image']) ?>"
                                 style="width:58px;height:58px;object-fit:cover;border-radius:6px;">
                            <button type="button" class="del-img-btn"
                                    data-image-id="<?= $img['id'] ?>"
                                    data-product-id="<?= $product['id'] ?>"
                                    style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;border-radius:50%;
                                           background:var(--red);color:#fff;border:none;cursor:pointer;font-size:10px;
                                           display:flex;align-items:center;justify-content:center;line-height:1;">×</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="images[]" class="form-control form-control-sm" accept="image/*" multiple>
                    <small style="color:var(--text-muted);">Select multiple. Appended to existing gallery.</small>
                </div>
            </div>

            <!-- Classification -->
            <div class="card mb-3">
                <div class="card-header"><span class="card-title"><i class="fa fa-th-large me-2"></i>Classification</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">— Select category —</option>
                            <?php foreach ($categories as $cat):
                                $prefix   = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $cat['depth']);
                                $arrow    = $cat['depth'] > 0 ? '↳ ' : '';
                                $selected = ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '';
                            ?>
                            <option value="<?= $cat['id'] ?>" <?= $selected ?>><?= $prefix . $arrow . e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($categories)): ?>
                        <small style="color:var(--amber);">⚠ No categories found. <a href="<?= url('admin/categories') ?>">Add categories first.</a></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-control">
                            <option value="">No Brand</option>
                            <?php foreach ($brands as $br): ?>
                            <option value="<?= $br['id'] ?>" <?= ($product['brand_id']??'')==$br['id']?'selected':'' ?>><?= e($br['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vendor</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">No Vendor</option>
                            <?php foreach ($vendors as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= ($product['vendor_id']??'')==$v['id']?'selected':'' ?>><?= e($v['company_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            Tags
                            <button type="button" id="autoTagsBtn" class="btn btn-secondary btn-sm" style="font-size:.72rem;padding:2px 8px;">
                                ✦ Auto Tags
                            </button>
                        </label>
                        <input type="text" name="tags" id="tagsInput" class="form-control"
                               value="<?= e($product['tags'] ?? '') ?>"
                               placeholder="electronics, phone, android" autocomplete="off">
                        <div id="tagChips" style="display:flex;flex-wrap:wrap;gap:4px;margin-top:6px;"></div>
                        <small style="color:var(--text-muted);">Comma-separated. Click ✦ to auto-generate from name &amp; description.</small>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card mb-3">
                <div class="card-header"><span class="card-title"><i class="fa fa-credit-card me-2"></i>Allowed Payment Methods</span></div>
                <div class="card-body">
                    <small style="color:var(--text-muted);display:block;margin-bottom:10px;">
                        Uncheck to hide a payment option for this product only.
                        If none selected, all active methods are shown.
                    </small>
                    <?php if (empty($allPm)): ?>
                    <div style="color:var(--amber);font-size:13px;">
                        ⚠ No active payment methods found.
                        <a href="<?= url('admin/payments') ?>" target="_blank">Manage payment methods →</a>
                    </div>
                    <?php else: foreach ($allPm as $pm): ?>
                    <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid var(--border);">
                        <input type="checkbox" name="payment_methods[]"
                               id="pm_<?= e($pm['code']) ?>"
                               value="<?= e($pm['code']) ?>"
                               class="form-check-input"
                               style="width:16px;height:16px;margin:0;flex-shrink:0;"
                               <?= (empty($activePm) || in_array($pm['code'], $activePm)) ? 'checked' : '' ?>>
                        <label for="pm_<?= e($pm['code']) ?>" style="margin:0;cursor:pointer;font-size:13px;">
                            <span style="font-size:15px;margin-right:4px;"><?= e($pm['icon'] ?? '') ?></span>
                            <?= e($pm['display_name']) ?>
                        </label>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Status & Options -->
            <div class="card mb-3">
                <div class="card-header"><span class="card-title"><i class="fa fa-toggle-on me-2"></i>Status & Options</span></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active"   <?= ($product['status']??'active')==='active'  ?'selected':'' ?>>Active</option>
                            <option value="inactive" <?= ($product['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
                            <option value="draft"    <?= ($product['status']??'')==='draft'   ?'selected':'' ?>>Draft</option>
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" style="width:16px;height:16px;margin:0;"
                                   <?= !empty($product['is_featured'])?'checked':'' ?>> Featured Product
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;">
                            <input type="checkbox" name="is_new" value="1" class="form-check-input" style="width:16px;height:16px;margin:0;"
                                   <?= !empty($product['is_new'])?'checked':'' ?>> Mark as New
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;">
                            <input type="checkbox" name="track_stock" value="1" class="form-check-input" style="width:16px;height:16px;margin:0;"
                                   <?= ($product['track_stock']??1)?'checked':'' ?>> Track Stock
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="font-size:15px;padding:12px;">
                <i class="fa fa-save me-2"></i><?= $isEdit ? 'Update Product' : 'Create Product' ?>
            </button>
        </div>
    </div>
</form>

<?php
$productId = $isEdit ? $product['id'] : 0;
$actionUrl = $isEdit ? "admin/products/update/{$productId}" : 'admin/products/store';
?>
<script>
var varIdx     = <?= count($product['variations'] ?? []) ?>;
var ACTION_URL = '<?= $actionUrl ?>';
var IS_EDIT    = <?= $isEdit ? 'true' : 'false' ?>;

/* ── Slug / SKU helpers ─────────────────────────────────────── */
function makeSlug(text) {
    return text.toLowerCase().trim()
        .replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-').replace(/^-|-$/g, '');
}
function makeSKU(name) {
    var words  = name.trim().split(/\s+/).slice(0, 5);
    var prefix = words.map(function(w){ return w.replace(/[^a-zA-Z0-9]/g,'').substring(0,3).toUpperCase(); }).filter(Boolean).join('');
    if (!prefix) prefix = 'PRD';
    return prefix + '-' + Math.floor(1000 + Math.random() * 9000);
}

var slugManuallyEdited = IS_EDIT;
var skuManuallyEdited  = IS_EDIT;

document.getElementById('productNameInput').addEventListener('input', function() {
    if (!slugManuallyEdited) document.getElementById('slugInput').value = makeSlug(this.value);
    if (!skuManuallyEdited)  document.getElementById('skuInput').value  = makeSKU(this.value);
});
document.getElementById('slugInput').addEventListener('input', function(){ slugManuallyEdited = true; });
document.getElementById('skuInput').addEventListener('input',  function(){ skuManuallyEdited  = true; });
document.getElementById('btnRegenSlug').addEventListener('click', function(){
    document.getElementById('slugInput').value = makeSlug(document.getElementById('productNameInput').value);
    slugManuallyEdited = false;
});
document.getElementById('btnGenSKU').addEventListener('click', function(){
    document.getElementById('skuInput').value = makeSKU(document.getElementById('productNameInput').value);
    skuManuallyEdited = false;
});

/* ── Thumbnail preview ─────────────────────────────────────── */
document.getElementById('thumbInput')?.addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var prev = document.getElementById('thumbPreview');
        if (prev.tagName === 'IMG') {
            prev.src = e.target.result;
        } else {
            prev.innerHTML = '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-sm);">';
        }
    };
    reader.readAsDataURL(file);
});

/* ── Gallery image delete ─────────────────────────────────── */
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.del-img-btn');
    if (!btn) return;
    Swal.fire({title:'Delete image?',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Delete'}).then(function(r){
        if (!r.isConfirmed) return;
        fetch(SITE_URL + '/admin/products/delete-image', {
            method:'POST', headers:{'X-CSRF-Token':CSRF_TOKEN},
            body: new URLSearchParams({image_id: btn.dataset.imageId, product_id: btn.dataset.productId, csrf_token: CSRF_TOKEN})
        }).then(r=>r.json()).then(function(res){
            if (res.success) btn.closest('.gallery-thumb-wrap').remove();
        });
    });
});

/* ── Variations ─────────────────────────────────────────────── */
function syncVarsMsg() {
    var rows = document.querySelectorAll('.variation-row');
    document.getElementById('noVarsMsg').style.display = rows.length ? 'none' : '';
}

document.getElementById('addVariation').addEventListener('click', function() {
    var baseSKU = document.getElementById('skuInput').value || 'PRD';
    var rand    = Math.floor(100 + Math.random() * 900);
    var row     = document.createElement('div');
    row.className = 'variation-row';
    row.innerHTML =
        '<input type="hidden" name="variations[' + varIdx + '][id]" value="0">'
        + '<input type="text"   name="variations[' + varIdx + '][name]"  class="form-control form-control-sm var-name-input" placeholder="e.g. Blue - L">'
        + '<input type="text"   name="variations[' + varIdx + '][sku]"   class="form-control form-control-sm var-sku-input"  placeholder="' + baseSKU + '-V' + rand + '" value="' + baseSKU + '-V' + rand + '">'
        + '<input type="number" name="variations[' + varIdx + '][price]" class="form-control form-control-sm var-price-input" placeholder="Price" step="0.01">'
        + '<input type="number" name="variations[' + varIdx + '][stock]" class="form-control form-control-sm var-stock-input" placeholder="0" value="0">'
        + '<button type="button" class="btn btn-sm remove-variation"><i class="fa fa-trash"></i></button>';
    document.getElementById('variationsContainer').appendChild(row);
    row.querySelector('.var-name-input').focus();
    varIdx++;
    syncVarsMsg();
});

/* Auto-SKU on variation name input */
document.addEventListener('input', function(e) {
    if (!e.target.classList.contains('var-name-input')) return;
    var row     = e.target.closest('.variation-row');
    var skuFld  = row ? row.querySelector('.var-sku-input') : null;
    if (!skuFld) return;
    var base    = document.getElementById('skuInput').value || 'PRD';
    var init    = e.target.value.replace(/[^a-zA-Z0-9\s]/g,'').trim().split(/\s+/)
                    .map(function(w){ return w.substring(0,2).toUpperCase(); }).join('');
    if (init) skuFld.value = base + '-' + init;
});

/* Remove variation row */
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.remove-variation');
    if (!btn) return;
    var row = btn.closest('.variation-row');
    if (row) { row.remove(); syncVarsMsg(); }
});

/* ── Tag chips UI ───────────────────────────────────────────── */
function renderTagChips(val) {
    var container = document.getElementById('tagChips');
    var tags = val.split(',').map(function(t){ return t.trim(); }).filter(Boolean);
    container.innerHTML = tags.map(function(t){
        return '<span style="display:inline-flex;align-items:center;gap:3px;padding:2px 8px;background:var(--cyan-dim);color:var(--cyan);border-radius:10px;font-size:11px;font-weight:600;">'
             + t + '</span>';
    }).join('');
}
var tagsInput = document.getElementById('tagsInput');
tagsInput.addEventListener('input', function(){ renderTagChips(this.value); });
renderTagChips(tagsInput.value); // init

/* ── Auto-Tags generator ─────────────────────────────────────── */
document.getElementById('autoTagsBtn')?.addEventListener('click', function() {
    var stopWords = new Set([
        'a','an','the','and','or','but','in','on','at','to','for','of','with',
        'is','are','was','were','be','been','being','have','has','had','do','does',
        'did','will','would','could','should','may','might','this','that','these',
        'those','it','its','we','our','you','your','i','my','he','she','they',
        'their','from','by','as','into','than','then','when','which','who','how',
        'all','any','both','each','few','more','most','other','some','such','only',
        'also','very','just','about','can','each','here','there','what','so','if'
    ]);

    var name = document.getElementById('productNameInput').value || '';
    var desc = document.getElementById('descriptionEn').value || '';
    var shortDesc = document.querySelector('[name="short_desc"]')?.value || '';
    var combined = (name + ' ' + shortDesc + ' ' + desc)
        .replace(/<[^>]*>/g, ' ')      // strip HTML
        .replace(/[^a-zA-Z0-9\s]/g, ' ')
        .toLowerCase();

    var words = combined.split(/\s+/)
        .map(function(w){ return w.trim(); })
        .filter(function(w){ return w.length > 2 && !stopWords.has(w); });

    // Frequency count
    var freq = {};
    words.forEach(function(w){ freq[w] = (freq[w] || 0) + 1; });

    // Sort by frequency, take top 12
    var tags = Object.keys(freq)
        .sort(function(a,b){ return freq[b]-freq[a]; })
        .slice(0, 12)
        .join(', ');

    if (!tags) { SwalDark.fire({icon:'warning',title:'No content',text:'Add a product name or description first.',timer:2000,showConfirmButton:false}); return; }

    tagsInput.value = tags;
    renderTagChips(tags);
    tagsInput.style.borderColor = '#10b981';
    setTimeout(function(){ tagsInput.style.borderColor=''; }, 1500);
});

/* ── AI Description ─────────────────────────────────────────── */
document.getElementById('aiDescBtn')?.addEventListener('click', function() {
    var name     = document.getElementById('productNameInput').value.trim();
    var category = document.querySelector('[name="category_id"] option:checked')?.textContent?.trim() || '';
    var keywords = document.getElementById('tagsInput').value.trim();
    if (!name) { Swal.fire('Warning', 'Enter a product name first.', 'warning'); return; }

    var btn = this;
    btn.disabled = true; btn.innerHTML = '⏳ Generating...';

    ajaxPost('admin/products/ai-desc', { name: name, category: category, keywords: keywords, lang: 'en' },
        function(res) {
            btn.disabled = false; btn.innerHTML = '✨ AI Generate';
            if (res.success) {
                var el = document.getElementById('descriptionEn');
                el.value = res.description;
                el.style.borderColor = '#10b981';
                setTimeout(function(){ el.style.borderColor=''; }, 2000);
                Swal.fire({ icon:'success', title:'Done!', timer:1200, showConfirmButton:false });
            } else {
                Swal.fire('Error', res.message || 'AI generation failed.', 'error');
            }
        },
        function(xhr) {
            btn.disabled = false; btn.innerHTML = '✨ AI Generate';
            var msg = 'API request failed.';
            try { var r = JSON.parse(xhr.responseText); if (r.message) msg = r.message; } catch(x){}
            Swal.fire('Error', msg, 'error');
        }
    );
});

/* ── Form submit ────────────────────────────────────────────── */
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var btn = this.querySelector('[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Saving...';

    var formData = new FormData(this);
    formData.append('_csrf', CSRF_TOKEN);

    $.ajax({
        url:         SITE_URL + '/' + ACTION_URL,
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        dataType:    'json',
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon:'success', title:res.message, timer:1500, showConfirmButton:false })
                    .then(function(){ window.location.href = res.redirect; });
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-save me-2"></i><?= $isEdit ? 'Update Product' : 'Create Product' ?>';
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-save me-2"></i><?= $isEdit ? 'Update Product' : 'Create Product' ?>';
            Swal.fire('Error', 'Upload failed. Check file sizes (max 10MB each).', 'error');
        }
    });
});
</script>
