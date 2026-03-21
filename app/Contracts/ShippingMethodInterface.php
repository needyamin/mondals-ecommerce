<?php

namespace App\Contracts;

use App\Models\Order;

interface ShippingMethodInterface
{
    /**
     * Get the shipping method identifier.
     */
    public function getIdentifier(): string;

    /**
     * Get the human-readable name.
     */
    public function getName(): string;

    /**
     * Calculate shipping cost for an order.
     */
    public function calculateCost(Order $order, array $options = []): float;

    /**
     * Check if this method is available for the given address/zone.
     */
    public function isAvailable(array $address): bool;

    /**
     * Get estimated delivery time in days.
     */
    public function getEstimatedDays(array $address): ?int;
}
