@extends('layouts.app')

@section('title', 'Shop Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header -->
    <div class="mb-10 text-center animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">
            @if(request('q'))
                Results for "<span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">{{ request('q') }}</span>"
            @elseif(request('category'))
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">{{ ucfirst(request('category')) }}</span>
            @else
                Explore <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Collections</span>
            @endif
        </h1>
        <p class="mt-4 text-slate-500 dark:text-slate-400 text-lg">Browse our premium catalog of carefully curated products.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Category Filters -->
    @php
        $allCategories = \App\Models\Category::where('is_active', true)->withCount('products')->orderBy('name')->get();
    @endphp
    <div class="mb-8 flex flex-wrap gap-2">
        <a href="{{ route('products', request()->except('category')) }}" class="px-4 py-2 rounded-full text-sm font-medium border transition-colors {{ !request('category') ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-primary hover:text-primary' }}">
            All Products
        </a>
        @foreach($allCategories as $cat)
            <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="px-4 py-2 rounded-full text-sm font-medium border transition-colors {{ request('category') === $cat->slug ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-primary hover:text-primary' }}">
                {{ $cat->name }} <span class="text-xs opacity-70">({{ $cat->products_count }})</span>
            </a>
        @endforeach
    </div>

    <!-- Toolbar / Filters -->
    <div class="glass-panel p-4 rounded-xl flex flex-col md:flex-row justify-between items-center mb-10 pb-4 shadow-sm">
        <div class="flex items-center space-x-4 mb-4 md:mb-0">
            <span class="text-slate-500 text-sm">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</span>
            @if(request('q'))
                <a href="{{ route('products') }}" class="text-xs text-red-500 hover:text-red-600 font-medium flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Clear search
                </a>
            @endif
        </div>

        <div class="flex items-center space-x-4">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Sort by:</label>
            <form action="{{ route('products') }}" method="GET" id="sortForm">
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <select name="sort" onchange="document.getElementById('sortForm').submit()" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A–Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z–A</option>
                </select>
            </form>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-24 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm animate-fade-in">
            <svg class="mx-auto h-20 w-20 text-slate-300 dark:text-slate-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-medium text-slate-900 dark:text-white mb-2 font-heading">No Products Found</h3>
            <p class="text-slate-500 dark:text-slate-400">Try adjusting your filters or search criteria.</p>
            <a href="{{ route('products') }}" class="mt-6 inline-flex btn-primary">Clear All Filters</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $products->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
