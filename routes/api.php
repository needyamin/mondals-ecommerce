<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('force-json')->group(function () {
    
    // Public Auth Routes
    Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);

    // Public Products & Categories
    Route::get('/categories/tree', [\App\Http\Controllers\Api\V1\CategoryController::class, 'index']);
    Route::get('/categories/featured', [\App\Http\Controllers\Api\V1\CategoryController::class, 'featured']);
    Route::get('/products', [\App\Http\Controllers\Api\V1\ProductController::class, 'index']);
    Route::get('/products/featured', [\App\Http\Controllers\Api\V1\ProductController::class, 'featured']);
    Route::get('/products/{slug}', [\App\Http\Controllers\Api\V1\ProductController::class, 'show']);

    // Public Reviews
    Route::get('/products/{productId}/reviews', [\App\Http\Controllers\Api\V1\ReviewController::class, 'index']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Authenticated User & Profile
        Route::get('/user', [\App\Http\Controllers\Api\Auth\AuthController::class, 'profile']);
        Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);

        // Addresses
        Route::apiResource('addresses', \App\Http\Controllers\Api\V1\AddressController::class)->except(['show']);

        // Orders
        Route::get('/orders', [\App\Http\Controllers\Api\V1\OrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\V1\OrderController::class, 'show']);
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Api\V1\OrderController::class, 'cancel']);

        // Wishlist
        Route::get('/wishlists', [\App\Http\Controllers\Api\V1\WishlistController::class, 'index']);
        Route::post('/wishlists/toggle', [\App\Http\Controllers\Api\V1\WishlistController::class, 'toggle']);

        // Reviews (Submit)
        Route::post('/reviews', [\App\Http\Controllers\Api\V1\ReviewController::class, 'store']);

        // Cart
        Route::get('/cart', [\App\Http\Controllers\Api\V1\CartController::class, 'index']);
        Route::post('/cart/items', [\App\Http\Controllers\Api\V1\CartController::class, 'addItem']);
        Route::put('/cart/items/{id}', [\App\Http\Controllers\Api\V1\CartController::class, 'updateItem']);
        Route::delete('/cart/items/{id}', [\App\Http\Controllers\Api\V1\CartController::class, 'removeItem']);
        Route::post('/cart/coupon', [\App\Http\Controllers\Api\V1\CartController::class, 'applyCoupon']);
        Route::delete('/cart/coupon', [\App\Http\Controllers\Api\V1\CartController::class, 'removeCoupon']);
        Route::delete('/cart', [\App\Http\Controllers\Api\V1\CartController::class, 'clearCart']);

        // Checkout
        Route::post('/checkout/calculate', [\App\Http\Controllers\Api\V1\CheckoutController::class, 'calculate']);
        Route::post('/checkout/order', [\App\Http\Controllers\Api\V1\CheckoutController::class, 'placeOrder']);

        // Vendor Application & Profile
        Route::post('/vendor/apply', [\App\Http\Controllers\Api\V1\VendorRegistrationController::class, 'apply']);
        Route::get('/vendor/profile', [\App\Http\Controllers\Api\V1\VendorRegistrationController::class, 'profile']);
        Route::put('/vendor/profile', [\App\Http\Controllers\Api\V1\VendorRegistrationController::class, 'updateProfile']);
    });

    // Public Store Listings (API)
    Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);
    Route::get('/stores/{slug}', [\App\Http\Controllers\StoreController::class, 'show']);
});
