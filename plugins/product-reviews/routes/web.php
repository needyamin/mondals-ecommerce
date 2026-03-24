<?php

use Illuminate\Support\Facades\Route;
use Plugins\ProductReviews\Http\Controllers\Admin\ReviewController as AdminReviewController;
use Plugins\ProductReviews\Http\Controllers\StorefrontReviewController;
use Plugins\ProductReviews\Http\Controllers\Vendor\ReviewController as VendorReviewController;

Route::middleware('web')->group(function () {
    Route::post('/product/{slug}/reviews', [StorefrontReviewController::class, 'store'])
        ->middleware('auth')
        ->name('product.reviews.store');

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/reviews', [VendorReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [VendorReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [VendorReviewController::class, 'reject'])->name('reviews.reject');
    });
});
