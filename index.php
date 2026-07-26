<?php
define('ROOT', __DIR__);
define('APP',  ROOT . '/app');
define('CORE', ROOT . '/core');

session_start();

spl_autoload_register(function (string $class): void {
    $paths = [
        CORE . '/' . $class . '.php',
        APP  . '/controllers/' . $class . '.php',
        APP  . '/models/'      . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) { require_once $file; return; }
    }
});

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
require_once CORE . '/RateLimit.php';

Lang::init();

$router = new Router();

// HOME
$router->get('',                        'HomeController',     'index');
$router->get('home',                    'HomeController',     'index');

// PRODUCTS & SEARCH
$router->get('products',                'ProductController',  'listing');
$router->get('product/{slug}',          'ProductController',  'detail');
$router->get('product/{slug}/quick',    'ProductController',  'quickView');
$router->get('category/{slug}',         'ProductController',  'category');
$router->get('brand/{slug}',            'ProductController',  'brand');
$router->get('search',                  'SearchController',   'index');

// CART
$router->get('cart',                    'CartController',     'index');
$router->post('cart/add',               'CartController',     'add');
$router->post('cart/update',            'CartController',     'update');
$router->post('cart/remove',            'CartController',     'remove');
$router->post('cart/apply-coupon',      'CartController',     'applyCoupon');
$router->post('cart/remove-coupon',     'CartController',     'removeCoupon');
$router->get('cart/count',              'CartController',     'count');
$router->get('cart/items',              'CartController',     'items');

// WISHLIST
$router->post('wishlist/toggle',        'WishlistController', 'toggle');
$router->get('wishlist/list',           'WishlistController', 'list');
$router->get('wishlist/items',          'WishlistController', 'items');

// CHECKOUT
$router->get('checkout',                'CheckoutController', 'index');
$router->post('checkout/place-order',   'CheckoutController', 'placeOrder');
$router->get('checkout/success/{id}',   'CheckoutController', 'success');
$router->get('invoice/{id}',            'CheckoutController', 'invoice');
$router->get('invoice/{id}/download',   'CheckoutController', 'download');

// AUTH — Single login for all roles
$router->get('login',                   'UserController',     'loginPage');
$router->post('login',                  'UserController',     'login');
$router->get('register',                'UserController',     'registerPage');
$router->post('register',               'UserController',     'register');
$router->get('logout',                  'UserController',     'logout');
$router->get('forgot-password',         'UserController',     'forgotPage');
$router->post('forgot-password',        'UserController',     'forgotPassword');
$router->get('reset-password',          'UserController',     'resetPage');
$router->post('reset-password',         'UserController',     'resetPassword');

// CUSTOMER ACCOUNT
$router->get('account',                 'UserController',     'account');
$router->get('account/orders',          'UserController',     'orders');
$router->get('account/order/{id}',      'UserController',     'orderDetail');
$router->post('account/order/{id}/cancel','UserController',   'cancelOrder');
$router->get('account/wishlist',        'UserController',     'wishlist');
$router->get('account/addresses',       'UserController',     'addresses');
$router->post('account/update-profile', 'UserController',     'updateProfile');
$router->post('account/add-address',         'UserController', 'addAddress');
$router->post('account/delete-address',      'UserController', 'deleteAddress');
$router->post('account/set-default-address', 'UserController', 'setDefaultAddress');

// REVIEWS
$router->post('review/submit',          'ReviewController',   'submit');

// TRACKING
$router->post('track/view',             'TrackingController', 'view');
$router->post('track/click',            'TrackingController', 'click');

// LANGUAGE
$router->get('lang/{code}',             'LangController',     'switch');

// PUBLIC PAGES
$router->get('about',                   'PageController',     'about');
$router->get('contact',                 'PageController',     'contact');
$router->post('contact/send',           'PageController',     'sendContact');
$router->get('order-tracking',               'OrderTrackingController', 'index');
$router->post('order-tracking/send-otp',    'OrderTrackingController', 'sendOtp');
$router->post('order-tracking/verify-otp',  'OrderTrackingController', 'verifyOtp');
$router->post('order-tracking/resend-otp',  'OrderTrackingController', 'resendOtp');
$router->get('order-tracking/reset',        'OrderTrackingController', 'reset');
$router->get('shipping-policy',         'PageController',     'shippingPolicy');
$router->get('return-policy',           'PageController',     'returnPolicy');
$router->get('faq',                     'PageController',     'faq');

// NEWSLETTER
$router->post('newsletter/subscribe',   'NewsletterController', 'subscribe');

// CHATBOT
$router->post('chatbot',                'ChatbotController',  'respond');

// CHAT
$router->post('chat/start',             'ChatController',     'start');
$router->post('chat/send',              'ChatController',     'send');
$router->get('chat/poll',               'ChatController',     'poll');
$router->post('chat/close',             'ChatController',     'close');

$router->dispatch();
