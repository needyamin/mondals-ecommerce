@extends('layouts.admin')

@section('title', 'Entity Profile: ' . $user->name)

@section('content')

    <!-- Profile Header & Navigation -->
    <div class="mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
        <div class="flex items-center gap-8 group">
            <a href="{{ route('admin.users.index') }}" class="w-14 h-14 rounded-2xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 shadow-xl group-hover:-translate-x-1 transition-all">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-brand-600 to-indigo-700 flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-brand-500/40 border-4 border-white dark:border-slate-800">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight leading-none mb-3 italic">{{ $user->name }}</h2>
                    <div class="flex flex-wrap items-center gap-4 text-xs font-black uppercase tracking-[0.1em] text-slate-500">
                        <span class="flex items-center bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">
                            <svg class="w-4 h-4 mr-2 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $user->email }}
                        </span>
                        <span class="opacity-20 text-slate-400">|</span>
                        <span class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-800/50">
                            Registered {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-4 rounded-2xl shadow-2xl shadow-indigo-500/30 transition-all hover:scale-105 active:scale-95 text-[10px] uppercase tracking-[0.2em] flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Modify Global Profile
            </a>
            <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                @if($user->status !== 'banned')
                    <input type="hidden" name="status" value="banned">
                    <button type="submit" class="bg-rose-100 text-rose-700 hover:bg-rose-200 font-bold px-6 py-4 rounded-2xl transition text-[10px] uppercase tracking-widest shadow-lg shadow-rose-500/10" onclick="return confirm('Immediately block customer access?');">
                        Ban Account
                    </button>
                @else
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-bold px-6 py-4 rounded-2xl transition text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-500/10">
                        Restore Access
                    </button>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-10 p-5 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex items-center shadow-lg shadow-emerald-500/5">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Dashboard Cluster -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white dark:bg-darkpanel p-10 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-500/5 rounded-full blur-3xl"></div>
                
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Biological ID</h3>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border 
                        {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400' }}
                    ">
                        {{ $user->status }}
                    </span>
                </div>
                
                <div class="space-y-8 text-sm relative z-10 font-bold">
                    <div class="flex flex-col gap-1.5">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest">Digital Contact Protocol</span>
                        <span class="text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800 px-1 py-1 rounded-lg w-fit">{{ $user->phone ?? 'NO MOBILE ATTACHED' }}</span>
                    </div>
                    <div class="flex flex-col gap-1.5 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest">Cart Lifecycle Depth</span>
                        <span class="text-3xl font-black text-brand-600 dark:text-brand-400">{{ $user->orders->count() }} <span class="text-xs font-black uppercase ml-1 opacity-50">Transactions</span></span>
                    </div>
                    <div class="flex flex-col gap-1.5 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest">Last Network Interaction</span>
                        <span class="text-slate-900 dark:text-white">{{ $user->updated_at->format('d M, Y \a\t H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Geographic Mapping (Addresses) -->
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-slate-900/30 border border-white/5 relative group">
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-brand-500/10 rounded-full blur-3xl"></div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Fulfillment Coordinates
                </h3>
                
                <ul class="space-y-6 relative z-10">
                    @forelse($user->addresses as $addr)
                        <li class="p-5 bg-white/5 rounded-2xl border border-white/10 text-xs hover:border-brand-500 transition-colors">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-black uppercase tracking-widest text-slate-300">{{ $addr->title ?? 'Base Address' }}</span>
                                @if($addr->is_default)
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em] bg-brand-600 text-white px-2 py-0.5 rounded-md">Primary</span>
                                @endif
                            </div>
                            <p class="text-slate-400 leading-relaxed font-bold">{{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }} {{ $addr->zip_code }}</p>
                            <p class="text-[9px] text-brand-400 font-mono mt-2 uppercase">{{ $addr->country }}</p>
                        </li>
                    @empty
                        <li class="text-[10px] text-slate-500 font-black uppercase tracking-widest border-2 border-dashed border-white/5 p-8 rounded-2xl text-center">No structural mappings registered.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        <!-- Financial Ledger (Order History) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white dark:bg-darkpanel rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-10 py-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/20 dark:bg-slate-800/5">
                    <div>
                        <h3 class="font-black font-heading text-slate-900 dark:text-white uppercase tracking-[0.2em] text-sm">Transactional Ledger</h3>
                        <p class="text-[10px] text-slate-400 font-black mt-2 uppercase tracking-widest italic decoration-brand-500 decoration-2 underline underline-offset-4">Recent Network Exchanges</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}?user_id={{ $user->id }}" class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 dark:text-slate-400 hover:bg-slate-900 hover:text-white dark:hover:bg-brand-600 transition-all shadow-sm">Audit Full History &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-800/10">
                                <th class="px-10 py-5">Node Reference</th>
                                <th class="px-10 py-5 text-center">Lifecycle Stage</th>
                                <th class="px-10 py-5 text-right">Value Exchange</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($user->orders as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-all cursor-pointer group" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-6">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-500 font-black text-lg border border-slate-200 dark:border-slate-700 group-hover:bg-brand-600 group-hover:text-white group-hover:border-brand-500 transition-all">
                                            #
                                        </div>
                                        <div>
                                            <h4 class="text-base font-black text-brand-600 dark:text-brand-400 font-mono tracking-tighter">{{ $order->order_number }}</h4>
                                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <span class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border 
                                        {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-800/50' : 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800 dark:border-slate-700' }}
                                    ">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">৳{{ number_format($order->total, 2) }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">{{ $order->payment_status }} verified</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800/30 flex items-center justify-center text-slate-200 mb-8">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        </div>
                                        <h4 class="text-xs font-black text-slate-300 dark:text-slate-600 uppercase tracking-[0.3em]">No Active Network Transactions</h4>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
