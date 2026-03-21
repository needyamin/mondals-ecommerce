<?php

namespace Plugins\FlatRateShipping;

use App\Contracts\ShippingMethodInterface;
use App\Models\Order;

class FlatRateShippingMethod implements ShippingMethodInterface
{
    protected array $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function getIdentifier(): string { return 'flat_rate'; }
    public function getName(): string { return 'Flat Rate Shipping'; }

    /**
     * Calculate shipping cost based on zone.
     */
    public function calculateCost(Order $order, array $options = []): float
    {
        $freeThreshold = $this->settings['free_shipping_threshold'] ?? 2000;

        // Free shipping above threshold
        if ($order->subtotal >= $freeThreshold) return 0;

        $city = strtolower($order->shipping_city ?? ($options['city'] ?? 'other'));
        $zones = $this->settings['zones'] ?? [];

        // Match zone or fall back to default
        foreach ($zones as $zone => $rate) {
            if (str_contains($city, strtolower($zone))) {
                return (float) $rate;
            }
        }

        return (float) ($this->settings['default_rate'] ?? 60);
    }

    /**
     * Available everywhere in Bangladesh.
     */
    public function isAvailable(array $address): bool
    {
        $country = strtolower($address['country'] ?? '');
        return $country === 'bangladesh' || $country === 'bd';
    }

    /**
     * Estimated delivery days by zone.
     */
    public function getEstimatedDays(array $address): ?int
    {
        $city = strtolower($address['city'] ?? '');

        if (str_contains($city, 'dhaka')) return 1;
        if (str_contains($city, 'chittagong') || str_contains($city, 'chattogram')) return 2;
        return 4;
    }
}
