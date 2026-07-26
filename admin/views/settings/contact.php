<?php /* admin/views/settings/contact.php */ ?>

<style>
.contact-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--text-muted);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.contact-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}
.social-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.social-icon-badge {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.social-input-row .form-control {
    flex: 1;
    font-size: 13px;
}
.contact-field-wrap {
    position: relative;
}
.contact-field-wrap .cfield-icon {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    pointer-events: none;
    z-index: 5;
}
.contact-field-wrap .form-control {
    padding-left: 38px;
}
/* Preview */
.preview-card-inner {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
}
.preview-topbar {
    background: var(--surface2);
    padding: 8px 14px;
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 12px;
    border-bottom: 1px solid var(--border);
}
.preview-topbar .pitem {
    display: flex; align-items: center; gap: 6px;
    color: var(--text-dim);
}
.preview-topbar .pitem i { color: var(--text-muted); font-size: 11px; }
.preview-footer {
    padding: 10px 14px;
    font-size: 12px;
}
.preview-footer .pfooter-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--text-muted);
    margin-bottom: 8px;
}
.social-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin: 3px 3px 3px 0;
    text-decoration: none;
    transition: opacity .2s;
    color: #fff !important;
}
.social-pill:hover { opacity: .85; color: #fff !important; }
.wa-float-preview {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #25d366;
    color: #fff;
    padding: 7px 14px;
    border-radius: 24px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
}
</style>

<div class="page-header">
    <h4>Contact &amp; Social Settings</h4>
</div>

<div class="row g-3">
    <!-- ── Left: Form ────────────────────────── -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form id="contactForm">
                    <?= CSRF::field() ?>

                    <!-- Contact Details -->
                    <div class="contact-section-title">
                        <i class="fa fa-phone" style="color:var(--green);"></i>Contact Details
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Support Phone</label>
                        <div class="contact-field-wrap">
                            <i class="fa fa-phone cfield-icon" style="color:var(--green);"></i>
                            <input type="text" name="support_phone" class="form-control"
                                   value="<?= e($settings['support_phone'] ?? '') ?>"
                                   placeholder="+94 77 000 0000">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;">Support Email</label>
                        <div class="contact-field-wrap">
                            <i class="fa fa-envelope cfield-icon" style="color:var(--cyan);"></i>
                            <input type="email" name="support_email" class="form-control"
                                   value="<?= e($settings['support_email'] ?? '') ?>"
                                   placeholder="support@anything.lk">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:12px;">
                            WhatsApp Number
                            <span style="font-size:11px;font-weight:400;color:var(--text-muted);margin-left:4px;">digits only</span>
                        </label>
                        <div class="contact-field-wrap">
                            <i class="fa fa-whatsapp cfield-icon" style="color:#25d366;"></i>
                            <input type="text" name="whatsapp_number" class="form-control"
                                   value="<?= e($settings['whatsapp_number'] ?? '') ?>"
                                   placeholder="94771234567">
                        </div>
                        <small class="text-muted" style="font-size:11px;">Country code + number, no spaces or +. E.g. 94771234567</small>
                    </div>

                    <!-- Social Media -->
                    <div class="contact-section-title">
                        <i class="fa fa-share-alt" style="color:var(--purple);"></i>Social Media Links
                    </div>

                    <?php $socials = [
                        ['social_facebook',  'fa fa-facebook',   'Facebook',   '#1877f2', 'rgba(24,119,242,0.15)'],
                        ['social_instagram', 'fa fa-instagram',  'Instagram',  '#e1306c', 'rgba(225,48,108,0.15)'],
                        ['social_tiktok',    'fa fa-music',      'TikTok',     '#e8e8e8', 'rgba(232,232,232,0.1)'],
                        ['social_youtube',   'fa fa-youtube',    'YouTube',    '#ff0000', 'rgba(255,0,0,0.15)'],
                        ['social_twitter',   'fa fa-twitter',    'Twitter / X','#1da1f2', 'rgba(29,161,242,0.15)'],
                        ['social_skype',     'fa fa-skype',      'Skype',      '#00aff0', 'rgba(0,175,240,0.15)'],
                    ]; foreach ($socials as [$key, $icon, $label, $color, $bg]): ?>
                    <div class="social-input-row">
                        <div class="social-icon-badge" style="background:<?= $bg ?>;color:<?= $color ?>;">
                            <i class="<?= $icon ?>"></i>
                        </div>
                        <input type="url" name="<?= $key ?>" class="form-control"
                               value="<?= e($settings[$key] ?? '') ?>"
                               placeholder="<?= $label ?> URL — https://...">
                    </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary w-100 mt-3" id="contactSaveBtn">
                        <i class="fa fa-save me-2"></i>Save Contact &amp; Social Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── Right: Preview ───────────────────── -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header" style="background:var(--surface);border-bottom:1px solid var(--border);padding:14px 20px;">
                <span style="font-size:12px;font-weight:700;color:var(--text);letter-spacing:.02em;">
                    <i class="fa fa-eye me-2" style="color:var(--cyan);"></i>Live Preview
                </span>
                <span style="font-size:11px;color:var(--text-muted);margin-left:8px;">Reflects saved values</span>
            </div>
            <div class="card-body">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:14px;">
                    These values are displayed in the site topbar, footer, and floating WhatsApp button.
                </p>

                <!-- Topbar preview -->
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:8px;">Topbar</div>
                <div class="preview-card-inner mb-3">
                    <div class="preview-topbar">
                        <?php if (!empty($settings['support_phone'])): ?>
                        <div class="pitem"><i class="fa fa-phone"></i><?= e($settings['support_phone']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['support_email'])): ?>
                        <div class="pitem"><i class="fa fa-envelope"></i><?= e($settings['support_email']) ?></div>
                        <?php endif; ?>
                        <?php if (empty($settings['support_phone']) && empty($settings['support_email'])): ?>
                        <span style="font-size:12px;color:var(--text-muted);font-style:italic;">No contact details saved yet</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Social preview -->
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:8px;">Social Links</div>
                <div style="margin-bottom:14px;">
                <?php
                $socialPills = [
                    ['social_facebook',  'fa fa-facebook',   '#1877f2'],
                    ['social_instagram', 'fa fa-instagram',  '#e1306c'],
                    ['social_tiktok',    'fa fa-music',      '#333'],
                    ['social_youtube',   'fa fa-youtube',    '#ff0000'],
                    ['social_twitter',   'fa fa-twitter',    '#1da1f2'],
                    ['social_skype',     'fa fa-skype',      '#00aff0'],
                ];
                $hasSocial = false;
                foreach ($socialPills as [$k, $ic, $c]):
                    if (!empty($settings[$k])):
                        $hasSocial = true;
                ?>
                <a href="<?= e($settings[$k]) ?>" target="_blank"
                   class="social-pill" style="background:<?= $c ?>;">
                    <i class="<?= $ic ?>"></i>
                    <?= ucfirst(str_replace('social_', '', $k)) ?>
                </a>
                <?php   endif; endforeach;
                if (!$hasSocial): ?>
                <span style="font-size:12px;color:var(--text-muted);font-style:italic;">No social links saved yet</span>
                <?php endif; ?>
                </div>

                <!-- WhatsApp float preview -->
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:8px;">Floating WhatsApp Button</div>
                <?php if (!empty($settings['whatsapp_number'])): ?>
                <div class="wa-float-preview">
                    <i class="fa fa-whatsapp" style="font-size:16px;"></i>
                    Chat on WhatsApp
                    <span style="opacity:.75;font-size:10px;margin-left:4px;">+<?= e($settings['whatsapp_number']) ?></span>
                </div>
                <?php else: ?>
                <span style="font-size:12px;color:var(--text-muted);font-style:italic;">No WhatsApp number saved yet</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $extraScript = <<<'JS'
<script>
$('#contactForm').on('submit', function(e) {
    e.preventDefault();
    var btn = $('#contactSaveBtn');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving…');
    ajaxPost('admin/contact-settings/save', Object.fromEntries(new FormData(this)), function(res) {
        SwalDark.fire({
            icon: res.success ? 'success' : 'error',
            title: res.message,
            timer: 1800,
            showConfirmButton: false
        }).then(function() { if (res.success) location.reload(); });
    });
});
</script>
JS;
?>
