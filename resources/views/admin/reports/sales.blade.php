@extends('layouts.admin')

@section('title', 'Sales Intelligence')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Revenue Reporting</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Track financial growth and transactional throughput over time.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.sales', array_merge(request()->query(), ['export' => 1])) }}" class="bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 px-5 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center hover:bg-slate-50 dark:hover:bg-slate-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Data
            </a>

            <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex items-center gap-2 bg-white dark:bg-darkpanel p-1.5 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 py-2.5 px-4">
                <span class="text-slate-400">to</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 py-2.5 px-4">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white p-2.5 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white dark:bg-darkpanel rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:border-brand-500/50 transition-colors">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Gross Processed Revenue</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">৳{{ number_format($totalSales, 2) }}</h3>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-100 to-brand-50 dark:from-brand-900/40 dark:to-brand-800/20 text-brand-600 dark:text-brand-400 flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        
        <div class="bg-white dark:bg-darkpanel rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:border-indigo-500/50 transition-colors">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Orders Initiated</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">{{ number_format($totalOrders) }}</h3>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-100 to-indigo-50 dark:from-indigo-900/40 dark:to-indigo-800/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center transform group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>

    </div>

    <!-- Chart / Visual Presentation -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden p-6 mb-8">
        <h3 class="font-bold text-slate-900 dark:text-white mb-6 font-heading">Sales Trend Activity</h3>
        
        <div class="h-64 flex items-end justify-between gap-1 mt-8">
            <!-- Simulated Bar Chart from actual SQL Date arrays -->
            @php $maxTotal = $salesData->max('total') ?: 1; @endphp
            
            @forelse($salesData as $data)
                @php $heightPercentage = ($data->total / $maxTotal) * 100; @endphp
                <div class="w-full flex flex-col items-center group relative">
                    <!-- Tooltip -->
                    <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs py-1 px-2 rounded font-bold whitespace-nowrap z-10 pointer-events-none">
                        ৳{{ number_format($data->total, 2) }}
                        <div class="text-[10px] text-slate-400 font-normal">{{ \Carbon\Carbon::parse($data->date)->format('M d') }}</div>
                    </div>
                    
                    <div class="w-full max-w-[40px] bg-brand-500 dark:bg-brand-600 rounded-t-sm transition-all duration-300 group-hover:bg-brand-400 border-b border-white dark:border-darkpanel" style="height: {{ max($heightPercentage, 1) }}%"></div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <p class="text-sm font-medium">No sales recorded during this timeframe.</p>
                </div>
            @endforelse
        </div>
        
    </div>

@endsection
