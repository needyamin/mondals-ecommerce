<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Notifications\Notification;

class VendorApplicationAdminNotification extends Notification
{
    public function __construct(public Vendor $vendor) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $v = $this->vendor;

        return [
            'title' => 'Vendor application',
            'body' => "{$v->store_name} applied for review.",
            'url' => route('admin.vendors.show', $v->id),
            'type' => 'vendor',
        ];
    }
}
