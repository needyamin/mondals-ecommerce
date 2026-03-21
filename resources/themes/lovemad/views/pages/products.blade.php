@extends('layouts.app')

@section('title', 'Explore Collections')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5 overflow-hidden">
        <h1 class="display-4 fw-bold mb-3">
            @if(request('q'))
                Results for "<span class="text-primary">{{ request('q') }}</span>"
            @elseif(request('category'))
                <span class="text-primary">{{ ucfirst(request('category')) }}</span>
            @else
                Explore <span class="text-primary">Collections</span>
            @endif
        </h1>
        <p class="text-muted lead mx-auto" style="max-width: 700px;">Browse our premium catalog of carefully curated products, crafted with love to fit your unique style.</p>
    </div>

    <!-- Enhanced Category Filters (Bootstrap 5) -->
    @php
        $allCategories = \App\Models\Category::where('is_active', true)->withCount('products')->orderBy('name')->get();
    @endphp
    <div class="mb-5 text-center overflow-auto py-2 custom-scrollbar">
        <div class="d-inline-flex gap-2">
            <a href="{{ route('products', request()->except('category')) }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-600 transition shadow-sm">
                All Collections
            </a>
            @foreach($allCategories as $cat)
                <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="btn {{ request('category') === $cat->slug ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 fw-600 transition shadow-sm">
                    {{ $cat->name }} <span class="small opacity-75 ms-1">({{ $cat->products_count }})</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Toolbar: Results Count & Sorting -->
    <div class="bg-white p-4 rounded-4 shadow-sm mb-5 d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-3 mb-md-0 d-flex align-items-center">
            <span class="text-muted small">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</span>
            @if(request('q') || request('category'))
                <a href="{{ route('products') }}" class="btn btn-link text-danger btn-sm text-decoration-none ms-3 fw-bold"><i class="fas fa-xmark me-1 small"></i> Clear All</a>
            @endif
        </div>

        <div class="d-flex align-items-center">
            <label class="small fw-bold text-muted me-3 d-none d-sm-block">Sort by:</label>
            <form action="{{ route('products') }}" method="GET" id="sortForm" class="d-flex align-items-center">
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <select name="sort" onchange="this.form.submit()" class="form-select border-0 bg-light rounded-pill px-4 shadow-none">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A–Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z–A</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    @if($products->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div class="mb-4">
                <i class="fas fa-magnifying-glass display-1 text-light"></i>
            </div>
            <h3 class="fw-bold">No Products Found</h3>
            <p class="text-muted px-4">We couldn't find any products matching your criteria. Try adjusting your filters or search keywords.</p>
            <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-5 fw-bold mt-3 shadow">View All Products</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100 border-0 product-card shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="position-relative">
                        <img class="card-img-top" src="{{ $product->primary_image ? asset('storage/' . $product->primary_image) : 'https://placehold.co/400x400/f8f9fa/555?text=No+Image' }}" alt="{{ $product->name }}" />
                        <div class="product-overlay d-flex align-items-center justify-content-center">
                            <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-primary rounded-circle me-2 p-3 border-0"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-dark rounded-circle p-3 border-0"><i class="fas fa-shopping-cart"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small mb-1">{{ $product->brand->name ?? 'Mondals' }}</div>
                        <h5 class="fw-bold mb-2 small text-truncate">{{ $product->name }}</h5>
                        <div class="fs-5 text-primary fw-bold">
                            ৳{{ number_format($product->price, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        <div class="mt-5 pt-5 d-flex justify-content-center">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .fw-600 { font-weight: 600; }
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
    .product-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.1); opacity: 0; transition: 0.3s; }
    .product-card:hover .product-overlay { opacity: 1; }
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #ffb3c1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--bs-primary); }
    .pagination .page-link { border-radius: 50% !important; margin: 0 5px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: none; color: #555; font-weight: 600; }
    .pagination .page-item.active .page-link { background-color: var(--bs-primary); color: white; box-shadow: 0 4px 15px rgba(255, 77, 109, 0.3); }
</style>
@endsection
