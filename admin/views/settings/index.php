<?php /* settings/index.php */ $pageTitle = 'Settings'; ?>

<style>
.settings-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--text-muted);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.settings-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}
.field-icon-wrap {
    position: relative;
}
.field-icon-wrap .field-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 13px;
    pointer-events: none;
    z-index: 5;
}
.field-icon-wrap .form-control,
.field-icon-wrap .form-select {
    padding-left: 36px;
}
.field-icon-wrap textarea.form-control {
    padding-left: 36px;
    padding-top: 10px;
}
.field-icon-wrap .field-icon.top-icon {
    top: 14px;
    transform: none;
}
.logo-preview-box {
    background: var(--surface);
    border: 1px dashed var(--border2);
    border-radius: var(--radius-sm);
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 10px;
}
.logo-preview-box img {
    max-height: 52px;
    max-width: 160px;
    object-fit: contain;
    border-radius: 6px;
}
.db-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 0;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
}
.db-info-row:last-child { border-bottom: none; }
.db-info-row .db-key { color: var(--text-muted); font-size: 12px; }
.db-info-row .db-val { font-weight: 600; color: var(--text); font-family: monospace; font-size: 12px; }
.api-key-wrap { position: relative; }
.api-key-wrap input { padding-right: 42px; }
.api-key-toggle {
    position: absolute;
    right: 10px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    transition: color .2s;
}
.api-key-toggle:hover { color: var(--cyan); }
.currency-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    background: var(--cyan-dim);
    color: var(--cyan);
    margin-bottom: 6px;
}
</style>

<div class="page-header">
    <h4>System Settings</h4>
</div>

<div class="row g-3">
    <!-- ── Left Column ─────────────────────── -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <form id="settingsForm" enctype="multipart/form-data">
                    <?= CSRF::field() ?>

                    <!-- Store Identity -->
                    <div class="settings-section-title"><i class="fa fa-store" style="color:var(--cyan);"></i>Store Identity</div>

                    <!-- Logo -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">Site Logo</label>
                        <?php $logo = $dbSettings['site_logo'] ?? ''; ?>
                        <?php if ($logo): ?>
                        <div class="logo-preview-box">
                            <img src="<?= url('uploads/logo/'.e($logo)) ?>" alt="Logo" id="logoPreviewImg">
                            <div>
                                <div style="font-size:12px;font-weight:600;color:var(--text);">Current Logo</div>
                                <div style="font-size:11px;color:var(--text-muted);"><?= e($logo) ?></div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="logo-preview-box" id="logoPreviewBox" style="display:none;">
                            <img src="" alt="Preview" id="logoPreviewImg" style="max-height:52px;">
                            <div style="font-size:12px;color:var(--text-muted);">New logo preview</div>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="site_logo" id="logoInput" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size:11px;">PNG / JPG / SVG / WebP — replaces current logo.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Store Name</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-tag field-icon"></i>
                            <input type="text" name="site_name" class="form-control"
                                   value="<?= e($dbSettings['site_name'] ?? $config['app_name'] ?? 'Anything.lk') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Tagline</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-quote-left field-icon"></i>
                            <input type="text" name="site_tagline" class="form-control"
                                   placeholder="Your one-stop shop"
                                   value="<?= e($dbSettings['site_tagline'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Store Address</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-map-marker field-icon top-icon"></i>
                            <textarea name="site_address" class="form-control" rows="2"
                                      placeholder="No. 1, Main Street, Colombo 01"><?= e($dbSettings['site_address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">Website URL</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-globe field-icon"></i>
                            <input type="url" name="site_website" class="form-control"
                                   placeholder="https://www.anything.lk"
                                   value="<?= e($dbSettings['site_website'] ?? '') ?>">
                        </div>
                        <small class="text-muted" style="font-size:11px;">Shown on invoices and PDF documents.</small>
                    </div>

                    <!-- Currency -->
                    <div class="settings-section-title"><i class="fa fa-dollar" style="color:var(--amber);"></i>Currency</div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">Display Currency</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-money field-icon"></i>
                            <select name="currency" class="form-select">
                                <option value="LKR" <?= ($dbSettings['currency']??'LKR')==='LKR'?'selected':'' ?>>LKR — Sri Lankan Rupee (Rs)</option>
                                <option value="USD" <?= ($dbSettings['currency']??'')==='USD'?'selected':'' ?>>USD — US Dollar ($)</option>
                            </select>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Contact & phone details are managed in <a href="<?= url('admin/contact-settings') ?>" style="color:var(--cyan);">Contact &amp; Social Settings</a>.</small>
                    </div>

                    <!-- AI -->
                    <div class="settings-section-title"><i class="fa fa-magic" style="color:var(--purple);"></i>AI Features</div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">Anthropic API Key</label>
                        <div class="api-key-wrap">
                            <input type="password" name="anthropic_api_key" id="apiKeyField" class="form-control"
                                   value="<?= e($config['anthropic_api_key']??'') ?>"
                                   placeholder="sk-ant-api03-…" autocomplete="new-password">
                            <button type="button" class="api-key-toggle" id="apiKeyToggle" title="Toggle visibility">
                                <i class="fa fa-eye" id="apiKeyIcon"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Required for <span style="color:var(--purple);">✨ AI product description</span> generation.</small>
                    </div>

                    <!-- Invoice & Billing -->
                    <div class="settings-section-title"><i class="fa fa-file-text" style="color:var(--green);"></i>Invoice &amp; Billing</div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Bank Name</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-bank field-icon"></i>
                            <input type="text" name="bank_name" class="form-control"
                                   placeholder="e.g. HNB, Bank of Ceylon"
                                   value="<?= e($dbSettings['bank_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Bank Account Number</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-credit-card field-icon"></i>
                            <input type="text" name="bank_account" class="form-control"
                                   placeholder="e.g. 123-456-7890"
                                   value="<?= e($dbSettings['bank_account'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Bank Branch</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-map-marker field-icon"></i>
                            <input type="text" name="bank_branch" class="form-control"
                                   placeholder="e.g. Colombo 03"
                                   value="<?= e($dbSettings['bank_branch'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">Invoice Footer Note</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-info-circle field-icon top-icon"></i>
                            <textarea name="invoice_footer_note" class="form-control" rows="2"
                                      placeholder="e.g. Payment Terms: Due on Receipt"><?= e($dbSettings['invoice_footer_note'] ?? '') ?></textarea>
                        </div>
                        <small class="text-muted" style="font-size:11px;">Appears at the bottom of all invoices and receipts. Defaults to "Payment Terms: Due on Receipt" if blank.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="saveBtn">
                        <i class="fa fa-save me-2"></i>Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── Right Column ────────────────────── -->
    <div class="col-lg-5">
        <!-- DB Info -->
        <div class="card mb-3">
            <div class="card-header" style="background:var(--surface);border-bottom:1px solid var(--border);padding:14px 20px;">
                <span style="font-size:12px;font-weight:700;color:var(--text);letter-spacing:.02em;">
                    <i class="fa fa-database me-2" style="color:var(--blue);"></i>Environment Info
                </span>
            </div>
            <div class="card-body" style="padding:4px 20px 8px;">
                <div class="db-info-row">
                    <span class="db-key"><i class="fa fa-database me-1"></i> Database</span>
                    <span class="db-val"><?= e($config['dbname']??'—') ?></span>
                </div>
                <div class="db-info-row">
                    <span class="db-key"><i class="fa fa-server me-1"></i> Host</span>
                    <span class="db-val"><?= e($config['servername']??'localhost') ?></span>
                </div>
                <div class="db-info-row">
                    <span class="db-key"><i class="fa fa-code me-1"></i> PHP Version</span>
                    <span class="db-val"><?= phpversion() ?></span>
                </div>
                <div class="db-info-row">
                    <span class="db-key"><i class="fa fa-table me-1"></i> MySQL Version</span>
                    <span class="db-val"><?php $dbConn=new Db(); $rv=$dbConn->selectOne("SELECT VERSION() AS v"); echo e($rv['v']??'—'); ?></span>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header" style="background:var(--surface);border-bottom:1px solid var(--border);padding:14px 20px;">
                <span style="font-size:12px;font-weight:700;color:var(--text);letter-spacing:.02em;">
                    <i class="fa fa-lock me-2" style="color:var(--amber);"></i>Admin Password
                </span>
            </div>
            <div class="card-body">
                <form id="pwForm">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Current Password</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-lock field-icon"></i>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">New Password</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-key field-icon"></i>
                            <input type="password" name="new_password" class="form-control" placeholder="Minimum 8 characters">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Confirm New Password</label>
                        <div class="field-icon-wrap">
                            <i class="fa fa-key field-icon"></i>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fa fa-shield me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $extraScript = <<<'JS'
<script>
/* API key visibility toggle */
$('#apiKeyToggle').on('click', function() {
    var f = $('#apiKeyField');
    var isPass = f.attr('type') === 'password';
    f.attr('type', isPass ? 'text' : 'password');
    $('#apiKeyIcon').toggleClass('fa-eye fa-eye-slash');
});

/* Logo preview on file select */
$('#logoInput').on('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#logoPreviewImg').attr('src', e.target.result);
        $('#logoPreviewBox').show();
    };
    reader.readAsDataURL(file);
});

/* Settings form */
$('#settingsForm').on('submit', function(e) {
    e.preventDefault();
    var btn = $('#saveBtn');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving…');
    var fd = new FormData(this);
    fd.set('_csrf', CSRF_TOKEN);
    $.ajax({
        url: SITE_URL + '/admin/settings/save',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            SwalDark.fire({
                icon: res.success ? 'success' : 'error',
                title: res.success ? 'Saved!' : 'Error',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
            }).then(function() { if (res.success) location.reload(); });
        },
        error: function(xhr) {
            var msg = 'Request failed.';
            try { msg = JSON.parse(xhr.responseText).message || msg; } catch(e) {}
            SwalDark.fire({ icon: 'error', title: 'Error', text: msg });
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fa fa-save me-2"></i>Save Settings');
        }
    });
});

/* Password form */
$('#pwForm').on('submit', function(e) {
    e.preventDefault();
    var btn = $(this).find('button[type=submit]');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Updating…');
    ajaxPost('admin/settings/change-password', Object.fromEntries(new FormData(this)), function(res) {
        Swal.fire({ icon: res.success ? 'success' : 'error', title: res.message, timer: 1800, showConfirmButton: false });
        if (res.success) $('#pwForm')[0].reset();
    });
    setTimeout(function() {
        btn.prop('disabled', false).html('<i class="fa fa-shield me-2"></i>Change Password');
    }, 2000);
});
</script>
JS;
?>
