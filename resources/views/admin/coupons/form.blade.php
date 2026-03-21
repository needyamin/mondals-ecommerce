@extends('layouts.admin')

@section('title', isset($item) ? 'Modify Strategic Coupon' : 'Campaign Allocation')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <!-- Header -->
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight underline decoration-emerald-500 decoration-4 underline-offset-8">
                Campaign {{ isset($item) ? 'Modification' : 'Allocation' }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-6 font-medium uppercase tracking-[0.2em] text-[10px]">
                {{ isset($item) ? 'Overriding established promotional data.' : 'Initialize new discount node in ecosystem.' }}
            </p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-emerald-600 shadow-xl transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-10 p-6 rounded-3xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/30">
            <ul class="list-disc list-inside space-y-2 text-sm font-black uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('admin.coupons.update', $item->id) : route('admin.coupons.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        @csrf
        @if(isset($item)) @method('PUT') @endif

        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white dark:bg-darkpanel p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-3 animate-pulse"></span>
                    Campaign Parameters
                </h3>

                <div class="space-y-8 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Promotional Key (CODE)</label>
                            <input type="text" name="code" value="{{ old('code', $item->code ?? '') }}" placeholder="COUPON2026" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner transition-all text-slate-900 dark:text-white font-mono tracking-widest uppercase font-bold" required>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Internal Name</label>
                            <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" placeholder="Summer Flash Sale" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Strategic Type</label>
                            <select name="type" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner font-bold text-slate-900 dark:text-white appearance-none">
                                <option value="fixed" {{ old('type', $item->type ?? '') === 'fixed' ? 'selected' : '' }}>Flat Value (৳)</option>
                                <option value="percentage" {{ old('type', $item->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="free_shipping" {{ old('type', $item->type ?? '') === 'free_shipping' ? 'selected' : '' }}>Free Logisitics</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payload Value</label>
                            <input type="number" step="0.01" name="value" value="{{ old('value', $item->value ?? '0') }}" placeholder="10.00" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Minimum Order Required</label>
                            <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $item->min_order_amount ?? '0') }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner font-bold text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Maximum Discount Cap</label>
                            <input type="number" step="0.01" name="max_discount_amount" value="{{ old('max_discount_amount', $item->max_discount_amount ?? '') }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-emerald-500 shadow-inner font-bold text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-slate-900/30 border border-white/5">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fulfillment Status
                </h3>

                <div class="space-y-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono">Activation Protocol</label>
                        <label class="relative flex items-center p-5 bg-slate-800 rounded-3xl cursor-pointer hover:bg-slate-700 transition group border-2 border-transparent has-[:checked]:border-emerald-500 shadow-sm">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-slate-200 dark:bg-slate-800 text-emerald-500 focus:ring-emerald-500 shadow-inner">
                            <span class="ml-4 flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.1em] text-white">Live State</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Accept transactions with this key.</span>
                            </span>
                        </label>
                    </div>

                    <div class="space-y-4 pt-8 border-t border-white/5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono">Merchant Owner</label>
                        <select name="vendor_id" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-4 text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner italic">
                            @foreach($vendors as $id => $label)
                                <option value="{{ $id }}" {{ old('vendor_id', $item->vendor_id ?? '') == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-[8px] text-slate-500 italic mt-2 uppercase font-black">Null owner applies globally to platform catalog.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 pt-8 border-t border-white/5">
                         <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono">Start Protocol</label>
                            <input type="date" name="starts_at" value="{{ old('starts_at', isset($item->starts_at) ? $item->starts_at->format('Y-m-d') : '') }}" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-4 text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono">Termination Protocol</label>
                            <input type="date" name="expires_at" value="{{ old('expires_at', isset($item->expires_at) ? $item->expires_at->format('Y-m-d') : '') }}" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-4 text-white font-bold text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner">
                        </div>
                    </div>

                    <div class="pt-10">
                        <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-[1.5rem] font-black shadow-2xl shadow-emerald-500/30 transition-all hover:scale-[1.03] active:scale-95 text-xs uppercase tracking-[0.3em]">
                            {{ isset($item) ? 'Commit Modifications' : 'Initialize Campaign' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
