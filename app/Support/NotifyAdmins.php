<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NotifyAdmins
{
    public static function send(Notification $notification): void
    {
        $admins = User::role('admin')->get();
        if ($admins->isEmpty()) {
            return;
        }
        \Illuminate\Support\Facades\Notification::send($admins, $notification);
    }
}
