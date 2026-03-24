@extends('layouts.admin')

@section('title', 'Payouts')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Merchant payouts</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Create disbursements and review payout history.</p>
        </div>
        <div class="rounded-2xl px-5 py-4 bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Unpaid liability</p>
            <p class="text-xl font-black tabular-nums text-brand-600 dark:text-brand-400 mt-1">৳ {{ number_format($vendorsWithBalance->sum('unpaid_balance')) }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/25 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @php
        $qBase = array_filter([
            'search' => request('search'),
            'vendor_id' => request('vendor_id'),
        ], fn ($v) => $v !== null && $v !== '');
        $activeAll = !request()->filled('status');
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <a href="{{ route('admin.payouts.index', $qBase) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-600 {{ $activeAll ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">All</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </a>
        <a href="{{ route('admin.payouts.index', $qBase + ['status' => 'pending']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 {{ request('status') === 'pending' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-900 dark:text-amber-400">Pending</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-amber-950 dark:text-amber-100">{{ number_format($stats['pending']) }}</p>
        </a>
        <a href="{{ route('admin.payouts.index', $qBase + ['status' => 'processing']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800 {{ request('status') === 'processing' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-800 dark:text-sky-400">Processing</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-sky-950 dark:text-sky-50">{{ number_format($stats['processing']) }}</p>
        </a>
        <a href="{{ route('admin.payouts.index', $qBase + ['status' => 'completed']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-emerald-50 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800 {{ request('status') === 'completed' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Completed</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-emerald-950 dark:text-emerald-50">{{ number_format($stats['completed']) }}</p>
        </a>
        <a href="{{ route('admin.payouts.index', $qBase + ['status' => 'failed']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 {{ request('status') === 'failed' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-800 dark:text-rose-400">Failed</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-rose-950 dark:text-rose-50">{{ number_format($stats['failed']) }}</p>
        </a>
        <a href="{{ route('admin.payouts.index', $qBase + ['status' => 'cancelled']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 {{ request('status') === 'cancelled' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Cancelled</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-800 dark:text-slate-100">{{ number_format($stats['cancelled']) }}</p>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.payouts.index') }}" class="bg-white dark:bg-darkpanel rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-2 mb-6 flex flex-col lg:flex-row gap-2 lg:items-center">
        <div class="flex-1 relative min-w-0">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Payout #, reference, store name…">
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <select name="status" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 py-2.5 px-4 min-w-[140px] focus:ring-2 focus:ring-brand-500">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="vendor_id" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 py-2.5 px-4 min-w-[180px] max-w-[220px] focus:ring-2 focus:ring-brand-500">
                <option value="">All merchants</option>
                @foreach($vendorsForFilter as $v)
                    <option value="{{ $v->id }}" {{ (string) request('vendor_id') === (string) $v->id ? 'selected' : '' }}>{{ $v->store_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition">Apply</button>
            @if(request()->hasAny(['search', 'status', 'vendor_id']))
                <a href="{{ route('admin.payouts.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700">Reset</a>
            @endif
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-slate-900 dark:bg-slate-950 rounded-3xl p-6 text-white border border-slate-700 shadow-lg">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Ready to pay out</h3>
                <div class="space-y-4 max-h-[520px] overflow-y-auto pr-1">
                    @forelse($vendorsWithBalance as $v)
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex shrink-0 rounded-xl overflow-hidden border border-white/20 w-12 h-12 bg-white/10">
                                        <img src="{{ $v->display_image }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold truncate">{{ $v->store_name }}</p>
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500">Unpaid balance</p>
                                    </div>
                                </div>
                                <p class="text-lg font-black text-brand-400 tabular-nums shrink-0">৳ {{ number_format($v->unpaid_balance) }}</p>
                            </div>
                            <form action="{{ route('admin.payouts.create') }}" method="POST">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $v->id }}">
                                <div class="flex gap-2">
                                    <select name="payment_method" class="flex-1 min-w-0 bg-white/10 border border-white/10 rounded-xl text-xs font-semibold py-2.5 px-3 text-white">
                                        <option value="bkash">bKash</option>
                                        <option value="bank_transfer">Bank transfer</option>
                                        <option value="nagad">Nagad</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 rounded-xl text-xs font-bold uppercase tracking-wide shrink-0">Create</button>
                                </div>
                                <input type="text" name="reference" placeholder="Memo / ref (optional)" class="w-full mt-2 bg-white/5 border border-white/10 rounded-xl text-xs py-2 px-3 placeholder:text-slate-500">
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center py-8 border border-dashed border-white/10 rounded-2xl">No unpaid balances.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/40">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Payout history</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Net amount is what the vendor receives.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Reference</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Merchant</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Method</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Amount</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($payouts as $p)
                                @php
                                    $vendor = $p->vendor;
                                    $pm = $p->payment_method ?? '';
                                    $pmLabel = match ($pm) {
                                        'bkash' => 'bKash',
                                        'bank_transfer' => 'Bank',
                                        'nagad' => 'Nagad',
                                        default => $pm !== '' ? ucfirst(str_replace('_', ' ', $pm)) : '—',
                                    };
                                    $statusClass = match ($p->status) {
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300 dark:border-emerald-800',
                                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/25 dark:text-amber-300 dark:border-amber-800',
                                        'processing' => 'bg-sky-50 text-sky-800 border-sky-200 dark:bg-sky-900/25 dark:text-sky-300 dark:border-sky-800',
                                        'failed' => 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-900/25 dark:text-rose-300 dark:border-rose-800',
                                        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-600',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-5 py-4 align-top">
                                        <p class="text-sm font-mono font-semibold text-brand-600 dark:text-brand-400">{{ $p->payout_number }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $p->created_at->format('M j, Y') }}</p>
                                        @if($p->transaction_id)
                                            <p class="text-[11px] text-slate-400 mt-1 truncate max-w-[180px]" title="{{ $p->transaction_id }}">{{ $p->transaction_id }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($vendor)
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="flex shrink-0 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 shadow-sm">
                                                    @if($vendor->display_banner)
                                                        <div class="hidden sm:block w-12 h-11 relative">
                                                            <img src="{{ $vendor->display_banner }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                                                        </div>
                                                    @endif
                                                    <div class="w-11 h-11 flex items-center justify-center">
                                                        <img src="{{ $vendor->display_image }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                                    </div>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $vendor->store_name }}</p>
                                                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-xs text-brand-600 dark:text-brand-400 hover:underline">View vendor</a>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-slate-300 hidden sm:table-cell">{{ $pmLabel }}</td>
                                    <td class="px-5 py-4 text-right align-top">
                                        <p class="text-sm font-bold tabular-nums text-slate-900 dark:text-white">৳ {{ number_format($p->net_amount) }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Gross ৳ {{ number_format($p->amount) }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center align-top">
                                        <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide border {{ $statusClass }}">{{ $p->status }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-right align-top">
                                        @if($p->status === 'pending')
                                            <form action="{{ route('admin.payouts.process', $p->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-bold hover:opacity-90 transition">Mark paid</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-sm text-slate-500">No payouts match your filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payouts->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">{{ $payouts->links() }}</div>
                @endif
            </div>
        </div>
    </div>

@endsection
