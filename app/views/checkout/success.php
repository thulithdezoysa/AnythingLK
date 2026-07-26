<?php $pageTitle = __('checkout.success'); ?>
<div class="container py-5 text-center">
    <div class="mx-auto" style="max-width:560px;">
        <div class="mb-4">
            <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center"
                 style="width:80px;height:80px;font-size:36px;">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <h2 class="fw-bold"><?= __('checkout.success') ?></h2>
        <p class="text-muted">Thank you for your order! We'll process it shortly.</p>

        <div class="bg-light rounded p-4 text-start mt-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Order Number</span>
                <strong><?= e($order['order_number']) ?></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Status</span>
                <span class="badge" style="background:var(--accent);"><?= ucfirst($order['status']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Payment</span>
                <span><?= ucfirst(str_replace('_',' ',$order['payment_method'])) ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Total</span>
                <strong style="color:var(--primary-dark);">LKR <?= number_format($order['total_amount'],2) ?></strong>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-center mt-4">
            <?php if (Auth::check()): ?>
            <a href="<?= url('account/order/'.$order['id']) ?>" class="btn btn-primary">View Order</a>
            <?php endif; ?>
            <a href="<?= url('invoice/'.$order['id'].'/download') ?>" target="_blank" class="btn btn-outline-success"><i class="fa fa-download me-1"></i>Download Invoice</a>
            <a href="<?= url('products') ?>" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
    </div>
</div>
