@extends('layouts.vendor')

@section('title', 'Product reviews')

@section('content')
@php
    $c = $counts ?? ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'all' => 0];
    $cur = $status ?? 'pending';
    $searchQ = request('search');
@endphp

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-200 text-sm font-medium flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </span>
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Product reviews</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1.5 text-sm max-w-2xl">Moderate reviews for <span class="font-semibold text-slate-700 dark:text-slate-300">your products only</span>. Shoppers only see reviews you have approved.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-6">
        @foreach([
            'pending' => ['label' => 'Pending', 'sub' => 'Needs your OK', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'approved' => ['label' => 'Approved', 'sub' => 'Visible to buyers', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            'rejected' => ['label' => 'Rejected', 'sub' => 'Not shown', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636'],
            'all' => ['label' => 'All', 'sub' => 'Every review', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
        ] as $key => $meta)
            @php $active = $cur === $key; @endphp
            <a href="{{ route('vendor.reviews.index', array_filter(['status' => $key, 'search' => $searchQ])) }}"
               class="group flex items-center gap-2.5 rounded-xl border px-3 py-2.5 sm:px-3.5 sm:py-3 transition-all duration-200 min-w-0
               {{ $active
                    ? 'border-vendor-500/50 bg-vendor-50/80 dark:bg-vendor-900/15 dark:border-vendor-500/40 shadow-sm ring-1 ring-vendor-500/25'
                    : 'border-slate-200/90 dark:border-slate-700 bg-white dark:bg-darkpanel hover:border-vendor-200 dark:hover:border-slate-600 hover:bg-slate-50/80 dark:hover:bg-slate-800/40' }}">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $active ? 'bg-vendor-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 group-hover:bg-vendor-50 dark:group-hover:bg-slate-700 group-hover:text-vendor-600 dark:group-hover:text-vendor-400' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/></svg>
                </span>
                <div class="min-w-0 flex-1 text-left">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <p class="text-lg font-extrabold text-slate-900 dark:text-white font-heading tabular-nums leading-none">{{ number_format($c[$key] ?? 0) }}</p>
                        @if($active)
                            <span class="text-[9px] font-bold uppercase tracking-wide text-vendor-600 dark:text-vendor-400 bg-vendor-100/80 dark:bg-vendor-900/40 px-1.5 py-0.5 rounded">Active</span>
                        @endif
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 dark:text-slate-200 mt-1 leading-tight">{{ $meta['label'] }}</p>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 mt-0.5 leading-tight truncate" title="{{ $meta['sub'] }}">{{ $meta['sub'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-r from-slate-50/90 to-white dark:from-slate-800/40 dark:to-darkpanel">
            <div class="flex flex-nowrap items-center justify-between gap-3 min-w-0 overflow-x-auto pb-0.5">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 shrink whitespace-nowrap">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white font-heading shrink-0">Your reviews</h3>
                    <span class="text-slate-300 dark:text-slate-600 shrink-0 hidden sm:inline" aria-hidden="true">|</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 shrink-0">
                        Showing <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }}</span>
                        of <span class="font-semibold text-slate-700 dark:text-slate-300">{{ number_format($reviews->total()) }}</span>
                        @if($searchQ)
                            <span class="text-vendor-600 dark:text-vendor-400"> · filtered</span>
                        @endif
                    </p>
                </div>
                <form method="GET" action="{{ route('vendor.reviews.index') }}" class="flex items-center gap-2 shrink-0">
                    <input type="hidden" name="status" value="{{ $cur }}">
                    <div class="flex w-[13rem] sm:w-56 min-w-0 items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-2.5 shadow-inner focus-within:ring-2 focus-within:ring-vendor-500 focus-within:border-transparent">
                        <span class="shrink-0 text-slate-400" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search" name="search" value="{{ $searchQ }}" placeholder="Product, comment…" autocomplete="off" aria-label="Search reviews by product, title, or comment"
                               class="min-w-0 flex-1 border-0 bg-transparent py-2 pr-0 text-sm text-slate-900 dark:text-white placeholder:text-slate-400/80 dark:placeholder:text-slate-500 focus:outline-none focus:ring-0 [&::-webkit-search-cancel-button]:appearance-none">
                    </div>
                    <button type="submit" class="shrink-0 px-3 py-2 rounded-xl bg-vendor-600 hover:bg-vendor-700 text-white text-xs font-bold shadow-md shadow-vendor-500/25 transition">Search</button>
                    @if($searchQ)
                        <a href="{{ route('vendor.reviews.index', ['status' => $cur]) }}" class="shrink-0 px-2.5 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/20">
                        <th class="px-6 py-4 pl-6 sm:pl-8">Product</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4 text-center w-24">Rating</th>
                        <th class="px-6 py-4 min-w-[200px]">Feedback</th>
                        <th class="px-6 py-4 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-right pr-6 sm:pr-8 whitespace-nowrap w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors align-top">
                            <td class="px-6 py-5 pl-6 sm:pl-8">
                                <a href="{{ route('product.detail', $review->product->slug) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 font-bold text-vendor-600 dark:text-vendor-400 hover:text-vendor-700 dark:hover:text-vendor-300 text-sm group/link">
                                    <span class="group-hover/link:underline">{{ Str::limit($review->product->name, 44) }}</span>
                                    <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <p class="text-[10px] text-slate-400 font-mono mt-1">#{{ $review->product->id }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $review->user->name ?? '—' }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $review->created_at->format('M j, Y · g:i A') }}</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="inline-flex items-center gap-0.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/25 border border-amber-200/80 dark:border-amber-800/50">
                                    @for($i = 0; $i < min(5, (int) $review->rating); $i++)
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-md">
                                @if(filled($review->title))
                                    <p class="font-semibold text-slate-800 dark:text-slate-200 text-xs mb-1">{{ Str::limit($review->title, 80) }}</p>
                                @endif
                                {{ Str::limit($review->comment, 160) }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold border uppercase tracking-wide
                                    @if($review->status === 'approved') bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300 dark:border-emerald-800/60
                                    @elseif($review->status === 'pending') bg-amber-50 text-amber-900 border-amber-200 dark:bg-amber-900/25 dark:text-amber-200 dark:border-amber-800/60
                                    @else bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-900/25 dark:text-rose-300 dark:border-rose-800/60 @endif">
                                    {{ $review->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right pr-6 sm:pr-8 whitespace-nowrap">
                                <div class="inline-flex flex-nowrap justify-end items-center gap-1">
                                    @if($review->status !== 'approved')
                                        <form action="{{ route('vendor.reviews.approve', $review) }}" method="POST" class="inline shrink-0">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold leading-none shadow-sm transition">Approve</button>
                                        </form>
                                    @endif
                                    @if($review->status !== 'rejected')
                                        <form action="{{ route('vendor.reviews.reject', $review) }}" method="POST" class="inline shrink-0" onsubmit="return confirm('Reject this review?');">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 rounded-md border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-[10px] font-bold leading-none hover:bg-slate-50 dark:hover:bg-slate-700 transition">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 sm:py-20">
                                <div class="max-w-md mx-auto text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <p class="text-base font-bold text-slate-800 dark:text-slate-200">No reviews in this view</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                                        @if($searchQ)
                                            Try another search or
                                            <a href="{{ route('vendor.reviews.index', ['status' => $cur]) }}" class="font-bold text-vendor-600 dark:text-vendor-400 hover:underline">clear filters</a>.
                                        @else
                                            New reviews for your products will show under <span class="font-semibold">Pending</span> first.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-slate-500 dark:text-slate-400 order-2 sm:order-1">Page {{ $reviews->currentPage() }} of {{ $reviews->lastPage() }}</p>
                <div class="order-1 sm:order-2">{{ $reviews->links('pagination::tailwind') }}</div>
            </div>
        @endif
    </div>
@endsection
