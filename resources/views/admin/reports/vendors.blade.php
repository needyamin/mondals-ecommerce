@extends('layouts.admin')

@section('title', 'Vendor Intelligence')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Merchant Performance</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Audit independent vendor success arrays, tracking their direct monetary contribution.</p>
        </div>
        
        <a href="{{ route('admin.reports.vendors', ['export' => 1]) }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition text-sm">
            <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download accounting sheet (CSV)
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Registered Merchant</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Published Products</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Fufilled Orders</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Vendor Net Earnings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900 dark:text-white block">{{ $vendor->store_name }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $vendor->email }}</span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full">{{ number_format($vendor->products_count) }}</span>
                        </td>
                        
                         <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-900/20 px-3 py-1 rounded-full">{{ number_format($vendor->orders_count) }}</span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">৳{{ number_format($vendor->earnings_sum_vendor_earning ?? 0, 2) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-500">
                            No approved vendor performance data synced yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>

@endsection
