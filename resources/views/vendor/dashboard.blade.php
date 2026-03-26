@extends('layouts.vendor')

@section('title', 'Vendor Overview')

@section('content')

    <!-- Welcome -->
    <div class="mb-10 text-center md:text-left flex flex-col md:flex-row items-center justify-between">
        <div>
            @if(auth()->user()->vendor->status !== 'approved')
                <div class="mb-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400 p-4 rounded-xl flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><strong>Action Required:</strong> Your store is currently awaiting admin approval. You can upload products, but they will remain inactive until approved.</span>
                </div>
            @endif
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">
                Welcome back, <span class="text-vendor-600 dark:text-vendor-400">{{ $vendor->store_name ?? 'Partner' }}</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light">Here's the latest update from your digital boutique.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('vendor.products.create') ?? '#' }}" class="bg-vendor-600 hover:bg-vendor-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-vendor-500/30 transition flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Upload New Product
            </a>
        </div>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">
        
        <!-- Total Earnings -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Net Income</p>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">৳{{ number_format($stats['total_earnings'] ?? 0, 2) }}</h3>
        </div>

        <!-- Available Balance -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Available to Withdraw</p>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">৳{{ number_format($stats['available_balance'] ?? 0, 2) }}</h3>
            @if(($stats['available_balance'] ?? 0) > 0)
                <span class="text-xs text-indigo-500 font-bold mt-2 hover:underline cursor-pointer">Request Payout &rarr;</span>
            @endif
        </div>

        <!-- Inventory Size -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-500/10 rounded-full blur-xl pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Active Listings</p>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">{{ $stats['active_products'] ?? 0 }} <span class="text-lg text-slate-400 font-normal">/ {{ $stats['total_products'] ?? 0 }} total</span></h3>
        </div>

        <!-- Pending Fulfillments -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/10 rounded-full blur-xl pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Orders to fulfill</p>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">{{ $stats['pending_orders'] ?? 0 }}</h3>
            @if(($stats['pending_orders'] ?? 0) > 0)
                <span class="text-xs text-amber-500 font-bold mt-2 flex items-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Requires Action</span>
            @endif
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-8 pb-10 mb-12">
        <h3 class="text-2xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Vendor Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('vendor.products.create') }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-vendor-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-vendor-100 dark:bg-vendor-900/40 text-vendor-600 dark:text-vendor-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-vendor-600 dark:group-hover:text-vendor-400">Add Product</span>
            </a>
            <a href="{{ route('vendor.products.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-blue-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Manage Products</span>
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-amber-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-amber-600 dark:group-hover:text-amber-400">Manage Orders</span>
            </a>
            <a href="{{ route('vendor.settings.index') }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-slate-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">Store Settings</span>
            </a>
        </div>
    </div>

    <!-- Recent Orders List -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">Recent Received Orders</h3>
            <a href="{{ route('vendor.orders.index') ?? '#' }}" class="text-sm font-medium text-vendor-500 hover:text-vendor-600">View All &nearr;</a>
        </div>
        
        <div class="overflow-x-auto">
            @if(isset($recentOrders) && $recentOrders->count() > 0)
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="pb-4 pt-2">Order ID</th>
                        <th class="pb-4 pt-2">Date</th>
                        <th class="pb-4 pt-2">Customer</th>
                        <th class="pb-4 pt-2">Status</th>
                        <th class="pb-4 pt-2">Amount (Your Cut)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 font-medium text-slate-900 dark:text-white">#{{ $order->id }}</td>
                            <td class="py-4 text-slate-500 dark:text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-4 text-slate-500 dark:text-slate-400 flex items-center">
                                <span class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 text-xs flex items-center justify-center mr-2 text-slate-600 dark:text-slate-300">
                                    {{ substr($order->user->name ?? 'C', 0, 1) }}
                                </span>
                                {{ $order->user->name ?? 'Guest' }}
                            </td>
                            <td class="py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full 
                                    {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' : '' }}
                                    {{ !in_array($order->status, ['delivered', 'pending', 'cancelled']) ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 font-medium text-slate-900 dark:text-white font-heading">
                                ৳{{ number_format($order->items->where('vendor_id', $vendor->id)->sum('total'), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-10">
                <p class="text-slate-500">You haven't received any orders yet. Keep pushing your catalogs!</p>
            </div>
            @endif
        </div>
    </div>

@endsection
