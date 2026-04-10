<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification
{
    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $o = $this->order;
        $total = number_format((float) $o->total, 2);
        $cur = $o->currency ?: 'BDT';

        return [
            'title' => 'New order',
            'body' => "{$o->order_number} — {$cur} {$total}",
            'url' => route('admin.orders.show', $o->id),
            'type' => 'order',
        ];
    }
}
