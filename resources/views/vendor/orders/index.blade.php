@extends('layouts.vendor')

@section('title', 'Received Orders')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center group transition duration-300">
        <div class="z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Manage Your Sales Queue</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">Incoming orders from your collections across Bangladesh.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <div class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-darkpanel text-slate-600 dark:text-slate-400 font-bold shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Real-time Sync
            </div>
        </div>
    </div>

    <!-- Filters & Stats Overview -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-10">
        
        <div class="xl:col-span-9 bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <form action="{{ route('vendor.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Quick Find (Order ID)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ORD-2026-X1" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-900 dark:text-white uppercase font-mono tracking-tighter shadow-inner">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Fulfillment Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-600 dark:text-slate-300 shadow-inner">
                        <option value="">All Active Orders</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Successfully Delivered</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full h-[50px] bg-slate-900 dark:bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-800 transition flex items-center justify-center group">
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="xl:col-span-3 bg-vendor-600 dark:bg-vendor-900/40 rounded-3xl p-6 text-white shadow-xl shadow-vendor-600/20 flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-vendor-200 mb-1">Queue Overview</p>
            <h3 class="text-4xl font-extrabold font-heading">{{ $orders->total() }}</h3>
            <p class="text-sm font-medium text-vendor-100 mt-1 italic">Awaiting Action</p>
        </div>

    </div>

    <!-- Active Orders Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/10">
                        <th class="px-8 py-5">Order Reference</th>
                        <th class="px-8 py-5">Recipient Information</th>
                        <th class="px-8 py-5">Cart Depth</th>
                        <th class="px-8 py-5 text-center">Lifecycle Status</th>
                        <th class="px-8 py-5">Your Revenue (Net)</th>
                        <th class="px-8 py-5 text-right">Access Terminal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-slate-900 dark:text-white font-mono tracking-tighter mb-1">{{ $order->order_number }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">{{ $order->user->name ?? $order->shipping_first_name }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold font-heading">{{ $order->shipping_city }}, {{ $order->shipping_country }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->items->count() }} Items</span>
                                    <span class="text-[10px] text-slate-400 font-medium italic mt-1 truncate max-w-[120px]">
                                        {{ $order->items->pluck('product_name')->implode(', ') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    @php
                                        $statuses = [
                                            'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800/50',
                                            'confirmed'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800/50',
                                            'processing' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800/50',
                                            'shipped'    => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-800/50',
                                            'delivered'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                            'canceled'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800/50',
                                        ];
                                        $colorClass = $statuses[$order->status] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                                    @endphp
                                    <span class="px-4 py-1.5 text-[10px] font-bold rounded-full border uppercase tracking-[0.1em] {{ $colorClass }} font-heading">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-slate-900 dark:text-white tracking-tight">৳{{ number_format($order->items->sum('subtotal'), 2) }}</span>
                                    <span class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest">{{ $order->payment_status }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('vendor.orders.show', $order->id) }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-vendor-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-slate-900/10 hover:shadow-vendor-500/30 group">
                                    Inspect Order
                                    <svg class="w-3 h-3 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-200 mb-4 animate-bounce">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white mb-1">Your Order Deck is Empty</p>
                                    <p class="text-sm text-slate-400 font-light max-w-xs mx-auto mb-6">When customers purchase your products, they will appear here in real-time for fulfillment.</p>
                                    <a href="{{ route('vendor.dashboard') }}" class="text-xs font-bold text-vendor-500 uppercase tracking-widest hover:underline">Back to Terminal</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->total() > 15)
            <div class="p-6 bg-slate-50/50 dark:bg-slate-800/10 border-t border-slate-100 dark:border-slate-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
