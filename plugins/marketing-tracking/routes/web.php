<?php

use Illuminate\Support\Facades\Route;
use Plugins\MarketingTracking\Http\Controllers\Admin\MarketingTrackingController;

Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/marketing', [MarketingTrackingController::class, 'edit'])->name('marketing.edit');
    Route::put('/marketing', [MarketingTrackingController::class, 'update'])->name('marketing.update');
});
