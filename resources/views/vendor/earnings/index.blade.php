@extends('layouts.vendor')

@section('title', 'Financial Intelligence')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center group transition duration-300">
        <div class="z-10 text-center md:text-left">
            <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tighter">Income Reports & Yield</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">Detailed breakdown of your digital settlement ledger.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <div class="px-6 py-2.5 rounded-xl border border-vendor-100 dark:border-vendor-900 bg-vendor-50 dark:bg-vendor-900/40 text-vendor-600 dark:text-vendor-400 font-bold shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verified Settlement
            </div>
        </div>
    </div>

    <!-- Financial Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        
        <div class="bg-indigo-600 rounded-[35px] p-8 text-white shadow-2xl shadow-indigo-600/30 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-indigo-200 mb-2 font-heading">Accumulated Yield</p>
            <div class="flex items-baseline mb-1">
                 <span class="text-lg font-bold text-indigo-300 mr-1.5">৳</span>
                 <h3 class="text-5xl font-black font-heading tracking-tighter">{{ number_format($summary['total'], 2) }}</h3>
            </div>
            <p class="text-xs font-medium text-indigo-100 italic">Total revenue since inception</p>
        </div>

        <div class="bg-white dark:bg-darkpanel rounded-[35px] p-8 border border-emerald-100 dark:border-emerald-900/30 shadow-xl shadow-emerald-500/5 flex flex-col justify-between group">
            <div>
                 <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-500 mb-2 font-heading">Successfully Disbursed</p>
                 <div class="flex items-baseline mb-1">
                      <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mr-1.5">৳</span>
                      <h3 class="text-5xl font-black font-heading tracking-tighter text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors">{{ number_format($summary['paid'], 2) }}</h3>
                 </div>
            </div>
            <div class="mt-6 flex items-center pt-6 border-t border-emerald-50 dark:border-emerald-900/20">
                 <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mr-4">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 </div>
                 <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Cleared and Paid</p>
            </div>
        </div>

        <div class="bg-white dark:bg-darkpanel rounded-[35px] p-8 border border-amber-100 dark:border-amber-900/30 shadow-xl shadow-amber-500/5 flex flex-col justify-between group">
            <div>
                 <p class="text-[10px] font-black uppercase tracking-[0.25em] text-amber-500 mb-2 font-heading">Awaiting Disbursement</p>
                 <div class="flex items-baseline mb-1">
                      <span class="text-lg font-bold text-amber-600 dark:text-amber-400 mr-1.5">৳</span>
                      <h3 class="text-5xl font-black font-heading tracking-tighter text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors">{{ number_format($summary['unpaid'], 2) }}</h3>
                 </div>
            </div>
            <div class="mt-6 flex items-center justify-between pt-6 border-t border-amber-50 dark:border-amber-900/20">
                 <div class="flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-widest">Pending Settlement</p>
                 </div>
                 @if($summary['unpaid'] > 0)
                    <a href="#" class="btn-vendor text-[10px] font-bold uppercase tracking-widest bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl transition-all shadow-lg shadow-amber-500/30">Request Payout</a>
                 @endif
            </div>
        </div>

    </div>

    <!-- Settlement History Table -->
    <div class="bg-white dark:bg-darkpanel rounded-[35px] shadow-sm border border-slate-100 dark:border-slate-800 p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 space-y-4 md:space-y-0">
            <h3 class="text-2xl font-black font-heading text-slate-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-3 text-vendor-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Detailed Settlement Ledger
            </h3>
            
            <div class="flex items-center bg-slate-50 dark:bg-slate-800 p-1 rounded-2xl border border-slate-100 dark:border-slate-700">
                 <a href="{{ route('vendor.earnings.index') }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all {{ !request('is_paid') ? 'bg-white dark:bg-slate-700 shadow-sm text-vendor-600 dark:text-white' : 'text-slate-400 hover:text-slate-600' }}">All Settlements</a>
                 <a href="{{ route('vendor.earnings.index', ['is_paid' => 'false']) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('is_paid') === 'false' ? 'bg-white dark:bg-slate-700 shadow-sm text-vendor-600 dark:text-white' : 'text-slate-400 hover:text-slate-600' }}">Awaiting Withdrawal</a>
                 <a href="{{ route('vendor.earnings.index', ['is_paid' => 'true']) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all {{ request('is_paid') === 'true' ? 'bg-white dark:bg-slate-700 shadow-sm text-vendor-600 dark:text-white' : 'text-slate-400 hover:text-slate-600' }}">Disbursed Funds</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-slate-800 mb-4 pb-4">
                        <th class="px-4 py-4">Financial Date</th>
                        <th class="px-4 py-4">Origin Hub (Order)</th>
                        <th class="px-4 py-4">Manifest Product</th>
                        <th class="px-4 py-4">Total Amount</th>
                        <th class="px-4 py-4">Protocol Fee</th>
                        <th class="px-4 py-4">Final Net Yield</th>
                        <th class="px-4 py-4 text-center">Settlement Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($earnings as $earning)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors group">
                            <td class="px-4 py-6">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $earning->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-sm font-black font-mono text-slate-900 dark:text-white tracking-widest">{{ $earning->order->order_number ?? 'ORD-00000' }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-tight mb-0.5">{{ $earning->orderItem->product_name ?? 'Digital Item' }}</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $earning->orderItem->quantity ?? 1 }} Unit Dispatched</span>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-sm font-bold text-slate-400">৳{{ number_format($earning->total_amount, 2) }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-sm font-bold text-rose-400">৳{{ number_format($earning->commission_amount, 2) }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-lg font-black font-heading text-slate-900 dark:text-white tracking-tighter">৳{{ number_format($earning->vendor_earning, 2) }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <div class="flex justify-center">
                                    @if($earning->is_paid)
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-[10px] font-black uppercase tracking-[0.1em] text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 shadow-sm animate-pulse"></div>
                                            Successfully Disbursed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-[10px] font-black uppercase tracking-[0.1em] text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/50">
                                            <div class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 shadow-sm"></div>
                                            Awaiting Release
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-[30%] flex items-center justify-center text-slate-200 mb-6 drop-shadow-sm group hover:scale-110 transition-transform">
                                        <svg class="w-10 h-10 group-hover:text-vendor-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m2-12v12M8 4v16"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">Your Settlement Ledger is Blank</h4>
                                    <p class="text-sm text-slate-400 mt-2 font-light max-w-xs mx-auto">When your store orders reach the payout threshold, the breakdown will manifest here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($earnings->hasPages())
            <div class="mt-10 p-6 bg-slate-50/50 dark:bg-slate-800/20 rounded-2xl border border-slate-100 dark:border-slate-800">
                {{ $earnings->links() }}
            </div>
        @endif
    </div>
@endsection
