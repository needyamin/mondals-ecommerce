<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->paginate(20);
        $unreadCount = $request->user()->unreadNotifications()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function visit(string $id): RedirectResponse
    {
        $n = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();
        $url = is_array($n->data) ? ($n->data['url'] ?? null) : null;

        return redirect()->to($url ?: route('admin.dashboard'));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
