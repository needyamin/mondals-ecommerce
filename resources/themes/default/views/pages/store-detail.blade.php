@extends('layouts.app')

@section('title', $vendor->store_name)

@section('content')
@php($psort = request('sort', '-created_at'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="text-sm text-slate-500 dark:text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary dark:hover:text-indigo-400">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('stores.index') }}" class="hover:text-primary dark:hover:text-indigo-400">Stores</a>
        <span class="mx-2">/</span>
        <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $vendor->store_name }}</span>
    </nav>

    <!-- Vendor Header -->
    <div class="glass-panel p-8 md:p-12 rounded-3xl mb-12 relative overflow-hidden bg-gradient-to-tr from-indigo-900 via-slate-900 to-purple-900 border-none">
        
        <div class="absolute inset-0 z-0 opacity-40 mix-blend-overlay bg-cover bg-center @if(!$vendor->display_banner) bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')] @endif" @if($vendor->display_banner) style="background-image: url('{{ $vendor->display_banner }}');" @endif></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
            <div class="w-32 h-32 rounded-full border-4 border-white/20 shadow-2xl overflow-hidden bg-white dark:bg-slate-900 mb-6 md:mb-0 md:mr-8 flex-shrink-0">
                <img src="{{ $vendor->display_image }}" alt="" class="w-full h-full object-cover">
            </div>
            
            <div class="flex-grow text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-heading font-extrabold tracking-tight mb-2">{{ $vendor->store_name }}</h1>
                        <p class="text-indigo-200 text-lg font-light mb-4">Official Retail Partner</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap justify-center md:justify-start gap-3 mt-6">
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md">
                        @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                        <span class="text-amber-400 text-lg">★</span>
                        <span class="font-bold text-white">{{ number_format($vendor->products->flatMap->reviews->avg('rating') ?? 0, 1) }}</span>
                        <span class="text-indigo-200 text-xs font-medium">avg. rating</span>
                        @else
                        <span class="text-indigo-200 text-sm">Ratings off</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md text-sm font-medium text-white">
                        <svg class="w-4 h-4 text-indigo-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        {{ $products->total() }} live items
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md text-sm font-medium text-white">
                        <svg class="w-4 h-4 text-indigo-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Since {{ $vendor->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(filled($vendor->description) || filled($vendor->email) || filled($vendor->phone) || count($vendor->address_lines))
    <div class="mb-12" x-data="{ open: false }">
        <button type="button" @click="open = !open" :aria-expanded="open"
                class="w-full flex items-center justify-between gap-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-darkpanel px-5 py-4 text-left shadow-sm hover:border-primary/40 transition">
            <span class="flex items-center gap-3 min-w-0">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-primary dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block font-bold font-heading text-slate-900 dark:text-white">Store information</span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400 truncate">About, contact &amp; location — tap to <span x-text="open ? 'hide' : 'show'"></span></span>
                </span>
            </span>
            <svg class="w-5 h-5 shrink-0 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{ 'rotate-180': open }"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-transition class="mt-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @if(filled($vendor->description))
        <div class="lg:col-span-2 rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-darkpanel p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-indigo-500/20 dark:text-indigo-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white">About this store</h2>
            </div>
            <div class="text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed text-[15px]">{{ $vendor->description }}</div>
        </div>
        @endif
        <div class="space-y-6 @if(!filled($vendor->description)) lg:col-span-3 lg:grid lg:grid-cols-2 lg:gap-6 lg:space-y-0 @endif">
            @if(filled($vendor->email) || filled($vendor->phone))
            <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-darkpanel p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Contact</h2>
                </div>
                <ul class="space-y-4 text-sm">
                    @if(filled($vendor->email))
                    <li>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Email</span>
                        <a href="mailto:{{ $vendor->email }}" class="mt-1 block font-medium text-primary dark:text-indigo-400 hover:underline break-all">{{ $vendor->email }}</a>
                    </li>
                    @endif
                    @if(filled($vendor->phone))
                    <li>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Phone</span>
                        <a href="tel:{{ preg_replace('/\s+/', '', $vendor->phone) }}" class="mt-1 block font-medium text-primary dark:text-indigo-400 hover:underline">{{ $vendor->phone }}</a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
            @if(count($vendor->address_lines))
            <div class="rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-darkpanel p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Ships / based in</h2>
                </div>
                <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed">{{ implode("\n", $vendor->address_lines) }}</p>
            </div>
            @endif
        </div>
    </div>
        </div>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Catalog</h2>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ $products->total() }} products</span>
            <form method="get" class="flex items-center gap-2">
                <label for="store-sort" class="text-xs font-bold text-slate-500 uppercase tracking-wide sr-only sm:not-sr-only sm:inline">Sort</label>
                <select id="store-sort" name="sort" onchange="this.form.submit()" class="text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary/40">
                    <option value="-created_at" @selected($psort === '-created_at' || $psort === '')>Newest first</option>
                    <option value="price" @selected($psort === 'price')>Price: low → high</option>
                    <option value="-price" @selected($psort === '-price')>Price: high → low</option>
                    <option value="name" @selected($psort === 'name')>Name A–Z</option>
                    <option value="-name" @selected($psort === '-name')>Name Z–A</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-20 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800">
            <svg class="mx-auto h-16 w-16 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">No products available yet.</h3>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-16 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
