@extends('layouts.app')

@section('title', 'Registered Stores')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-4">
            Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Retail Partners</span>
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">Browse verified seller storefronts and open their catalog.</p>
    </div>

    <form method="get" action="{{ route('stores.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:justify-between mb-10 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white/80 dark:bg-slate-900/60 p-4">
        <div class="flex flex-1 flex-col sm:flex-row gap-3 min-w-0">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by store name, city, email…"
                   class="flex-1 min-w-0 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/40">
            <select name="sort" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/40 sm:w-48 shrink-0">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest first</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                <option value="name" @selected(request('sort') === 'name')>Name (A–Z)</option>
                <option value="name_desc" @selected(request('sort') === 'name_desc')>Name (Z–A)</option>
                <option value="products" @selected(request('sort') === 'products')>Most products</option>
            </select>
        </div>
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="px-5 py-3 rounded-xl bg-gradient-to-r from-primary to-indigo-600 text-white text-sm font-bold shadow-lg shadow-primary/25 hover:opacity-95 transition">Search</button>
            <a href="{{ route('stores.index') }}" class="px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">Clear</a>
        </div>
    </form>

    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 text-center md:text-left">
        Showing <strong class="text-slate-800 dark:text-slate-200">{{ $vendors->firstItem() ?? 0 }}–{{ $vendors->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800 dark:text-slate-200">{{ $vendors->total() }}</strong> stores
    </p>

    @if($vendors->isEmpty())
        <div class="text-center py-24 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">No stores found</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Try a different search term.</p>
            <a href="{{ route('stores.index') }}" class="inline-flex px-6 py-3 rounded-xl bg-primary text-white text-sm font-bold">Show all stores</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($vendors as $vendor)
                @php($cardLoc = collect([$vendor->city, $vendor->state, $vendor->country])->filter(fn ($v) => filled($v))->implode(' · '))
                <article class="group relative flex flex-col rounded-3xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="relative w-full shrink-0">
                        <div class="relative h-36 sm:h-44 w-full overflow-hidden rounded-t-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700">
                            @if($vendor->display_banner)
                                <img src="{{ $vendor->display_banner }}" alt="" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/75 via-slate-900/20 to-transparent pointer-events-none"></div>
                        </div>
                        <div class="absolute left-1/2 bottom-0 z-20 -translate-x-1/2 translate-y-1/2">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full ring-4 ring-white dark:ring-slate-900 shadow-xl overflow-hidden bg-white dark:bg-slate-800 flex items-center justify-center">
                                <img src="{{ $vendor->display_image }}" alt="{{ $vendor->store_name }}" class="max-h-full max-w-full w-full h-full object-contain object-center p-0.5">
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pt-14 sm:pt-16 pb-6 flex flex-col flex-grow text-center rounded-b-3xl">
                        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white leading-tight">{{ $vendor->store_name }}</h3>
                        @if(filled($cardLoc))
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-2">{{ $cardLoc }}</span>
                        </p>
                        @endif
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 flex-grow line-clamp-3 text-left px-1">
                            {{ \Illuminate\Support\Str::limit($vendor->description ?? 'Verified partner on Mondals—browse their full catalog.', 140) }}
                        </p>
                        <div class="mt-6 mb-6 grid grid-cols-2 gap-3 w-full">
                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/80 py-3 px-2 border border-slate-100 dark:border-slate-700">
                                <span class="block text-2xl font-extrabold text-slate-900 dark:text-white leading-none">{{ $vendor->published_products_count }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Live products</span>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/80 py-3 px-2 border border-slate-100 dark:border-slate-700">
                                @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                                <span class="block text-2xl font-extrabold text-amber-500 leading-none">{{ number_format($vendor->products->avg(fn($p) => $p->reviews->avg('rating')) ?? 0, 1) }}</span>
                                @else
                                <span class="block text-2xl font-extrabold text-slate-300 dark:text-slate-600 leading-none">—</span>
                                @endif
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Avg. rating</span>
                            </div>
                        </div>
                        <a href="{{ route('stores.show', $vendor->slug) }}" class="mb-6 w-full inline-flex justify-center items-center gap-2 py-3.5 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold hover:opacity-90 transition">
                            View store
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5 6"/></svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-16 flex justify-center">
            {{ $vendors->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
