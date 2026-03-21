@extends('layouts.admin')

@section('title', 'Financial Desk: Merchant Payouts')

@section('content')

    <!-- Financial Orchestration Header -->
    <div class="mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-10 group">
        <div class="relative">
            <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight underline decoration-indigo-500 decoration-4 underline-offset-8">Financial Desk</h2>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-6 ml-1 group-hover:text-indigo-500 transition-colors">Vendor Profit Distribution Lifecycle</p>
        </div>
        <div class="flex items-center gap-4 w-full xl:w-auto">
            <div class="px-6 py-4 bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none text-brand-600 font-black text-xs uppercase tracking-widest flex flex-col">
                <span class="text-[8px] text-slate-400 mb-1">Estimated Unpaid Liability</span>
                <span class="text-xl tracking-tighter">৳ {{ number_format($vendorsWithBalance->sum('unpaid_balance')) }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-10 p-5 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex items-center shadow-lg shadow-emerald-500/5">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-black text-xs uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Action: Eligible Disbursments -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-slate-900/40 relative overflow-hidden group border border-white/5">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 mr-3 animate-pulse"></span>
                    Pending Disbursment Map
                </h3>
                
                <div class="space-y-6 relative z-10">
                    @forelse($vendorsWithBalance as $v)
                        <div class="p-6 bg-white/5 rounded-3xl border border-white/10 hover:border-indigo-500 transition-all group/card">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white font-black text-lg border border-white/10 italic font-mono">
                                        {{ substr($v->store_name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-base font-black tracking-tight">{{ $v->store_name }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500">Unpaid Balance</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-black text-brand-400 tracking-tighter">৳ {{ number_format($v->unpaid_balance) }}</span>
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.payouts.create') }}" method="POST">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $v->id }}">
                                <div class="flex gap-2">
                                    <select name="payment_method" class="flex-1 bg-white/10 border-none rounded-xl text-[10px] font-black uppercase tracking-widest py-3 px-4 focus:ring-1 focus:ring-indigo-500 shadow-inner">
                                        <option value="bkash">bKash (Primary)</option>
                                        <option value="bank_transfer" class="text-slate-900">Bank Transfer</option>
                                        <option value="nagad">Nagad</option>
                                    </select>
                                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 active:scale-95 transition-all">
                                        Initiate
                                    </button>
                                </div>
                                <input type="text" name="reference" placeholder="Internal Memo (Optional)" class="w-full mt-3 bg-white/5 border-none rounded-xl text-[10px] font-bold py-2.5 px-4 focus:ring-1 focus:ring-indigo-500/50 opacity-40 hover:opacity-100 transition-opacity">
                            </form>
                        </div>
                    @empty
                        <div class="py-10 text-center border-2 border-dashed border-white/5 rounded-[2rem]">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest italic leading-relaxed">System Audit Complete.<br>Liability Matrix Equalized.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- History: Disbursment Terminal -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-darkpanel rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden relative group h-full">
                <div class="p-10 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/20 dark:bg-slate-800/10">
                    <div>
                        <h3 class="font-black font-heading text-slate-900 dark:text-white uppercase tracking-[0.2em] text-xs">Payout Audit Track</h3>
                        <p class="text-[9px] text-slate-400 font-black mt-2 uppercase tracking-widest italic decoration-brand-500 decoration-2 underline underline-offset-4">Transaction History Registry</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-800/10">
                                <th class="px-8 py-5">Reference & Date</th>
                                <th class="px-8 py-5">Merchant Store</th>
                                <th class="px-8 py-5 text-center">Value Payload</th>
                                <th class="px-8 py-5 text-center">Status</th>
                                <th class="px-8 py-5 text-right">Audit Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-[10px] font-black uppercase tracking-widest">
                            @forelse($payouts as $p)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-brand-600/5 transition-all group/row">
                                <td class="px-8 py-8">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-black text-brand-600 dark:text-brand-400 font-mono tracking-tighter">{{ $p->payout_number }}</span>
                                        <span class="text-[8px] text-slate-500 font-bold uppercase">{{ $p->created_at->format('d M, Y') }}</span>
                                        <span class="text-[8px] text-indigo-500 font-mono italic">{{ $p->transaction_id ?? 'No Internal Ref' }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-black italic">
                                            {{ substr($p->vendor->store_name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-black text-slate-900 dark:text-white tracking-tighter">{{ $p->vendor->store_name }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-slate-900 dark:text-white tracking-tighter">৳ {{ number_format($p->net_amount) }}</span>
                                        <span class="text-[8px] text-slate-400 font-black uppercase line-through opacity-40">৳ {{ number_format($p->amount) }} Gross</span>
                                    </div>
                                </td>

                                <td class="px-8 py-8 text-center uppercase">
                                    <span class="px-4 py-1.5 rounded-lg border shadow-sm inline-block
                                        {{ $p->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50' : 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50' }}
                                    ">
                                        {{ $p->status }}
                                    </span>
                                </td>

                                <td class="px-8 py-8 text-right">
                                    @if($p->status === 'pending')
                                        <form action="{{ route('admin.payouts.process', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-5 py-3 bg-slate-900 text-white dark:bg-white dark:text-slate-900 rounded-xl hover:scale-105 transition-all shadow-xl active:scale-95 text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                                Finalize Protocal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[8px] text-slate-400 font-black italic whitespace-nowrap">Disbursement Archived</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 mb-6 animate-bounce">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No Transactional Fragments</h4>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payouts->hasPages())
                <div class="p-8 bg-slate-50/20 border-t border-slate-100 dark:border-slate-800">
                    {{ $payouts->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>

@endsection
