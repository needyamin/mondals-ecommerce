@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')

    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Orders</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Monitor all incoming sales, process shipments, and manage refunds.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.export', request()->query()) }}" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Order Data
            </a>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-2 mb-6 flex flex-col md:flex-row items-center justify-between">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-grow w-full md:w-auto relative group">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-transparent border-none focus:ring-0 pl-12 pr-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500" placeholder="Search by Order ID, Customer Name, or Email...">
        </form>
        <div class="w-full md:w-auto flex flex-wrap items-center gap-2 p-2 border-t md:border-t-0 md:border-l border-slate-100 dark:border-slate-800">
            <select name="status" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 py-2.5 px-4 cursor-pointer">
                <option value="">Status: All</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select name="payment_status" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 py-2.5 px-4 cursor-pointer hidden sm:block">
                <option value="">Payment: All</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <button type="submit" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 p-2.5 rounded-xl transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Order ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Date placed</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Payment</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                        
                        <td class="px-6 py-4">
                            <span class="text-sm font-extrabold text-brand-600 dark:text-brand-400 font-heading">#{{ $order->id }}</span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 block">{{ $order->created_at->format('M d, Y') }}</span>
                            <span class="text-xs text-slate-400 font-mono">{{ $order->created_at->format('h:i A') }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs">
                                    {{ substr($order->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->user->name ?? 'Guest User' }}</span>
                                    <span class="text-xs text-slate-500">{{ $order->user->email ?? $order->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-extrabold text-slate-900 dark:text-white">৳{{ number_format($order->total, 2) }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold
                                {{ $order->payment_status === 'paid' ? 'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-900/20' : '' }}
                                {{ $order->payment_status === 'pending' ? 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-900/20' : '' }}
                                {{ $order->payment_status === 'failed' ? 'text-rose-600 bg-rose-50 dark:text-rose-400 dark:bg-rose-900/20' : '' }}
                                {{ $order->payment_status === 'refunded' ? 'text-slate-600 bg-slate-100 dark:text-slate-400 dark:bg-slate-800' : '' }}
                            ">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border 
                                {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : '' }}
                                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50 dark:text-amber-400' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800/50 dark:text-blue-400' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-900/20 dark:border-indigo-800/50 dark:text-indigo-400' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400' : '' }}
                            ">
                                @if(in_array($order->status, ['delivered', 'shipped']))
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                @endif
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center text-sm font-bold text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300" onclick="event.stopPropagation();">
                                View Details &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No orders found</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">No sales matching this criteria are currently in the database.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($orders) && $orders->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            {{ $orders->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endsection
