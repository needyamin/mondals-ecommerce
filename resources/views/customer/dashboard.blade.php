@extends('layouts.customer')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-10">
    <!-- Welcome Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-800 rounded-3xl p-8 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-heading font-bold mb-2">Marhaban, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p class="text-indigo-100/80 font-light text-sm">Welcome back to your shopping terminal. Track your orders and see what's new.</p>
            </div>
            <div class="mt-6 md:mt-0 flex items-center space-x-4">
                <a href="{{ route('products') }}" class="px-6 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-sm font-bold hover:bg-white hover:text-indigo-900 transition-all duration-300">Start Shopping</a>
            </div>
        </div>
        <!-- Background Ornaments -->
        <div class="absolute -top-[50%] -right-[10%] w-[300px] h-[300px] bg-white/10 blur-[80px] rounded-full pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-sm hover:translate-y-[-4px] transition-transform duration-300">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 border border-indigo-100 dark:border-indigo-800/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Orders</p>
            <h3 class="text-3xl font-heading font-bold text-slate-900 dark:text-white">{{ $stats['total_orders'] }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-sm hover:translate-y-[-4px] transition-transform duration-300">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 border border-amber-100 dark:border-amber-800/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Pending Shipment</p>
            <h3 class="text-3xl font-heading font-bold text-slate-900 dark:text-white">{{ $stats['pending_orders'] }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-sm hover:translate-y-[-4px] transition-transform duration-300">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4 border border-rose-100 dark:border-rose-800/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">In Wishlist</p>
            <h3 class="text-3xl font-heading font-bold text-slate-900 dark:text-white">{{ $stats['wishlist_count'] }}</h3>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white">Recent Orders</h3>
            <a href="{{ route('customer.orders.index') }}" class="text-sm font-bold text-primary hover:text-indigo-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total</th>
                        <th class="px-8 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($recentOrders as $order)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-8 py-5 text-sm font-bold text-slate-900 dark:text-white">#{{ $order->order_number }}</td>
                            <td class="px-8 py-5 text-sm text-slate-500 dark:text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ 
                                    $order->status === 'completed' ? 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400' : 
                                    ($order->status === 'pending' ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' :
                                    'bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-400')
                                }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-900 dark:text-white text-right">TK {{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="text-xs font-bold text-slate-400 hover:text-primary transition-colors">Details &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-500 dark:text-slate-400">No orders placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
