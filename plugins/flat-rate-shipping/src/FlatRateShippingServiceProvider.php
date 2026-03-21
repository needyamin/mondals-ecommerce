<?php

namespace Plugins\FlatRateShipping;

use App\Models\Plugin;
use Illuminate\Support\ServiceProvider;

class FlatRateShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('shipping.flat_rate', function () {
            $plugin = Plugin::where('slug', 'flat-rate-shipping')->first();
            $settings = $plugin?->settings ?? [];
            return new FlatRateShippingMethod($settings);
        });

        $this->app->tag(['shipping.flat_rate'], 'shipping_methods');
    }

    public function boot(): void
    {
        // Register hook for checkout discovery
        app('plugin.manager')->on('register_shipping_methods', function($methods) {
            $plugin = Plugin::where('slug', 'flat-rate-shipping')->first();
            $settings = $plugin?->settings ?? [];

            $defaultRate = (float) ($settings['default_rate'] ?? 60);
            $freeThreshold = (float) ($settings['free_shipping_threshold'] ?? 2000);
            
            $methods['flat_rate_plugin'] = [
                'id'             => 'flat_rate_plugin',
                'name'           => 'Flat Rate Shipping',
                'cost'           => $defaultRate,
                'estimated_days' => '2-4 Days',
                'free_above'     => $freeThreshold,
            ];
            return $methods;
        });
    }

    public function install(): void {}
    public function uninstall(): void {}
}
