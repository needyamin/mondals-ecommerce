<?php

namespace Plugins\MarketingTracking;

use Illuminate\Support\ServiceProvider;

class MarketingTrackingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'marketing-tracking');
        // Routes are required from routes/web.php so route:cache registers admin.marketing.*
    }
}
