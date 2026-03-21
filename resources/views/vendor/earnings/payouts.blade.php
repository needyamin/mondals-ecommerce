@extends('layouts.vendor')

@section('title', 'Payout Log Archive')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center group transition duration-300">
        <div class="z-10 text-center md:text-left">
            <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tighter">Bank Withdrawal Protocol</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">View all historical disbursements to your business account.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <div class="px-6 py-2.5 rounded-xl border border-blue-100 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-bold shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Banking Terminal
            </div>
        </div>
    </div>

    <!-- Payout Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <span class="absolute -right-4 -bottom-4 w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full group-hover:scale-150 transition-transform duration-500"></span>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Payout Frequency</p>
            <h4 class="text-2xl font-black font-heading text-slate-900 dark:text-white">Monthly Cycle</h4>
            <p class="text-xs text-slate-500 mt-1 uppercase font-bold tracking-tighter">Settled every 1st & 15th</p>
        </div>
        
        <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <span class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-50 dark:bg-blue-900/10 rounded-full group-hover:scale-150 transition-transform duration-500"></span>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Bank of Record</p>
            <h4 class="text-2xl font-black font-heading text-slate-900 dark:text-white uppercase truncate">{{ auth()->user()->vendor->bank_name ?? 'Not Set' }}</h4>
            <p class="text-xs text-slate-500 mt-1 italic font-medium">Verified Channel</p>
        </div>

        <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <span class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-50 dark:bg-emerald-900/10 rounded-full group-hover:scale-150 transition-transform duration-500"></span>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Disbursed</p>
            <h4 class="text-2xl font-black font-heading text-emerald-500 tracking-tighter">৳{{ number_format($payouts->sum('amount'), 2) }}</h4>
            <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-widest">Lifetime Volume</p>
        </div>

        <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <span class="absolute -right-4 -bottom-4 w-16 h-16 bg-amber-50 dark:bg-amber-900/10 rounded-full group-hover:scale-150 transition-transform duration-500"></span>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Protocol Status</p>
            <div class="flex items-center space-x-2 mt-1">
                 <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                 <h4 class="text-xl font-black font-heading text-slate-900 dark:text-white uppercase tracking-widest">Compliant</h4>
            </div>
        </div>
    </div>

    <!-- Payout Archive -->
    <div class="bg-white dark:bg-darkpanel rounded-[35px] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-8 py-8 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center group cursor-pointer transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/10">
            <h3 class="text-2xl font-black font-heading text-slate-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-3 text-vendor-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m2-12v12M8 4v16"></path></svg>
                Bank Transfer Manifest
            </h3>
            <span class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-vendor-500 transition-all duration-300">Detailed Batch history &nearr;</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.25em] border-b border-slate-50 dark:border-slate-800 mb-4 pb-4 bg-slate-50/50 dark:bg-slate-800/10">
                        <th class="px-8 py-5">Disbursement ID</th>
                        <th class="px-8 py-5">Execution Date</th>
                        <th class="px-8 py-5">Banking Method</th>
                        <th class="px-8 py-5">Payout Quantum</th>
                        <th class="px-8 py-5 text-center">Batch Status</th>
                        <th class="px-8 py-5 text-right">Raw Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300">
                            <td class="px-8 py-6">
                                <span class="text-xs font-black font-mono text-slate-400 uppercase tracking-[0.2em]">#{{ str_pad($payout->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-800 dark:text-white tracking-widest font-heading">{{ $payout->created_at->format('M d, Y') }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Processed at 12:00 PM</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                     <div class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-white mr-3 shadow-md">
                                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                                     </div>
                                     <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 capitalize tracking-tight">{{ $payout->payment_method ?? 'Bank Transfer' }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ auth()->user()->vendor->account_number ? 'XX' . substr(auth()->user()->vendor->account_number, -4) : 'Direct Account' }}</span>
                                     </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-2xl font-black font-heading text-slate-900 dark:text-white tracking-tighter">৳{{ number_format($payout->amount, 2) }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    @php
                                        $statuses = [
                                            'pending'   => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50',
                                            'processing'=> 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50',
                                            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50',
                                            'failed'    => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50',
                                        ];
                                        $colorClass = $statuses[$payout->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                    @endphp
                                    <span class="px-5 py-2 text-[9px] font-black uppercase tracking-[0.25em] rounded-full border {{ $colorClass }} font-heading">
                                        {{ $payout->status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="p-3 bg-white dark:bg-slate-700 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm text-slate-400 hover:text-vendor-600 hover:shadow-lg transition-all transform hover:-translate-y-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-[30%] bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-200 mb-6 drop-shadow-md group hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">Withdrawal History Manifest</h4>
                                    <p class="text-sm text-slate-400 mt-2 font-light max-w-sm mx-auto italic">No payouts have been executed yet. We process all cleared settlements automatically on the protocol dates.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->total() > 15)
            <div class="p-8 bg-slate-50/50 dark:bg-slate-800/10 border-t border-slate-100 dark:border-slate-800">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>

    <!-- Protocol Disclaimer -->
    <div class="mt-12 p-8 bg-slate-900 rounded-[35px] text-white shadow-2xl shadow-indigo-500/10 flex flex-col md:flex-row items-center relative overflow-hidden group">
         <div class="absolute -right-24 -top-24 w-64 h-64 bg-vendor-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
         <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-vendor-400 mb-4 md:mb-0 md:mr-8 flex-shrink-0 border border-slate-700">
             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
         </div>
         <div>
            <h5 class="text-lg font-bold font-heading mb-1 text-vendor-400 uppercase tracking-[0.25em]">Financial Protocol Guard</h5>
            <p class="text-sm text-slate-400 leading-relaxed font-light">All payouts are subject to a 7-day clearing period to account for customer returns and protocol disputes. Funds are automatically disbursed once they cross the ৳5,000 threshold or reach the 30-day settlement window.</p>
         </div>
    </div>
@endsection
