@extends('layouts.admin')

@section('title', 'Vendors')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Vendor directory</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Review applications, commission rates, and seller activity.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.vendors.export', request()->query()) }}" class="inline-flex items-center bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm text-sm">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.vendors.create') }}" class="inline-flex items-center bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/25 transition text-sm">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add vendor
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @php
        $qBase = array_filter(['search' => request('search')]);
        $activeAll = !request()->filled('status');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <a href="{{ route('admin.vendors.index', $qBase) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-600 {{ $activeAll ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">All</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </a>
        <a href="{{ route('admin.vendors.index', $qBase + ['status' => 'approved']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-emerald-50 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800 {{ request('status') === 'approved' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Approved</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-emerald-950 dark:text-emerald-50">{{ number_format($stats['approved']) }}</p>
        </a>
        <a href="{{ route('admin.vendors.index', $qBase + ['status' => 'pending']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 {{ request('status') === 'pending' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-900 dark:text-amber-400">Pending</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-amber-950 dark:text-amber-100">{{ number_format($stats['pending']) }}</p>
        </a>
        <a href="{{ route('admin.vendors.index', $qBase + ['status' => 'suspended']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 {{ request('status') === 'suspended' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-800 dark:text-rose-400">Suspended</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-rose-950 dark:text-rose-50">{{ number_format($stats['suspended']) }}</p>
        </a>
        <a href="{{ route('admin.vendors.index', $qBase + ['status' => 'rejected']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 {{ request('status') === 'rejected' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Rejected</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-800 dark:text-slate-100">{{ number_format($stats['rejected']) }}</p>
        </a>
    </div>

    {{-- Filters: one form so search + status work together --}}
    <form method="GET" action="{{ route('admin.vendors.index') }}" class="bg-white dark:bg-darkpanel rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-2 mb-6 flex flex-col sm:flex-row gap-2 sm:items-center">
        <div class="flex-1 relative min-w-0">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Store name, vendor email, or owner name…">
        </div>
        <div class="flex gap-2 shrink-0">
            <select name="status" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 py-2.5 px-4 min-w-[150px] focus:ring-2 focus:ring-brand-500">
                <option value="">All statuses</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition">Apply</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.vendors.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Contact</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Products</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center hidden md:table-cell" title="Order line items">Lines</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Commission</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3 min-w-[220px]">
                                <div class="flex shrink-0 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 shadow-sm">
                                    @if($vendor->display_banner)
                                        <div class="hidden sm:block w-14 h-12 relative">
                                            <img src="{{ $vendor->display_banner }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="w-12 h-12 sm:w-11 sm:h-12 flex items-center justify-center">
                                        <img src="{{ $vendor->display_image }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $vendor->store_name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $vendor->user->name ?? 'No owner' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <p class="text-sm text-slate-700 dark:text-slate-300 truncate max-w-[200px]" title="{{ $vendor->email }}">{{ $vendor->email }}</p>
                            @if($vendor->phone)
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $vendor->phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $vendor->products_count }}</span>
                        </td>
                        <td class="px-5 py-4 text-center hidden md:table-cell">
                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $vendor->orders_count }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $vendor->commission_rate }}%</span>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $st = $vendor->status;
                                $badge = match($st) {
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300 dark:border-emerald-800',
                                    'pending' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/25 dark:text-amber-300 dark:border-amber-800',
                                    'suspended' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/25 dark:text-rose-300 dark:border-rose-800',
                                    'rejected' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                    default => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                                $label = $st === 'pending' ? 'Pending review' : ucfirst($st);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-brand-600 text-white hover:bg-brand-700 transition shadow-sm" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.products.index', ['vendor_id' => $vendor->id]) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition" title="Products">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="max-w-sm mx-auto">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No vendors match</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Try another search or status filter, or add a new vendor.</p>
                                <a href="{{ route('admin.vendors.create') }}" class="inline-flex items-center text-sm font-bold text-brand-600 dark:text-brand-400 hover:underline">Add vendor →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendors->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
            {{ $vendors->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endsection
