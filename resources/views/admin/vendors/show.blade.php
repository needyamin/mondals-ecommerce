@extends('layouts.admin')

@section('title', 'Merchant Terminal: ' . $vendor->store_name)

@section('content')

    <!-- Premium Header & Control Deck -->
    <div class="mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.vendors.index') }}" class="w-14 h-14 rounded-2xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-500/50 transition-all shadow-xl shadow-slate-200/50 dark:shadow-none group">
                <svg class="w-7 h-7 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-600 to-indigo-700 flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-brand-500/30">
                    {{ substr($vendor->store_name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white font-heading tracking-tight">{{ $vendor->store_name }}</h2>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border 
                            {{ $vendor->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : '' }}
                            {{ $vendor->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50 dark:text-amber-400' : '' }}
                            {{ $vendor->status === 'suspended' ? 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400' : '' }}
                            {{ $vendor->status === 'rejected' ? 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400' : '' }}
                        ">
                            {{ $vendor->status }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                        <span class="flex items-center font-medium">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $vendor->email }}
                        </span>
                        <span class="opacity-30">|</span>
                        <span class="font-bold text-slate-900 dark:text-slate-200">ID: #{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full xl:w-auto">
            @if($vendor->status === 'pending')
                <div class="flex gap-3 w-full sm:w-auto">
                    <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl font-bold shadow-xl shadow-emerald-500/30 transition-all hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Approve merchant
                        </button>
                    </form>
                    <button type="button" @click="$dispatch('open-modal', 'reject-modal')" class="flex-1 sm:flex-none bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 px-8 py-4 rounded-2xl font-bold border border-rose-200 dark:border-rose-800 hover:bg-rose-100 transition-all">
                        Reject Application
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Alert System -->
    @if(session('success'))
        <div class="mb-10 p-5 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex items-center shadow-lg shadow-emerald-500/5">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Sidebar: Intelligence & Policies -->
        <div class="lg:col-span-4 space-y-8">
            
            <!-- Information Cluster -->
            <div class="bg-white dark:bg-darkpanel p-8 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-brand-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Partner Portfolio
                </h3>
                
                <div class="space-y-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Merchant Owner</span>
                        <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $vendor->user->name ?? 'System Bind' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Direct Contact</span>
                        <code class="text-sm font-mono font-black text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-900/20 px-2 py-1 rounded-lg w-fit">{{ $vendor->phone ?? '+880 N/A' }}</code>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Registered Address</span>
                        <span class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-semibold">{{ $vendor->address ?? 'No physical address.' }}</span>
                    </div>
                </div>

                @if($vendor->status === 'rejected' && $vendor->rejection_reason)
                    <div class="mt-8 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                        <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-2 block">Rejection Note</span>
                        <p class="text-xs italic text-slate-500">{{ $vendor->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <!-- Policy & Commission Deck -->
            <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-2xl shadow-slate-900/20 border border-white/5 relative overflow-hidden group">
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8">Platform Economics</h3>
                
                <form action="{{ route('admin.vendors.commission', $vendor->id) }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Revenue Share (%)</label>
                        <div class="relative">
                            <input type="number" step="0.1" name="commission_rate" value="{{ $vendor->commission_rate }}" class="w-full bg-slate-800 border-none rounded-2xl px-6 py-5 text-white font-mono text-3xl font-black focus:ring-2 focus:ring-brand-500 shadow-inner">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-500 font-black text-xl">%</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-black py-4 rounded-2xl transition hover:scale-[1.02] shadow-xl shadow-white/5 active:scale-95 text-sm uppercase tracking-widest">
                        Apply Adjustments
                    </button>
                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                        <p class="text-[10px] text-center text-slate-400 font-bold uppercase tracking-wider leading-relaxed">Changes take effect immediately on all new transactions.</p>
                    </div>
                </form>
            </div>
            
            <!-- Access Terminal -->
            <div class="bg-rose-50/50 dark:bg-rose-900/10 p-8 rounded-[2rem] border border-rose-100 dark:border-rose-900/30">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-rose-500 mb-6 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.947 11.947 0 012.06 7.02c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622-1.042-.133-2.052-.382-3.016-.382H11V1.944z" clip-rule="evenodd"></path></svg>
                    Access Terminal
                </h3>
                @if($vendor->status !== 'suspended')
                    <form action="{{ route('admin.vendors.suspend', $vendor->id) }}" method="POST" onsubmit="return confirm('Strictly halt this vendor?');">
                        @csrf
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-rose-500/30 active:scale-95 text-sm uppercase tracking-widest">
                            Suspend merchant
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.vendors.unsuspend', $vendor->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-emerald-500/30 active:scale-95 text-sm uppercase tracking-widest">
                            Restore access
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Inventory & Lifecycle Analytics -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Performance HUD -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="group bg-white dark:bg-darkpanel p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Inventory Depth</span>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tighter">{{ $vendor->products_count }}</span>
                        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs uppercase">SKUs</div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="group bg-white dark:bg-darkpanel p-8 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">Total Sales</span>
                    <div class="flex items-end justify-between">
                        <span class="text-4xl font-black text-emerald-600 leading-none tracking-tighter">{{ $vendor->orders_count }}</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-xs uppercase">Orders</div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="group bg-slate-900 p-8 rounded-[2rem] border border-white/5 shadow-2xl hover:shadow-vendor-600/10 transition-all duration-500 hover:-translate-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4 text-white/50">Total Revenue Volume</span>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-black text-white leading-none tracking-tighter">৳ {{ number_format($vendor->totalEarnings(), 2) }}</span>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white font-bold text-xs uppercase border border-white/10">BDT</div>
                    </div>
                </div>
            </div>

            <!-- Product Registry -->
            <div class="bg-white dark:bg-darkpanel rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-10 py-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/30 dark:bg-slate-800/5">
                    <div>
                        <h3 class="font-black font-heading text-slate-900 dark:text-white uppercase tracking-[0.1em] text-sm">Inventory Ledger</h3>
                        <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Most Recent Catalog Additions</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}?vendor_id={{ $vendor->id }}" class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-all">Export Report Mapping</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                                <th class="px-10 py-5">Product Identity & SKU</th>
                                <th class="px-10 py-5 text-center">Stock Node</th>
                                <th class="px-10 py-5 text-right">Unit Liquidation Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($vendor->products as $product)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition-colors group">
                                <td class="px-10 py-6">
                                    <div class="flex items-center">
                                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden flex-shrink-0 mr-5 border border-slate-200 dark:border-slate-700 p-0.5 group-hover:scale-110 transition-transform">
                                            @if($product->primary_image)
                                                <img src="{{ asset('storage/' . $product->primary_image) }}" class="w-full h-full object-cover rounded-[0.6rem]">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-base font-bold text-slate-900 dark:text-white mb-0.5">{{ $product->name }}</h4>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-mono font-black text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700 tracking-tighter uppercase">{{ $product->sku }}</span>
                                                @if($product->is_featured)
                                                    <span class="text-[8px] font-black uppercase bg-amber-100 text-amber-600 px-1.5 py-0.5 rounded tracking-widest">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-center">
                                    <div class="inline-flex flex-col">
                                        <span class="text-lg font-black {{ $product->quantity < 5 ? 'text-rose-500' : 'text-slate-900 dark:text-slate-200' }}">{{ $product->quantity }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Available</span>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div class="inline-flex flex-col items-end">
                                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">TK {{ number_format($product->price, 2) }}</span>
                                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">+ Tax Included</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center text-slate-200 mb-6">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">No Virtual Inventory Records</h4>
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

    <!-- Orchestration Modal: Rejection -->
    <x-modal name="reject-modal" focusable>
        <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" class="p-10">
            @csrf
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white font-heading tracking-tight">Decline Application</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Inform the merchant why their application was not accepted.</p>
                </div>
            </div>
            
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Reason for Rejection</label>
            <textarea name="reason" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-3xl px-6 py-5 text-sm focus:ring-2 focus:ring-brand-500 shadow-inner mb-8 text-slate-900 dark:text-white" placeholder="e.g. Documentation mismatch, restricted category, or insufficient information..."></textarea>
            
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'reject-modal')" class="px-8 py-4 rounded-2xl font-black text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all text-xs uppercase tracking-widest">Cancel</button>
                <button type="submit" class="px-8 py-4 rounded-2xl bg-rose-600 text-white font-black shadow-xl shadow-rose-500/40 hover:scale-105 transition-all text-xs uppercase tracking-widest">Confirm Rejection</button>
            </div>
        </form>
    </x-modal>

@endsection
