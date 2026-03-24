<?php

namespace Plugins\MarketingTracking;

use Illuminate\Support\ServiceProvider;

class MarketingTrackingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'marketing-tracking');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
