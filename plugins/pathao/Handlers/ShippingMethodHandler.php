<?php

namespace Plugins\Pathao\Handlers;

use App\Models\Plugin;

class ShippingMethodHandler
{
    /**
     * Register Pathao shipping methods into checkout.
     */
    public function register($payload): array
    {
        $methods = $payload['value'] ?? [];
        
        // Read live settings from database
        $plugin = Plugin::where('slug', 'pathao')->first();
        $settings = $plugin?->settings ?? [];

        $insideCityRate  = (float) ($settings['inside_city_rate'] ?? 60);
        $outsideCityRate = (float) ($settings['outside_city_rate'] ?? 120);
        
        $methods['pathao_city'] = [
            'id'             => 'pathao_city',
            'name'           => 'Pathao Inside City',
            'cost'           => $insideCityRate,
            'estimated_days' => '1-2 Days',
        ];

        $methods['pathao_outside_city'] = [
            'id'             => 'pathao_outside_city',
            'name'           => 'Pathao Outside City',
            'cost'           => $outsideCityRate,
            'estimated_days' => '3-5 Days',
        ];

        return $methods;
    }
}
