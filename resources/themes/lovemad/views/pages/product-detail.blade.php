@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 160))

@section('content')
<div class="container py-3">
    @if(session('success'))
        <div class="alert alert-success py-2 px-3 mb-3" style="font-size: 13px; border-radius: 4px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 13px; border-radius: 4px;">{{ session('error') }}</div>
    @endif

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products') }}" class="text-decoration-none text-muted">Shop</a></li>
            @if($product->categories->first())
                <li class="breadcrumb-item"><a href="{{ route('products', ['category' => $product->categories->first()->slug]) }}" class="text-decoration-none text-muted">{{ $product->categories->first()->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Product Detail Card --}}
    <div class="bg-white shadow-sm p-3 p-md-4 mb-3" style="border-radius: 4px;">
        <div class="row g-4">
            {{-- Images --}}
            <div class="col-lg-5">
                <div class="position-relative mb-3 border rounded overflow-hidden" style="aspect-ratio: 1/1;">
                    <img src="{{ $product->display_image }}" alt="{{ $product->name }}"
                         class="w-100 h-100" style="object-fit: contain;" id="main-product-image">
                </div>
                @if($product->images->count() > 0)
                    <div class="d-flex gap-2 overflow-auto custom-scrollbar pb-2">
                        @foreach($product->images as $image)
                            <div class="flex-shrink-0 border rounded overflow-hidden cursor-pointer thumb-item"
                                 style="width: 64px; height: 64px; cursor: pointer;"
                                 onclick="document.getElementById('main-product-image').src='{{ $image->display_url }}'">
                                <img src="{{ $image->display_url }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- Product Info --}}
            <div class="col-lg-7">
                {{-- Brand --}}
                @if($product->brand)
                    <a href="{{ route('products') }}" class="text-decoration-none text-primary small fw-bold text-uppercase">{{ $product->brand->name }}</a>
                @endif

                <h1 class="fw-bold mb-3" style="font-size: 20px; line-height: 1.4;">{{ $product->name }}</h1>

                {{-- Rating --}}
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom flex-wrap">
                    @if($reviewsEnabled ?? false)
                    <div class="d-flex align-items-center me-3">
                        @php $avgRating = $product->getAverageRatingAttribute(); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}" style="font-size: 14px; color: var(--lm-star);"></i>
                        @endfor
                        <span class="ms-2 fw-bold" style="font-size: 14px; color: var(--lm-primary);">{{ $avgRating }}</span>
                    </div>
                    <span class="text-muted" style="font-size: 13px;">{{ $product->reviews_count ?? $product->reviews->count() }} Ratings</span>
                    <span class="mx-2 text-muted">|</span>
                    @endif
                    <span class="text-muted" style="font-size: 13px;"><span style="color: var(--lm-success)">●</span> {{ $product->quantity }} in stock</span>
                </div>

                {{-- Price Block --}}
                <div class="mb-4 p-3 rounded" style="background: var(--lm-orange-soft);">
                    <div class="d-flex align-items-baseline gap-3">
                        <span style="font-size: 28px; font-weight: 700; color: var(--lm-primary);">৳{{ number_format($product->price, 0) }}</span>
                        @if($product->compare_price > $product->price)
                            <span class="price-old" style="font-size: 16px;">৳{{ number_format($product->compare_price, 0) }}</span>
                            <span class="badge bg-danger" style="font-size: 11px;">-{{ $product->getDiscountPercentAttribute() }}%</span>
                        @endif
                    </div>
                </div>

                {{-- Short Description --}}
                <p class="text-muted mb-4" style="font-size: 14px; line-height: 1.7;">
                    {{ $product->short_description ?? Str::limit(strip_tags($product->description), 200) }}
                </p>

                {{-- Quantity & Add to Cart --}}
                @php
                    $stockQty = max(0, (int) $product->quantity);
                    $maxSelectable = $stockQty > 0 ? $stockQty : 1;
                @endphp
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <span class="text-muted flex-shrink-0" style="font-size: 13px;">Quantity</span>
                    <div class="product-qty-wrap d-inline-flex align-items-stretch rounded border bg-white overflow-hidden" role="group" aria-label="Quantity">
                        <button class="product-qty-btn" type="button" id="qty-minus" aria-label="Decrease quantity" @if($stockQty < 1) disabled @endif>
                            <i class="bi bi-dash" style="font-size: 1.1rem;"></i>
                        </button>
                        <input type="number" id="qty-input" value="{{ $stockQty > 0 ? 1 : 0 }}" min="{{ $stockQty > 0 ? 1 : 0 }}" max="{{ $maxSelectable }}"
                               class="product-qty-input text-center fw-bold border-0 flex-shrink-0" style="font-size: 15px;" inputmode="numeric" readonly>
                        <button class="product-qty-btn" type="button" id="qty-plus" aria-label="Increase quantity" @if($stockQty < 1) disabled @endif>
                            <i class="bi bi-plus" style="font-size: 1.1rem;"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="form-qty" value="{{ $stockQty > 0 ? 1 : 0 }}">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius: 2px; font-size: 14px;" @if($stockQty < 1) disabled @endif>
                            <i class="bi bi-cart-plus me-2"></i> {{ $stockQty < 1 ? 'Out of stock' : 'Add to Cart' }}
                        </button>
                    </form>
                </div>

                {{-- Delivery Info --}}
                <div class="border rounded p-3" style="font-size: 13px;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-truck me-3 text-primary fs-5"></i>
                        <div>
                            <div class="fw-bold">Delivery</div>
                            <div class="text-muted">Shipping available nationwide</div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-arrow-repeat me-3 text-primary fs-5"></i>
                        <div>
                            <div class="fw-bold">Return Policy</div>
                            <div class="text-muted">7 Days easy return</div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-check me-3 text-primary fs-5"></i>
                        <div>
                            <div class="fw-bold">Warranty</div>
                            <div class="text-muted">Authentic product guarantee</div>
                        </div>
                    </div>
                </div>

                {{-- Sold By --}}
                @if($product->vendor)
                    <div class="mt-3 p-3 bg-light rounded d-flex align-items-center justify-content-between" style="font-size: 13px;">
                        <div>
                            <span class="text-muted">Sold by</span>
                            <a href="{{ route('stores.show', $product->vendor->slug) }}" class="fw-bold text-decoration-none ms-1">{{ $product->vendor->store_name }}</a>
                        </div>
                        <a href="{{ route('stores.show', $product->vendor->slug) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 2px; font-size: 12px;">Visit Store</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Description / Specs / Reviews Tabs --}}
    <div class="bg-white shadow-sm p-3 p-md-4 mb-3" style="border-radius: 4px;">
        <ul class="nav nav-tabs border-0" id="detailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-uppercase border-0" style="font-size: 13px; color: var(--lm-primary); border-bottom: 2px solid var(--lm-primary) !important;"
                        data-bs-toggle="tab" data-bs-target="#tab-desc" type="button">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-uppercase border-0" style="font-size: 13px;"
                        data-bs-toggle="tab" data-bs-target="#tab-specs" type="button">Specifications</button>
            </li>
            @if($reviewsEnabled ?? false)
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-uppercase border-0" style="font-size: 13px;"
                        data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button">Reviews ({{ $product->reviews->count() }})</button>
            </li>
            @endif
        </ul>
        <div class="tab-content pt-4">
            {{-- Description --}}
            <div class="tab-pane fade show active" id="tab-desc" style="font-size: 14px; line-height: 1.8; color: #555;">
                {!! $product->description !!}
            </div>
            {{-- Specifications --}}
            <div class="tab-pane fade" id="tab-specs">
                <table class="table table-borderless mb-0" style="font-size: 13px;">
                    <tbody>
                        <tr class="border-bottom"><td class="fw-bold text-muted" style="width: 30%;">SKU</td><td>{{ $product->sku }}</td></tr>
                        @if($product->weight)
                            <tr class="border-bottom"><td class="fw-bold text-muted">Weight</td><td>{{ $product->weight }} kg</td></tr>
                        @endif
                        @if($product->brand)
                            <tr class="border-bottom"><td class="fw-bold text-muted">Brand</td><td>{{ $product->brand->name }}</td></tr>
                        @endif
                        @if($product->vendor)
                            <tr><td class="fw-bold text-muted">Sold By</td><td><a href="{{ route('stores.show', $product->vendor->slug) }}" class="text-decoration-none fw-bold">{{ $product->vendor->store_name }}</a></td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if($reviewsEnabled ?? false)
            <div class="tab-pane fade" id="tab-reviews">
                @if($product->reviews->isEmpty())
                    <p class="text-muted text-center py-4" style="font-size: 13px;">No reviews yet. Be the first to review this product!</p>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($product->reviews as $review)
                            <div class="border-bottom pb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-muted" style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size: 13px;">{{ $review->user->name }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        <i class="bi bi-star-fill" style="font-size: 12px; color: var(--lm-star);"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 text-muted" style="font-size: 13px;">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @auth
                    @if($userHasReviewed ?? false)
                        <p class="text-muted mt-4 pt-3 border-top mb-0" style="font-size: 13px;">You have already submitted a review for this product.</p>
                    @else
                    <form action="{{ route('product.reviews.store', $product->slug) }}" method="POST" class="mt-4 pt-3 border-top">
                        @csrf
                        <h6 class="fw-bold mb-3" style="font-size: 14px;">Write a Review</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted mb-1" style="font-size: 12px;">Rating</label>
                            <select name="rating" class="form-select form-select-sm" required style="max-width: 220px; font-size: 13px;">
                                @foreach([5 => '5 — Excellent', 4 => '4 — Very good', 3 => '3 — Good', 2 => '2 — Fair', 1 => '1 — Poor'] as $val => $label)
                                    <option value="{{ $val }}" @selected((int) old('rating', 5) === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted mb-1" style="font-size: 12px;">Your review</label>
                            <textarea name="comment" rows="4" class="form-control bg-light border shadow-none @error('comment') is-invalid @enderror" placeholder="Share your experience (at least 10 characters)..." style="font-size: 13px;" required minlength="10" maxlength="2000">{{ old('comment') }}</textarea>
                            @error('comment')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-4" style="border-radius: 2px;">Submit Review</button>
                    </form>
                    @endif
                @else
                    <div class="mt-3 p-3 bg-light rounded text-center" style="font-size: 13px;">
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Log in</a> to write a review.
                    </div>
                @endauth
            </div>
            @endif
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mb-3">
            <div class="section-title-bar">
                <h2><i class="bi bi-grid me-2"></i>You May Also Like</h2>
            </div>
            <div class="bg-white p-3">
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2">
                    @foreach($relatedProducts as $relProduct)
                        <div class="col">
                            @include('partials.product-card', ['product' => $relProduct])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('qty-input');
        const formQty = document.getElementById('form-qty');
        const btnMinus = document.getElementById('qty-minus');
        const btnPlus = document.getElementById('qty-plus');
        const maxQty = {{ $stockQty }};
        if (!qtyInput || maxQty < 1) return;

        function sync() {
            let n = parseInt(qtyInput.value, 10) || 1;
            n = Math.min(maxQty, Math.max(1, n));
            qtyInput.value = n;
            formQty.value = n;
            btnMinus.disabled = n <= 1;
            btnPlus.disabled = n >= maxQty;
        }
        sync();

        btnMinus.addEventListener('click', () => {
            let n = parseInt(qtyInput.value, 10) || 1;
            if (n > 1) { qtyInput.value = n - 1; sync(); }
        });

        btnPlus.addEventListener('click', () => {
            let n = parseInt(qtyInput.value, 10) || 1;
            if (n < maxQty) { qtyInput.value = n + 1; sync(); }
        });

        // Tab active style
        document.querySelectorAll('#detailTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('#detailTabs .nav-link').forEach(t => {
                    t.style.color = '#555';
                    t.style.borderBottom = '2px solid transparent';
                });
                this.style.color = 'var(--lm-primary)';
                this.style.borderBottom = '2px solid var(--lm-primary)';
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .thumb-item { transition: border-color .2s; }
    .thumb-item:hover { border-color: var(--lm-primary) !important; }
    .product-qty-wrap { border-color: var(--lm-border) !important; min-height: 44px; }
    .product-qty-btn {
        width: 44px; flex-shrink: 0; border: 0; background: #f3f4f6; color: var(--lm-text);
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
    }
    .product-qty-btn:hover:not(:disabled) { background: #e8e8e8; color: var(--lm-primary); }
    .product-qty-btn:disabled { opacity: .45; cursor: not-allowed; }
    .product-qty-input {
        width: 52px; min-width: 52px; background: #fff !important; color: var(--lm-text) !important;
        -moz-appearance: textfield;
    }
    .product-qty-input::-webkit-outer-spin-button,
    .product-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endpush

@endsection
