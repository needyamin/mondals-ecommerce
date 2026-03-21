<?php

namespace Plugins\BkashPayment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Plugin;
use Illuminate\Support\ServiceProvider;

class BkashPaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the bKash gateway into the container
        $this->app->singleton('payment.bkash', function () {
            $plugin = Plugin::where('slug', 'bkash-payment')->first();
            $settings = $plugin?->settings ?? [];
            return new BkashGateway($settings);
        });

        // Also register as a tagged payment gateway
        $this->app->tag(['payment.bkash'], 'payment_gateways');
    }

    public function boot(): void
    {
        // Register plugin routes
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }

    /**
     * Called when the plugin is installed.
     */
    public function install(): void
    {
        // Any setup on install
    }

    /**
     * Called when the plugin is uninstalled.
     */
    public function uninstall(): void
    {
        // Any cleanup on uninstall
    }
}
