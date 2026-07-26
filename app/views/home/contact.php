<?php $pageTitle = 'Contact Us'; ?>

<style>
.text-muted{color:var(--text-muted) !important;}
.page-hero{padding:3.5rem 0 3rem;background:var(--bg-soft);border-bottom:1px solid var(--border);text-align:center;}
.page-hero-icon{width:60px;height:60px;border-radius:16px;background:var(--primary,#E63946);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 1.25rem;box-shadow:0 8px 24px rgba(230,57,70,.25);}
.contact-card{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:2rem;height:100%;box-shadow:0 1px 8px rgba(0,0,0,.06);}
.contact-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px;}
.contact-label{font-weight:600;font-size:.9rem;margin-bottom:.2rem;color:var(--text);}
.form-label{color:var(--text);font-weight:600;font-size:.85rem;}
</style>

<section class="page-hero">
  <div class="container">
    <div class="page-hero-icon"><i class="fas fa-envelope"></i></div>
    <h1 class="fw-bold mb-2" style="color:var(--text);">Contact Us</h1>
    <p class="text-muted mb-0">We're here to help you anytime</p>
  </div>
</section>

<div class="container py-5">
  <div class="row g-4">

    <!-- Info -->
    <div class="col-md-5">
      <div class="contact-card">
        <h5 class="fw-bold mb-4" style="color:var(--text);">Get in Touch</h5>
        <?php
        $contacts = [
          ['bg'=>'rgba(99,102,241,.12)','color'=>'#6366f1','icon'=>'fa-map-marker-alt','label'=>'Address',        'val'=>nl2br(e(setting('site_address','123 Galle Road, Colombo 03, Sri Lanka')))],
          ['bg'=>'rgba(16,185,129,.12)','color'=>'#10b981','icon'=>'fa-phone',          'label'=>'Phone',          'val'=>e(setting('support_phone','+94 77 000 0000'))],
          ['bg'=>'rgba(239,68,68,.12)', 'color'=>'#ef4444','icon'=>'fa-envelope',        'label'=>'Email',          'val'=>e(setting('support_email','support@anything.lk'))],
          ['bg'=>'rgba(245,158,11,.12)','color'=>'#f59e0b','icon'=>'fa-clock',           'label'=>'Business Hours', 'val'=>'Mon–Sat: 8am – 9pm<br>Sunday: 10am – 6pm'],
        ];
        foreach ($contacts as $c): ?>
        <div class="d-flex align-items-start gap-3 mb-4">
          <div class="contact-icon" style="background:<?= $c['bg'] ?>;color:<?= $c['color'] ?>;"><i class="fas <?= $c['icon'] ?>"></i></div>
          <div>
            <div class="contact-label"><?= $c['label'] ?></div>
            <div class="text-muted small lh-lg"><?= $c['val'] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Form -->
    <div class="col-md-7">
      <div class="contact-card">
        <h5 class="fw-bold mb-4" style="color:var(--text);">Send us a Message</h5>
        <form id="contactForm" novalidate>
          <?= CSRF::field() ?>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Your Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" required
                     value="<?= Auth::check() ? e(Auth::user()['name']) : '' ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" required
                     value="<?= Auth::check() ? e(Auth::user()['email']) : '' ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Subject</label>
              <input type="text" name="subject" class="form-control" placeholder="How can we help?">
            </div>
            <div class="col-12">
              <label class="form-label">Message <span class="text-danger">*</span></label>
              <textarea name="message" class="form-control" rows="5"
                        placeholder="Write your message here…" required></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary w-100 py-2" id="contactBtn">
                <i class="fas fa-paper-plane me-2"></i>Send Message
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
$('#contactForm').on('submit', function(e) {
    e.preventDefault();
    var btn = $('#contactBtn');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
    ajaxPost('contact/send', Object.fromEntries(new FormData(this)), function(res) {
        btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Send Message');
        if (res.success) {
            Swal.fire({ icon:'success', title:'Message Sent!', text:"We'll get back to you soon.", timer:2000, showConfirmButton:false });
            document.getElementById('contactForm').reset();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
});
</script>
JS;
?>
