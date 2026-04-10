<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (! auth()->check()) {
                return;
            }
            $user = auth()->user();
            $view->with([
                'adminHeaderNotifications' => $user->notifications()->latest()->limit(15)->get(),
                'adminUnreadNotificationCount' => $user->unreadNotifications()->count(),
            ]);
        });
    }
}
