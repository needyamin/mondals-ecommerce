<?php

namespace Plugins\ProductReviews;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProductReviewsServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'product-reviews');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->app->booted(function () {
            Route::middleware(['api', 'force-json'])
                ->prefix('api/v1')
                ->group(__DIR__.'/../routes/api.php');
        });
    }
}
