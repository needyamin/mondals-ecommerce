@extends('layouts.app')

@section('title', 'Love Your Style')

@section('content')
<!-- Header Banner -->
<header class="bg-primary py-5 mb-5 text-white" style="background: linear-gradient(135deg, var(--bs-primary) 0%, #ff85a1 100%) !important;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center">
            <h1 class="display-3 fw-bolder mb-3">{{ @themeValue('hero_title', 'Welcome to Lovemad') }}</h1>
            <p class="lead fw-normal text-white-50 mb-4">{{ @themeValue('hero_subtitle', 'Shop with love and style') }}</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('products') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-primary shadow">Shop Now</a>
                <a href="#featured" class="btn btn-outline-light btn-lg px-5 rounded-pill fw-bold">Explore Categories</a>
            </div>
        </div>
    </div>
</header>

<!-- Featured Categories -->
<section class="py-5 bg-white overflow-hidden" id="featured">
    <div class="container px-4 px-lg-5">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h2 class="fw-bold fs-3 mb-0">Browse Categories</h2>
            <a href="{{ route('products') }}" class="text-primary text-decoration-none fw-600">View All <i class="fas fa-arrow-right ms-2 small"></i></a>
        </div>
        <div class="row gx-4 gy-4">
            @foreach($categories as $category)
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('products', ['category' => $category->slug]) }}" class="text-decoration-none group">
                    <div class="card h-100 border-0 text-center bg-light rounded-4 p-4 transition-transform hover-lift">
                        <div class="mb-3">
                            <i class="{{ $category->icon ?? 'fas fa-tag' }} fs-1 text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">{{ $category->name }}</h6>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5" id="products">
    <div class="container px-4 px-lg-5">
        <div class="text-center mb-5">
            <span class="love-badge mb-2 d-inline-block">Curated for You</span>
            <h2 class="fw-bold display-5">Featured Products</h2>
        </div>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @forelse($featuredProducts as $product)
            <div class="col mb-5">
                <div class="card h-100 border-0 product-card shadow-sm rounded-4 overflow-hidden bg-white">
                    <!-- Product image-->
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
                    <!-- Product actions-->
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small mb-1">{{ $product->brand->name ?? 'Mondals' }}</div>
                        <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                        <div class="fs-5 text-primary fw-bold">
                            ৳{{ number_format($product->price, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center">New products coming soon!</p>
            @endforelse
        </div>
    </div>
</section>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .product-card { transition: all 0.3s ease; border: 1px solid #f1f1f1 !important; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
    .product-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.1); opacity: 0; transition: 0.3s; }
    .product-card:hover .product-overlay { opacity: 1; }
    .hover-lift { transition: transform 0.25s ease-out; }
    .hover-lift:hover { transform: translateY(-3px); }
</style>
@endsection
