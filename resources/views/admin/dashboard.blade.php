@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Welcome Section -->
    <div class="mb-10 text-center md:text-left flex flex-col md:flex-row items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">
                Welcome back, <span class="text-brand-600 dark:text-brand-400">{{ auth()->user()->name ?? 'Administrator' }}</span> 👋
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light">Here's the latest high-level overview of your digital platform.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <button class="bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Last 30 Days
            </button>
            <a href="{{ route('admin.reports.sales') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-brand-500/30 transition flex items-center transform hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Export
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">
        
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex flex-col sm:flex-row sm:items-start gap-4 relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner border border-emerald-200 dark:border-emerald-800/50">
                <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="min-w-0 flex-1 w-full">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Sales</p>
                <h3 class="text-lg sm:text-xl lg:text-2xl font-extrabold text-slate-900 dark:text-white font-heading tabular-nums tracking-tight leading-snug break-words hyphens-none">৳{{ number_format($stats['total_sales'] ?? 105420.50, 2) }}</h3>
                <span class="text-xs text-emerald-500 font-bold mt-2 flex items-center"><svg class="w-3 h-3 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> 12.5%</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex items-center relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-inner border border-blue-200 dark:border-blue-800/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Today's Orders</p>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $stats['orders_today'] ?? 42 }}</h3>
                @if(($stats['pending_orders'] ?? 12) > 0)
                    <span class="text-xs text-amber-500 font-bold mt-2 flex items-center">{{ $stats['pending_orders'] ?? 12 }} pending</span>
                @else
                    <span class="text-xs text-slate-500 font-bold mt-2 flex items-center">Up to date</span>
                @endif
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex items-center relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-brand-100 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-inner border border-brand-200 dark:border-brand-800/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Live Products</p>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $stats['active_products'] ?? 1403 }}</h3>
                <span class="text-xs text-brand-500 font-bold mt-2 flex items-center">Across 12 catalogs</span>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 flex items-center relative overflow-hidden group hover:-translate-y-1 transition duration-300">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 shadow-inner border border-purple-200 dark:border-purple-800/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">Approved Vendors</p>
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $stats['approved_vendors'] ?? 45 }}</h3>
                @if(($stats['pending_vendors'] ?? 3) > 0)
                    <span class="text-xs text-rose-500 font-bold mt-2 flex items-center">{{ $stats['pending_vendors'] ?? 3 }} awaiting approval</span>
                @else
                    <span class="text-xs text-slate-500 font-bold mt-2 flex items-center">None pending</span>
                @endif
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-8 pb-10">
        <h3 class="text-2xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Operator Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            
            <a href="{{ route('admin.products.create') ?? '#' }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-brand-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-brand-600 dark:group-hover:text-brand-400">Launch Product</span>
            </a>
            
            <a href="{{ route('admin.coupons.create') ?? '#' }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-pink-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-pink-600 dark:group-hover:text-pink-400">Create Campaign</span>
            </a>

            <a href="{{ route('admin.orders.index') ?? '#' }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-blue-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Manage Orders</span>
            </a>

            <a href="{{ route('admin.settings') ?? '#' }}" class="group flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition-all border border-slate-100 dark:border-slate-700 hover:border-slate-500 hover:shadow-xl hover:-translate-y-1 block">
                <div class="w-14 h-14 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">Platform Config</span>
            </a>

        </div>
    </div>

@endsection
