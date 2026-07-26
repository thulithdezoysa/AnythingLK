<?php
define('ROOT',  dirname(__DIR__));
define('APP',   ROOT . '/app');
define('CORE',  ROOT . '/core');
define('ADMIN', __DIR__);

session_start();

require_once ROOT . '/db_connect.php';
require_once CORE . '/Helper.php';
require_once CORE . '/Lang.php';
require_once CORE . '/CSRF.php';
require_once CORE . '/Auth.php';
require_once CORE . '/Router.php';
require_once CORE . '/Controller.php';
require_once CORE . '/MailService.php';
require_once CORE . '/InvoiceGenerator.php';
require_once CORE . '/DiscountEngine.php';
require_once ADMIN . '/AdminController.php';

// Pre-load all admin controllers
require_once ADMIN . '/controllers/AdminDashboardController.php';
require_once ADMIN . '/controllers/AdminProductController.php';
require_once ADMIN . '/controllers/AdminStockController.php';
require_once ADMIN . '/controllers/AdminPOController.php';
require_once ADMIN . '/controllers/AdminMiscControllers.php';

// Autoloader for admin-specific controllers
spl_autoload_register(function (string $class): void {
    $paths = [
        ADMIN . '/controllers/' . $class . '.php',
        APP   . '/controllers/' . $class . '.php',
        APP   . '/models/'      . $class . '.php',
        CORE  . '/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) { require_once $file; return; }
    }
});

Lang::init();

// Guard: must be admin or supervisor
if (!Auth::check() || !Auth::isSupervisor()) {
    // Not logged in → redirect to single login page
    $loginUrl = Helper::url('login');
    if (Auth::check() && Auth::isCustomer()) {
        // Logged in as customer - go to customer account
        header('Location: ' . Helper::url('account'));
    } else {
        header('Location: ' . $loginUrl);
    }
    exit;
}

// Pass project root as base path so admin routes like 'admin/dashboard' resolve correctly
$adminScriptDir = dirname($_SERVER['SCRIPT_NAME']); // e.g. /AnythingLK/admin
$projectBase    = dirname($adminScriptDir);          // e.g. /AnythingLK
$router = new Router($projectBase === '/' ? '' : $projectBase);

// Dashboard
$router->get('admin',                           'AdminDashboardController', 'index');
$router->get('admin/dashboard',                 'AdminDashboardController', 'index');

// Products
$router->get('admin/products',                  'AdminProductController',   'index');
$router->get('admin/products/create',           'AdminProductController',   'create');
$router->post('admin/products/store',           'AdminProductController',   'store');
$router->get('admin/products/edit/{id}',        'AdminProductController',   'edit');
$router->post('admin/products/update/{id}',     'AdminProductController',   'update');
$router->post('admin/products/delete',          'AdminProductController',   'delete');
$router->post('admin/products/ai-desc',         'AdminProductController',   'aiDescription');
$router->post('admin/products/delete-image',    'AdminProductController',   'deleteImage');

// Categories (unlimited nesting)
$router->get('admin/categories',                'AdminCategoryController',  'index');
$router->post('admin/categories/store',         'AdminCategoryController',  'store');
$router->post('admin/categories/update',        'AdminCategoryController',  'update');
$router->post('admin/categories/delete',        'AdminCategoryController',  'delete');
$router->get('admin/categories/children/{id}',  'AdminCategoryController',  'children');

// Brands
$router->get('admin/brands',                    'AdminBrandController',     'index');
$router->post('admin/brands/store',             'AdminBrandController',     'store');
$router->post('admin/brands/update',            'AdminBrandController',     'update');
$router->post('admin/brands/delete',            'AdminBrandController',     'delete');

// Stock
$router->get('admin/stock',                     'AdminStockController',     'index');
$router->get('admin/stock/movements',           'AdminStockController',     'movements');
$router->post('admin/stock/adjust',             'AdminStockController',     'adjust');
$router->post('admin/stock/stock-in',           'AdminStockController',     'stockIn');
$router->get('admin/stock/low-stock',           'AdminStockController',     'lowStock');
$router->post('admin/stock/alert',              'AdminStockController',     'emailAlert');

// Vendors
$router->get('admin/vendors',                   'AdminVendorController',    'index');
$router->post('admin/vendors/store',            'AdminVendorController',    'store');
$router->post('admin/vendors/update',           'AdminVendorController',    'update');
$router->post('admin/vendors/delete',           'AdminVendorController',    'delete');

// Purchase Orders
$router->get('admin/purchase-orders',           'AdminPOController',        'index');
$router->get('admin/purchase-orders/create',    'AdminPOController',        'create');
$router->post('admin/purchase-orders/store',    'AdminPOController',        'store');
$router->get('admin/purchase-orders/view/{id}', 'AdminPOController',        'show');
$router->post('admin/purchase-orders/submit',   'AdminPOController',        'submit');
$router->post('admin/purchase-orders/approve',  'AdminPOController',        'approve');
$router->post('admin/purchase-orders/receive',  'AdminPOController',        'receive');
$router->post('admin/purchase-orders/cancel',   'AdminPOController',        'cancel');

// Orders (supervisor can view + update status)
$router->get('admin/orders',                    'AdminOrderController',     'index');
$router->get('admin/orders/view/{id}',          'AdminOrderController',     'show');
$router->post('admin/orders/update-status',     'AdminOrderController',     'updateStatus');
$router->post('admin/orders/update-payment',    'AdminOrderController',     'updatePayment');
$router->post('admin/orders/update-shipping',   'AdminOrderController',     'updateShipping');
$router->get('admin/orders/invoice/{id}',           'AdminOrderController',     'invoice');
$router->get('admin/orders/receipt/{id}',           'AdminOrderController',     'receipt');
$router->get('admin/orders/invoice/{id}/download',  'AdminOrderController',     'download');
$router->get('admin/orders/receipt/{id}/download',  'AdminOrderController',     'downloadReceipt');

// Reviews (supervisor can approve)
$router->get('admin/reviews',                   'AdminReviewController',    'index');
$router->post('admin/reviews/update-status',    'AdminReviewController',    'updateStatus');

// Users (admin only)
$router->get('admin/users',                     'AdminUserController',      'index');
$router->post('admin/users/toggle-status',      'AdminUserController',      'toggleStatus');

// Discounts (unified — /admin/coupons kept for nav compat)
$router->get('admin/coupons',                    'AdminCouponController', 'index');
$router->post('admin/coupons/coupon-store',      'AdminCouponController', 'couponStore');
$router->post('admin/coupons/coupon-update',     'AdminCouponController', 'couponUpdate');
$router->post('admin/coupons/coupon-delete',     'AdminCouponController', 'couponDelete');
$router->post('admin/coupons/product-store',     'AdminCouponController', 'productStore');
$router->post('admin/coupons/product-update',    'AdminCouponController', 'productUpdate');
$router->post('admin/coupons/product-delete',    'AdminCouponController', 'productDelete');
$router->post('admin/coupons/global-store',      'AdminCouponController', 'globalStore');
$router->post('admin/coupons/global-update',     'AdminCouponController', 'globalUpdate');
$router->post('admin/coupons/global-delete',     'AdminCouponController', 'globalDelete');
$router->post('admin/coupons/bogo-store',        'AdminCouponController', 'bogoStore');
$router->post('admin/coupons/bogo-update',       'AdminCouponController', 'bogoUpdate');
$router->post('admin/coupons/bogo-delete',       'AdminCouponController', 'bogoDelete');

// Banners
$router->get('admin/banners',                   'AdminBannerController',    'index');
$router->post('admin/banners/store',            'AdminBannerController',    'store');
$router->post('admin/banners/update',           'AdminBannerController',    'update');
$router->post('admin/banners/delete',           'AdminBannerController',    'delete');
$router->post('admin/banners/toggle',           'AdminBannerController',    'toggle');

// Reports
$router->get('admin/reports',                   'AdminReportController',    'index');
$router->get('admin/reports/sales',             'AdminReportController',    'sales');
$router->get('admin/reports/products',          'AdminReportController',    'products');

// Settings (admin only)
$router->get('admin/settings',                  'AdminSettingsController',  'index');
$router->post('admin/settings/save',            'AdminSettingsController',  'save');
$router->post('admin/settings/change-password', 'AdminSettingsController',  'changePassword');

// Payment Gateway Settings
$router->get('admin/payment-gateway',           'AdminPaymentSettingsController', 'index');
$router->post('admin/payment-gateway/save',     'AdminPaymentSettingsController', 'save');
$router->post('admin/payment-gateway/toggle',   'AdminPaymentSettingsController', 'toggle');

// Payment Methods
$router->get('admin/payments',                  'AdminPaymentController',   'index');
$router->post('admin/payments/store',           'AdminPaymentController',   'store');
$router->post('admin/payments/update',          'AdminPaymentController',   'update');
$router->post('admin/payments/toggle',          'AdminPaymentController',   'toggle');
$router->post('admin/payments/delete',          'AdminPaymentController',   'delete');
$router->get('admin/payments/transactions',     'AdminPaymentController',   'transactions');
$router->post('admin/payments/update-txn',      'AdminPaymentController',   'updateTxn');

// Shipping Methods
$router->get('admin/shipping',                  'AdminShippingController',  'index');
$router->post('admin/shipping/store',           'AdminShippingController',  'store');
$router->post('admin/shipping/update',          'AdminShippingController',  'update');
$router->post('admin/shipping/delete',          'AdminShippingController',  'delete');

// Contact messages
$router->get('admin/messages',                  'AdminMessagesController',  'index');
$router->post('admin/messages/mark-read',       'AdminMessagesController',  'markRead');

// Newsletter subscribers
$router->get('admin/newsletter',                'AdminNewsletterController', 'index');
$router->post('admin/newsletter/delete',        'AdminNewsletterController', 'delete');
$router->get('admin/newsletter/export',         'AdminNewsletterController', 'export');

// Contact & Social Settings
$router->get('admin/contact-settings',          'AdminContactSettingsController', 'index');
$router->post('admin/contact-settings/save',    'AdminContactSettingsController', 'save');

// Homepage Sections
$router->get('admin/home-sections',                        'AdminHomeSectionsController', 'index');
$router->post('admin/home-sections/hero/store',            'AdminHomeSectionsController', 'heroStore');
$router->post('admin/home-sections/hero/update',           'AdminHomeSectionsController', 'heroUpdate');
$router->post('admin/home-sections/hero/delete',           'AdminHomeSectionsController', 'heroDelete');
$router->post('admin/home-sections/hero/toggle',           'AdminHomeSectionsController', 'heroToggle');
$router->post('admin/home-sections/collection/store',      'AdminHomeSectionsController', 'collectionStore');
$router->post('admin/home-sections/collection/update',     'AdminHomeSectionsController', 'collectionUpdate');
$router->post('admin/home-sections/collection/delete',     'AdminHomeSectionsController', 'collectionDelete');
$router->post('admin/home-sections/collection/toggle',     'AdminHomeSectionsController', 'collectionToggle');
$router->post('admin/home-sections/review/store',          'AdminHomeSectionsController', 'reviewStore');
$router->post('admin/home-sections/review/update',         'AdminHomeSectionsController', 'reviewUpdate');
$router->post('admin/home-sections/review/delete',         'AdminHomeSectionsController', 'reviewDelete');
$router->post('admin/home-sections/review/toggle',         'AdminHomeSectionsController', 'reviewToggle');
$router->post('admin/home-sections/reorder',               'AdminHomeSectionsController', 'reorder');

// Chat
$router->get('admin/chat',                         'AdminChatController',  'index');
$router->get('admin/chat/view/{id}',               'AdminChatController',  'show');
$router->post('admin/chat/reply',                  'AdminChatController',  'reply');
$router->post('admin/chat/close',                  'AdminChatController',  'close');
$router->get('admin/chat/messages',                'AdminChatController',  'messages');
$router->get('admin/chat/auto-replies',            'AdminChatController',  'autoReplies');
$router->post('admin/chat/auto-replies/save',      'AdminChatController',  'saveReply');
$router->post('admin/chat/auto-replies/delete',    'AdminChatController',  'deleteReply');
$router->post('admin/chat/auto-replies/toggle',    'AdminChatController',  'toggleReply');

$router->dispatch();
