<?php

use Illuminate\Support\Facades\Route;
use Plugins\ProductReviews\Http\Controllers\Api\ReviewController;

Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
});
