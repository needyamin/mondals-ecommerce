@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-slate-600 dark:text-slate-400 text-sm">In-app alerts for admins.</p>
    @if($notifications->total() > 0 && $unreadCount > 0)
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-brand-600 dark:text-brand-400 hover:underline">Mark all as read</button>
        </form>
    @endif
</div>

<div class="glass-panel rounded-xl overflow-hidden">
    @forelse($notifications as $n)
        @php
            $data = is_array($n->data) ? $n->data : [];
            $title = $data['title'] ?? 'Notification';
            $body = $data['body'] ?? '';
        @endphp
        <a href="{{ route('admin.notifications.visit', $n->id) }}" class="flex gap-4 px-4 py-3 border-b border-slate-100 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $n->read_at ? 'opacity-70' : '' }}">
            <div class="flex-1 min-w-0">
                <p class="font-medium text-slate-900 dark:text-white">{{ $title }}</p>
                @if($body !== '')
                    <p class="text-sm text-slate-600 dark:text-slate-400 truncate">{{ $body }}</p>
                @endif
                <p class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
            </div>
            @unless($n->read_at)
                <span class="shrink-0 w-2 h-2 mt-2 rounded-full bg-brand-500"></span>
            @endunless
        </a>
    @empty
        <p class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No notifications yet.</p>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="mt-6">{{ $notifications->links() }}</div>
@endif
@endsection
