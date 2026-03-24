@extends('layouts.admin')

@section('title', $user->name)

@section('content')

    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start gap-4">
        <div class="flex items-start gap-4 min-w-0">
            <a href="{{ route('admin.users.index') }}" class="shrink-0 w-11 h-11 rounded-xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-brand-600 transition" title="Back">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex items-center gap-4 min-w-0">
                <img src="{{ $user->display_avatar }}" alt="" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-600 shrink-0" loading="lazy">
                <div class="min-w-0">
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white font-heading truncate">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-brand-500/10 text-brand-600 dark:text-brand-400">{{ $role->name }}</span>
                        @endforeach
                        @php
                            $statusClass = match ($user->status) {
                                'active' => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300 dark:border-emerald-800',
                                'inactive' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-600',
                                'banned' => 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-900/25 dark:text-rose-300 dark:border-rose-800',
                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/25 dark:text-amber-300 dark:border-amber-800',
                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border {{ $statusClass }}">{{ $user->status }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition shadow-lg shadow-brand-500/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('admin.users.status', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                @if($user->status !== 'banned')
                    <input type="hidden" name="status" value="banned">
                    <button type="submit" class="inline-flex items-center bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300 font-bold px-4 py-2.5 rounded-xl text-sm border border-rose-200 dark:border-rose-800 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition" onclick="return confirm('Ban this user?');">Ban</button>
                @else
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="inline-flex items-center bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300 font-bold px-4 py-2.5 rounded-xl text-sm border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition">Restore</button>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/25 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-medium">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Account</h3>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">User ID</dt>
                        <dd class="font-mono font-semibold text-slate-900 dark:text-white mt-0.5">#{{ $user->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Phone</dt>
                        <dd class="text-slate-800 dark:text-slate-200 mt-0.5">{{ $user->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Orders</dt>
                        <dd class="text-2xl font-black text-brand-600 dark:text-brand-400 tabular-nums mt-0.5">{{ number_format($user->orders_count) }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Joined</dt>
                        <dd class="text-slate-800 dark:text-slate-200 mt-0.5">{{ $user->created_at->format('M j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Last updated</dt>
                        <dd class="text-slate-800 dark:text-slate-200 mt-0.5">{{ $user->updated_at->format('M j, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Marketing</dt>
                        <dd class="mt-0.5">{{ $user->marketing_opt_in ? 'Opted in' : 'Opted out' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-slate-900 rounded-3xl border border-slate-700 p-6 text-white shadow-lg">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Addresses</h3>
                <ul class="space-y-3">
                    @forelse($user->addresses as $addr)
                        <li class="p-4 rounded-2xl bg-white/5 border border-white/10 text-sm">
                            <div class="flex justify-between items-center gap-2 mb-1">
                                <span class="font-bold">{{ $addr->title ?? 'Address' }}</span>
                                @if($addr->is_default)
                                    <span class="text-[10px] font-bold uppercase bg-brand-600 px-2 py-0.5 rounded">Default</span>
                                @endif
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed">{{ $addr->address_line1 }}, {{ $addr->city }}{{ $addr->state ? ', '.$addr->state : '' }} {{ $addr->zip_code }}</p>
                            <p class="text-[11px] text-brand-400 mt-1">{{ $addr->country }}</p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500 text-center py-6 border border-dashed border-white/10 rounded-2xl">No saved addresses.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/80 dark:bg-slate-800/40">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">Recent orders</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Latest {{ $user->orders->count() }} shown</p>
                    </div>
                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="inline-flex items-center justify-center text-sm font-bold text-brand-600 dark:text-brand-400 hover:underline">View all in orders →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-500 uppercase tracking-wider bg-slate-50/50 dark:bg-slate-800/30">
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($user->orders as $order)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 cursor-pointer transition-colors" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                                    <td class="px-5 py-4">
                                        <span class="font-mono font-semibold text-brand-600 dark:text-brand-400">{{ $order->order_number }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold uppercase border
                                            {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400' }}
                                        ">{{ $order->status }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-bold tabular-nums">৳{{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-12 text-center text-slate-500">No orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
