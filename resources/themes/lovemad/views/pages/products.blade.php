@extends('layouts.app')

@section('title', 'Shop Products')

@section('content')
<div class="container py-3">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            @if(request('category'))
                <li class="breadcrumb-item active">{{ ucfirst(request('category')) }}</li>
            @else
                <li class="breadcrumb-item active">All Products</li>
            @endif
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-3">
        {{-- Sidebar Filters --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="bg-white p-3 shadow-sm" style="border-radius: 4px;">
                <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 13px; border-bottom: 2px solid var(--lm-primary); padding-bottom: 8px;">
                    <i class="bi bi-funnel me-1"></i> Filters
                </h6>

                {{-- Category Filters --}}
                @php
                    $allCategories = \App\Models\Category::where('is_active', true)->withCount('products')->orderBy('name')->get();
                @endphp
                <div class="mb-4">
                    <h6 class="fw-bold mb-2" style="font-size: 12px; color: var(--lm-text-muted); text-transform: uppercase;">Category</h6>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('products', request()->except('category')) }}"
                           class="text-decoration-none d-flex justify-content-between align-items-center py-1 px-2 rounded {{ !request('category') ? 'fw-bold' : '' }}"
                           style="font-size: 13px; color: {{ !request('category') ? 'var(--lm-primary)' : '#555' }}; background: {{ !request('category') ? 'var(--lm-orange-soft)' : 'transparent' }};">
                            All Products
                        </a>
                        @foreach($allCategories as $cat)
                            <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                               class="text-decoration-none d-flex justify-content-between align-items-center py-1 px-2 rounded"
                               style="font-size: 13px; color: {{ request('category') === $cat->slug ? 'var(--lm-primary)' : '#555' }}; background: {{ request('category') === $cat->slug ? 'var(--lm-orange-soft)' : 'transparent' }};">
                                <span>{{ $cat->name }}</span>
                                <span class="text-muted" style="font-size: 11px;">({{ $cat->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Sort Options --}}
                <div>
                    <h6 class="fw-bold mb-2" style="font-size: 12px; color: var(--lm-text-muted); text-transform: uppercase;">Sort By</h6>
                    <form action="{{ route('products') }}" method="GET" id="filterForm">
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm border-0 bg-light shadow-none" style="font-size: 13px;">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A–Z</option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z–A</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="col-lg-9">
            {{-- Toolbar --}}
            <div class="bg-white p-3 shadow-sm mb-3 d-flex flex-wrap justify-content-between align-items-center" style="border-radius: 4px;">
                <span class="text-muted" style="font-size: 13px;">
                    <strong>{{ $products->total() }}</strong> items found
                    @if(request('q'))
                        for "<strong class="text-primary">{{ request('q') }}</strong>"
                        <a href="{{ route('products') }}" class="text-danger ms-2 text-decoration-none small fw-bold"><i class="bi bi-x-circle me-1"></i>Clear</a>
                    @endif
                </span>

                {{-- Mobile sort --}}
                <div class="d-lg-none">
                    <form action="{{ route('products') }}" method="GET" id="mobileSortForm">
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm border-0 bg-light shadow-none" style="font-size: 12px; width: auto;">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price ↑</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price ↓</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Mobile Category Pills --}}
            <div class="d-lg-none mb-3 overflow-auto custom-scrollbar pb-1">
                <div class="d-inline-flex gap-2">
                    <a href="{{ route('products', request()->except('category')) }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} fw-medium" style="font-size: 12px; border-radius: 2px; white-space: nowrap;">
                        All
                    </a>
                    @foreach($allCategories as $cat)
                        <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="btn btn-sm {{ request('category') === $cat->slug ? 'btn-primary' : 'btn-outline-secondary' }} fw-medium" style="font-size: 12px; border-radius: 2px; white-space: nowrap;">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if($products->isEmpty())
                <div class="bg-white text-center py-5 shadow-sm" style="border-radius: 4px;">
                    <i class="bi bi-search display-4 text-muted d-block mb-3" style="opacity: .3;"></i>
                    <h5 class="fw-bold mb-2">No Products Found</h5>
                    <p class="text-muted mb-3" style="font-size: 13px;">Try adjusting your filters or search criteria.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary btn-sm fw-bold px-4" style="border-radius: 2px;">Clear All Filters</a>
                </div>
            @else
                <div class="row row-cols-2 row-cols-sm-3 row-cols-xl-4 g-2">
                    @foreach($products as $product)
                        <div class="col">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
