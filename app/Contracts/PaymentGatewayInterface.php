<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Get the gateway identifier.
     */
    public function getIdentifier(): string;

    /**
     * Get the human-readable name.
     */
    public function getName(): string;

    /**
     * Initiate a payment and return a redirect URL or response data.
     */
    public function initiatePayment(Order $order, array $options = []): array;

    /**
     * Verify/validate a payment callback.
     */
    public function verifyPayment(array $payload): bool;

    /**
     * Process refund.
     */
    public function refund(Order $order, float $amount): bool;

    /**
     * Check if the gateway supports the given currency.
     */
    public function supportsCurrency(string $currency): bool;
}
