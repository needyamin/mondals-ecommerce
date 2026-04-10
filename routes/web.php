<?php

use Illuminate\Support\Facades\Route;

// Default Storefront
Route::get('/', [\App\Http\Controllers\StorefrontController::class, 'home'])->name('home');
Route::get('/products', [\App\Http\Controllers\StorefrontController::class, 'products'])->name('products');
Route::get('/product/{slug}', [\App\Http\Controllers\StorefrontController::class, 'productDetail'])->name('product.detail');
// Cart (works for both guests and authenticated users)
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Checkout (requires login)
Route::middleware('auth')->group(function () {
    Route::post('/cart/coupon', [\App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('/cart/coupon/remove', [\App\Http\Controllers\CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/place-order', [\App\Http\Controllers\CheckoutController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/order/confirmation/{orderNumber}', [\App\Http\Controllers\CheckoutController::class, 'confirmation'])->name('order.confirmation');
});

// Payment Gateway Callbacks (Global)
Route::match(['get', 'post'], '/payment/{gateway}/callback', [\App\Http\Controllers\PaymentCallbackController::class, 'handle'])->name('payment.callback');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Basic Login/Register Web Fallback
Route::get('/login', [\App\Http\Controllers\Auth\WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\WebAuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\Auth\WebAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\WebAuthController::class, 'register']);
Route::get('/register/vendor', [\App\Http\Controllers\Auth\VendorSignupController::class, 'create'])->name('register.vendor');
Route::post('/register/vendor', [\App\Http\Controllers\Auth\VendorSignupController::class, 'store']);

// Logout Web
Route::post('/logout', [\App\Http\Controllers\Auth\WebAuthController::class, 'logout'])->name('logout');

// ── Customer Area ──
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
    Route::get('/profile', [\App\Http\Controllers\Customer\AccountController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Customer\AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Admin Area
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/visit', [\App\Http\Controllers\Admin\NotificationController::class, 'visit'])->name('notifications.visit')->whereUuid('id');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Catalog CRUD (uses BaseCrudController pattern)
    Route::get('products/export', [\App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->except(['show']);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);

    // Orders (Custom actions, not standard resource)
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{id}/payment', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment');

    // Users (Customers & Staff)
    Route::get('/users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{id}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Vendors
    Route::get('/vendors/export', [\App\Http\Controllers\Admin\VendorController::class, 'export'])->name('vendors.export');
    Route::get('/vendors', [\App\Http\Controllers\Admin\VendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/create', [\App\Http\Controllers\Admin\VendorController::class, 'create'])->name('vendors.create');
    Route::post('/vendors', [\App\Http\Controllers\Admin\VendorController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{id}', [\App\Http\Controllers\Admin\VendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{id}/approve', [\App\Http\Controllers\Admin\VendorController::class, 'approve'])->name('vendors.approve');
    Route::post('/vendors/{id}/reject', [\App\Http\Controllers\Admin\VendorController::class, 'reject'])->name('vendors.reject');
    Route::post('/vendors/{id}/suspend', [\App\Http\Controllers\Admin\VendorController::class, 'suspend'])->name('vendors.suspend');
    Route::post('/vendors/{id}/unsuspend', [\App\Http\Controllers\Admin\VendorController::class, 'unsuspend'])->name('vendors.unsuspend');
    Route::patch('/vendors/{id}/commission', [\App\Http\Controllers\Admin\VendorController::class, 'updateCommission'])->name('vendors.commission');

    // CMS
    Route::get('/pages', [\App\Http\Controllers\Admin\CmsController::class, 'pages'])->name('cms.pages');
    Route::get('/pages/create', [\App\Http\Controllers\Admin\CmsController::class, 'createPage'])->name('cms.pages.create');
    Route::post('/pages', [\App\Http\Controllers\Admin\CmsController::class, 'storePage'])->name('cms.pages.store');
    Route::get('/pages/{id}/edit', [\App\Http\Controllers\Admin\CmsController::class, 'editPage'])->name('cms.pages.edit');
    Route::put('/pages/{id}', [\App\Http\Controllers\Admin\CmsController::class, 'updatePage'])->name('cms.pages.update');
    Route::delete('/pages/{id}', [\App\Http\Controllers\Admin\CmsController::class, 'destroyPage'])->name('cms.pages.destroy');

    Route::get('/banners', [\App\Http\Controllers\Admin\CmsController::class, 'banners'])->name('cms.banners');
    Route::post('/banners', [\App\Http\Controllers\Admin\CmsController::class, 'storeBanner'])->name('cms.banners.store');
    Route::delete('/banners/{id}', [\App\Http\Controllers\Admin\CmsController::class, 'destroyBanner'])->name('cms.banners.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\CmsController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\CmsController::class, 'updateSettings'])->name('settings.update');

    // Payouts (Admin manages vendor payouts)
    Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'create'])->name('payouts.create');
    Route::post('/payouts/{id}/process', [\App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('payouts.process');

    // Reports
    Route::get('/reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/vendors', [\App\Http\Controllers\Admin\ReportController::class, 'vendors'])->name('reports.vendors');

    Route::get('/storage/product-uploads', [\App\Http\Controllers\Admin\StorageSettingsController::class, 'edit'])->name('storage.edit');
    Route::post('/storage/product-uploads', [\App\Http\Controllers\Admin\StorageSettingsController::class, 'update'])->name('storage.update');

    // Themes
    Route::get('/themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
    Route::post('/themes/upload', [\App\Http\Controllers\Admin\ThemeController::class, 'upload'])->name('themes.upload');
    Route::post('/themes/{theme}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');
    Route::get('/themes/customize', [\App\Http\Controllers\Admin\ThemeController::class, 'customize'])->name('themes.customize');
    Route::post('/themes/customize', [\App\Http\Controllers\Admin\ThemeController::class, 'saveCustomization'])->name('themes.customize.save');

    // Plugins
    Route::get('/plugins', [\App\Http\Controllers\Admin\PluginController::class, 'index'])->name('plugins.index');
    Route::post('/plugins/upload', [\App\Http\Controllers\Admin\PluginController::class, 'upload'])->name('plugins.upload');
    Route::post('/plugins/{slug}/install', [\App\Http\Controllers\Admin\PluginController::class, 'install'])->name('plugins.install');
    Route::post('/plugins/{slug}/enable', [\App\Http\Controllers\Admin\PluginController::class, 'enable'])->name('plugins.enable');
    Route::post('/plugins/{slug}/disable', [\App\Http\Controllers\Admin\PluginController::class, 'disable'])->name('plugins.disable');
    Route::delete('/plugins/{slug}/uninstall', [\App\Http\Controllers\Admin\PluginController::class, 'uninstall'])->name('plugins.uninstall');
    Route::get('/plugins/{slug}/settings', [\App\Http\Controllers\Admin\PluginController::class, 'settings'])->name('plugins.settings');
    Route::post('/plugins/{slug}/settings', [\App\Http\Controllers\Admin\PluginController::class, 'saveSettings'])->name('plugins.settings.save');
    Route::delete('/plugins/{slug}/purge', [\App\Http\Controllers\Admin\PluginController::class, 'destroy'])->name('plugins.destroy');
});

// ── Vendor Area ──
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class)->except(['show']);
    Route::get('/orders', [\App\Http\Controllers\Vendor\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Vendor\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/advance', [\App\Http\Controllers\Vendor\OrderController::class, 'advance'])->name('orders.advance');
    Route::get('/earnings', [\App\Http\Controllers\Vendor\EarningController::class, 'index'])->name('earnings.index');
    Route::get('/payouts', [\App\Http\Controllers\Vendor\EarningController::class, 'payouts'])->name('payouts.index');
    Route::get('/payouts/{id}/receipt', [\App\Http\Controllers\Vendor\EarningController::class, 'payoutReceipt'])->name('payouts.receipt');
    Route::get('/settings', [\App\Http\Controllers\Vendor\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Vendor\SettingsController::class, 'update'])->name('settings.update');
});

// ── Public Store Pages ──
Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{slug}', [\App\Http\Controllers\StoreController::class, 'show'])->name('stores.show');

// Plugin admin routes (must live here so `route:cache` includes them; see MarketingTrackingServiceProvider for views)
if (file_exists(base_path('plugins/marketing-tracking/routes/web.php'))) {
    require base_path('plugins/marketing-tracking/routes/web.php');
}
