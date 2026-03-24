<?php

use Illuminate\Support\Facades\Route;
use Plugins\IpBlocking\Http\Controllers\Admin\IpBlockingController;

Route::middleware(['web', 'auth', 'admin'])->prefix('admin/ip-blocking')->name('admin.ip-blocking.')->group(function () {
    Route::get('/', [IpBlockingController::class, 'index'])->name('index');
    Route::post('/ips', [IpBlockingController::class, 'storeIp'])->name('ips.store');
    Route::delete('/ips/{blockedIp}', [IpBlockingController::class, 'destroyIp'])->name('ips.destroy');
    Route::post('/users/ban', [IpBlockingController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [IpBlockingController::class, 'unbanUser'])->name('users.unban');
});
