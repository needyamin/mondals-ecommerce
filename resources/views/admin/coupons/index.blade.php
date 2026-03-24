@extends('layouts.admin')

@section('title', 'Manage Coupons')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Coupons</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Generate discount codes, limit redemptions, and track promo success.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.coupons.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Coupon
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 pl-12 pr-4 py-2.5 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 shadow-sm" placeholder="Search coupon code...">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Promotion Code</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Discount Factor</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Redemptions</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($items as $coupon)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        
                        <td class="px-6 py-4">
                            <span class="text-lg font-extrabold text-brand-600 dark:text-brand-400 font-mono tracking-widest">{{ $coupon->code }}</span>
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            @if($coupon->type === 'percentage')
                                {{ rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') }}%
                            @elseif($coupon->type === 'free_shipping')
                                Free shipping
                            @else
                                ৳{{ number_format($coupon->value, 2) }}
                            @endif
                            <span class="text-xs font-medium text-slate-500 block">Off total</span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $coupon->times_used }} / {{ $coupon->usage_limit ?? '∞' }}</span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $valid = true;
                                if ($coupon->expires_at && $coupon->expires_at->isPast()) $valid = false;
                                if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit) $valid = false;
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border 
                                {{ $valid && $coupon->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400' }}
                            ">
                                {{ $valid && $coupon->is_active ? 'Active' : ($coupon->is_active ? 'Expired/Used' : 'Disabled') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this promotion?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-rose-50 text-xs font-bold text-rose-600 dark:bg-rose-900/20 dark:text-rose-400 transition-colors">Del</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            No coupons actively generated.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($items) && $items->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            {{ $items->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endsection
