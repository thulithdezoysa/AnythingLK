<?php $pageTitle = 'Frequently Asked Questions'; ?>

<style>
.text-muted{color:var(--text-muted) !important;}
.page-breadcrumb{background:var(--bg-soft);border-bottom:1px solid var(--border);padding:.6rem 0;}
.page-breadcrumb a{color:var(--primary,#E63946);text-decoration:none;}
.page-breadcrumb .breadcrumb-item.active{color:var(--text-muted);}
.page-breadcrumb .breadcrumb-item+.breadcrumb-item::before{color:var(--text-muted);}
.page-hero{padding:3.5rem 0 3rem;background:var(--bg-soft);border-bottom:1px solid var(--border);text-align:center;}
.page-hero-icon{width:60px;height:60px;border-radius:16px;background:var(--primary,#E63946);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;margin:0 auto 1.25rem;box-shadow:0 8px 24px rgba(230,57,70,.25);}
.faq-cat-title{color:var(--text);font-size:1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:.6rem;}
.faq-cat-icon{width:34px;height:34px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;background:rgba(230,57,70,.1);color:var(--primary,#E63946);flex-shrink:0;}
/* Accordion theme */
.accordion-item{background:var(--bg-card) !important;border:1px solid var(--border) !important;border-radius:12px !important;overflow:hidden;margin-bottom:.6rem;}
.accordion-button{background:var(--bg-card) !important;color:var(--text) !important;font-weight:600;font-size:.875rem;box-shadow:none !important;padding:1rem 1.25rem;}
.accordion-button:not(.collapsed){background:var(--bg-soft) !important;color:var(--primary,#E63946) !important;}
[data-theme="dark"] .accordion-button::after{filter:invert(1) brightness(1.5);}
.accordion-body{background:var(--bg-card) !important;color:var(--text-muted) !important;font-size:.875rem;line-height:1.75;border-top:1px solid var(--border);padding:1rem 1.25rem;}
.accordion-body a{color:var(--primary,#E63946);}
/* Search */
.faq-search .input-group-text{background:var(--bg-card) !important;border-color:var(--border) !important;color:var(--text-muted) !important;}
/* CTA */
.cta-box{background:var(--bg-soft);border:1px solid var(--border);border-radius:16px;padding:3rem 2rem;text-align:center;}
.btn-whatsapp{background:#25d366;color:#fff;}
.btn-whatsapp:hover{background:#1ebe5d;color:#fff;}
</style>

<div class="page-breadcrumb">
  <div class="container">
    <ol class="breadcrumb mb-0 small">
      <li class="breadcrumb-item"><a href="<?= url('') ?>"><i class="fas fa-home me-1"></i>Home</a></li>
      <li class="breadcrumb-item active">FAQ</li>
    </ol>
  </div>
</div>

<section class="page-hero">
  <div class="container">
    <div class="page-hero-icon"><i class="fas fa-question"></i></div>
    <h1 class="fw-bold mb-2" style="color:var(--text);">Frequently Asked Questions</h1>
    <p class="text-muted mb-0">Find answers instantly</p>
  </div>
</section>

<div class="container py-5">
  <div class="mx-auto" style="max-width:860px;">

    <!-- Search -->
    <div class="mb-5 faq-search">
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" id="faqSearch" class="form-control"
               placeholder="Search FAQs — try: delivery, refund, payment…">
      </div>
    </div>

    <?php
    $faqs = [
      'Orders & Delivery' => [
        'icon' => 'fa-truck',
        'items' => [
          ['How long does delivery take?', 'Standard delivery takes 3–7 business days. Express delivery is 1–2 business days. Same-day delivery is available in Colombo.'],
          ['Can I track my order?', 'Yes! Use our <a href="'.url('order-tracking').'">Order Tracking</a> page.'],
          ['What if my order is late?', 'Contact us at '.e(setting('support_email','support@anything.lk')).' or call '.e(setting('support_phone','+94 77 000 0000')).'.'],
          ['Do you deliver island-wide?', 'Yes, we deliver across all districts in Sri Lanka.'],
        ]
      ],
      'Payments' => [
        'icon' => 'fa-credit-card',
        'items' => [
          ['What payment methods do you accept?', 'Cash on Delivery, KOKO, MintPay, and Payzy.'],
          ['Is online payment safe?', 'Yes, all transactions are SSL encrypted.'],
          ['Can I pay in installments?', 'Yes! KOKO and MintPay support installments.'],
        ]
      ],
      'Returns & Refunds' => [
        'icon' => 'fa-undo',
        'items' => [
          ['What is your return policy?', 'Returns accepted within 7 days. See our <a href="'.url('return-policy').'">Return Policy</a>.'],
          ['How long does a refund take?', '3–7 business days after inspection.'],
          ['Do I have to pay for return shipping?', 'No, return pickup is free.'],
        ]
      ],
      'Account & General' => [
        'icon' => 'fa-user',
        'items' => [
          ['Do I need an account to order?', 'No, but an account gives you order tracking and wishlist benefits.'],
          ['How do I reset my password?', 'Use "Forgot Password" on the login page.'],
          ['How do I contact support?', 'Email, phone, or WhatsApp support is available 7 days a week.'],
        ]
      ],
    ];

    foreach ($faqs as $category => $data):
      $slug = Helper::slug($category);
    ?>
    <div class="mb-5 faq-category">
      <h4 class="faq-cat-title">
        <span class="faq-cat-icon"><i class="fas <?= $data['icon'] ?>"></i></span>
        <?= $category ?>
      </h4>
      <div class="accordion" id="acc-<?= $slug ?>">
        <?php foreach ($data['items'] as $i => [$q, $a]):
          $id = $slug.'-'.$i;
        ?>
        <div class="accordion-item faq-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#<?= $id ?>">
              <?= e($q) ?>
            </button>
          </h2>
          <div id="<?= $id ?>" class="accordion-collapse collapse">
            <div class="accordion-body faq-content"><?= $a ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- CTA -->
    <div class="cta-box">
      <h5 class="fw-bold mb-2" style="color:var(--text);">Still have questions?</h5>
      <p class="text-muted mb-4">Our support team is available 7 days a week.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= url('contact') ?>" class="btn btn-primary px-4">
          <i class="fas fa-envelope me-2"></i>Email Us
        </a>
        <a href="https://wa.me/<?= e(setting('whatsapp_number','94770000000')) ?>" target="_blank" class="btn btn-whatsapp px-4">
          <i class="fab fa-whatsapp me-2"></i>WhatsApp
        </a>
      </div>
    </div>

  </div>
</div>

<script>
document.getElementById('faqSearch').addEventListener('keyup', function() {
    var value = this.value.toLowerCase();
    document.querySelectorAll('.faq-category').forEach(function(cat) { cat.style.display = 'none'; });
    document.querySelectorAll('.faq-item').forEach(function(item) {
        var q = item.querySelector('.accordion-button').innerText.toLowerCase();
        var a = item.querySelector('.faq-content').innerText.toLowerCase();
        if (q.includes(value) || a.includes(value)) {
            item.style.display = '';
            item.querySelector('.accordion-collapse').classList.add('show');
            item.querySelector('.accordion-button').classList.remove('collapsed');
            item.closest('.faq-category').style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
