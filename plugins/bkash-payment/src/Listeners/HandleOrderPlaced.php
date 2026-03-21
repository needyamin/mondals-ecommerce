<?php

namespace Plugins\BkashPayment\Listeners;

use App\Events\OrderPlaced;

class HandleOrderPlaced
{
    /**
     * When an order is placed with bKash payment method, send notification.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        if ($order->payment_method !== 'bkash') return;

        // Example: Log, send notification, or auto-initiate payment
        \Log::info("bKash: Order #{$order->order_number} placed, awaiting payment.");
    }
}
