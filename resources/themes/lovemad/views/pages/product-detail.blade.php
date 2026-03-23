@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 160))

@section('content')
<div class="container py-3">

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
                    <img src="{{ $product->primary_image }}" alt="{{ $product->name }}"
                         class="w-100 h-100" style="object-fit: contain;" id="main-product-image">
                </div>
                @if($product->images->count() > 0)
                    <div class="d-flex gap-2 overflow-auto custom-scrollbar pb-2">
                        @foreach($product->images as $image)
                            <div class="flex-shrink-0 border rounded overflow-hidden cursor-pointer thumb-item"
                                 style="width: 64px; height: 64px; cursor: pointer;"
                                 onclick="document.getElementById('main-product-image').src='{{ $image->url }}'">
                                <img src="{{ $image->url }}" class="w-100 h-100" style="object-fit: cover;">
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
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center me-3">
                        @php $avgRating = $product->getAverageRatingAttribute(); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}" style="font-size: 14px; color: var(--lm-star);"></i>
                        @endfor
                        <span class="ms-2 fw-bold" style="font-size: 14px; color: var(--lm-primary);">{{ $avgRating }}</span>
                    </div>
                    <span class="text-muted" style="font-size: 13px;">{{ $product->reviews_count ?? $product->reviews->count() }} Ratings</span>
                    <span class="mx-2 text-muted">|</span>
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
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <label class="me-2 text-muted" style="font-size: 13px;">Quantity</label>
                        <div class="input-group" style="width: 130px;">
                            <button class="btn btn-outline-secondary border-end-0 px-3" type="button" id="qty-minus">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="qty-input" value="1" min="1" max="{{ $product->quantity }}"
                                   class="form-control text-center border-start-0 border-end-0 shadow-none fw-bold" style="font-size: 14px;" readonly>
                            <button class="btn btn-outline-secondary border-start-0 px-3" type="button" id="qty-plus">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="form-qty" value="1">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius: 2px; font-size: 14px;">
                            <i class="bi bi-cart-plus me-2"></i> Add to Cart
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
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-uppercase border-0" style="font-size: 13px;"
                        data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button">Reviews ({{ $product->reviews->count() }})</button>
            </li>
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
                        <tr><td class="fw-bold text-muted">Sold By</td><td><a href="{{ route('stores.index') }}" class="text-decoration-none fw-bold">{{ $product->vendor->store_name }}</a></td></tr>
                    </tbody>
                </table>
            </div>
            {{-- Reviews --}}
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
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold mb-3" style="font-size: 14px;">Write a Review</h6>
                        <textarea class="form-control bg-light border-0 shadow-none mb-3" rows="3" placeholder="Share your experience..." style="font-size: 13px;"></textarea>
                        <button class="btn btn-primary btn-sm fw-bold px-4" style="border-radius: 2px;">Submit Review</button>
                    </div>
                @else
                    <div class="mt-3 p-3 bg-light rounded text-center" style="font-size: 13px;">
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Log in</a> to write a review.
                    </div>
                @endauth
            </div>
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
        const maxQty = {{ $product->quantity }};

        document.getElementById('qty-minus').addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                formQty.value = qtyInput.value;
            }
        });

        document.getElementById('qty-plus').addEventListener('click', () => {
            if (parseInt(qtyInput.value) < maxQty) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
                formQty.value = qtyInput.value;
            }
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
</style>
@endpush

@endsection
