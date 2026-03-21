@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted small">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted small">Shop</a></li>
            <li class="breadcrumb-item active small" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row gx-lg-5">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                <img src="{{ $product->primary_image ? asset('storage/' . $product->primary_image) : 'https://placehold.co/600x600/f8f9fa/555?text=Product+Image' }}" class="img-fluid w-100" id="main-product-image" alt="{{ $product->name }}">
            </div>
            @if($product->images->count() > 0)
            <div class="row gx-2">
                @foreach($product->images as $img)
                <div class="col-3">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden cursor-pointer thumb-img" onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $img->image) }}'">
                        <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid w-100">
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <span class="love-badge mb-2 d-inline-block">{{ $product->brand->name ?? 'Mondals' }}</span>
                <h1 class="fw-bold display-6 mb-3">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-muted small">(4.5 Ratings & 12 Reviews)</span>
                </div>

                <div class="mb-4">
                    <span class="display-5 fw-bold text-primary">৳{{ number_format($product->price, 2) }}</span>
                    @if($product->compare_price > $product->price)
                        <span class="text-muted text-decoration-line-through ms-3 fs-4">৳{{ number_format($product->compare_price, 2) }}</span>
                    @endif
                </div>

                <p class="text-muted mb-5 lead fs-6">
                    {{ $product->short_description ?? Str::limit(strip_tags($product->description), 200) }}
                </p>

                <div class="d-flex align-items-center gap-3 mb-5">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary border-0 bg-light rounded-start-pill px-3" type="button" onclick="let q = document.getElementById('qty'); if(q.value > 1) q.value--"><i class="fas fa-minus small"></i></button>
                        <input type="number" id="qty" name="quantity" class="form-control bg-light border-0 text-center fw-bold" value="1" readonly>
                        <button class="btn btn-outline-secondary border-0 bg-light rounded-end-pill px-3" type="button" onclick="let q = document.getElementById('qty'); q.value++"><i class="fas fa-plus small"></i></button>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="form-qty" value="1">
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold py-3 shadow-lg" onclick="document.getElementById('form-qty').value = document.getElementById('qty').value">
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <div class="border-top pt-4">
                    <div class="row text-center gy-3">
                        <div class="col-4">
                            <i class="fas fa-truck text-muted mb-2"></i>
                            <p class="small text-muted mb-0">Free Delivery</p>
                        </div>
                        <div class="col-4 border-start border-end">
                            <i class="fas fa-rotate-left text-muted mb-2"></i>
                            <p class="small text-muted mb-0">Easy Returns</p>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-shield-halved text-muted mb-2"></i>
                            <p class="small text-muted mb-0">Secure Payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="mt-5 py-5">
        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 mx-2" id="pills-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-desc" type="button" role="tab" aria-controls="pills-desc" aria-selected="true">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 mx-2" id="pills-spec-tab" data-bs-toggle="pill" data-bs-target="#pills-spec" type="button" role="tab" aria-controls="pills-spec" aria-selected="false">Specifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 mx-2" id="pills-review-tab" data-bs-toggle="pill" data-bs-target="#pills-review" type="button" role="tab" aria-controls="pills-review" aria-selected="false">Reviews</button>
            </li>
        </ul>
        <div class="tab-content bg-white p-5 shadow-sm rounded-4" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-desc" role="tabpanel" aria-labelledby="pills-desc-tab">
                {!! $product->description !!}
            </div>
            <div class="tab-pane fade" id="pills-spec" role="tabpanel" aria-labelledby="pills-spec-tab">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">SKU</th>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>Weight</th>
                        <td>{{ $product->weight ?? 'N/A' }} kg</td>
                    </tr>
                    <tr>
                        <th>Brand</th>
                        <td>{{ $product->brand->name ?? 'General' }}</td>
                    </tr>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab">
                <p class="text-center text-muted italic">Share your experience with this product!</p>
            </div>
        </div>
    </div>
</div>

<style>
    .thumb-img:hover { border: 2px solid var(--bs-primary) !important; transition: 0.2s; }
    .nav-pills .nav-link.active { background-color: var(--bs-primary) !important; color: white !important; }
    .nav-link { color: #555; font-weight: 600; }
</style>
@endsection
