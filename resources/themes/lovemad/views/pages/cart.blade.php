@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-3">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active">Shopping Cart</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    @if($items->count() > 0)
    <div class="row g-3">
        {{-- Cart Items --}}
        <div class="col-lg-8">
            <div class="bg-white shadow-sm" style="border-radius: 4px;">
                {{-- Table Header --}}
                <div class="d-none d-md-flex p-3 border-bottom fw-bold text-uppercase" style="font-size: 12px; color: var(--lm-text-muted);">
                    <div style="flex: 5;">Product</div>
                    <div style="flex: 2;" class="text-center">Quantity</div>
                    <div style="flex: 2;" class="text-end">Total Price</div>
                    <div style="flex: 1;" class="text-end">Action</div>
                </div>

                {{-- Items --}}
                @foreach($items as $item)
                    @php
                        if (auth()->check()) {
                            $product = $item->product;
                            $itemQty = $item->quantity;
                            $itemPrice = $item->price;
                            $lineTotal = $item->price * $item->quantity;
                            $itemId = $item->id;
                        } else {
                            $product = $item['product'] ?? null;
                            $itemQty = $item['quantity'];
                            $itemPrice = $product ? $product->price : 0;
                            $lineTotal = $item['line_total'] ?? 0;
                            $itemId = ($item['product_id'] ?? 0) . '-' . ($item['variant_id'] ?? 0);
                        }
                    @endphp
                    @if($product)
                    <div class="p-3 border-bottom d-flex flex-wrap align-items-center">
                        {{-- Product Info --}}
                        <div class="d-flex align-items-center" style="flex: 5; min-width: 200px;">
                            <div class="border rounded overflow-hidden flex-shrink-0 me-3" style="width: 72px; height: 72px;">
                                @if($product->primary_image)
                                    <img src="{{ asset('storage/' . $product->primary_image) }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light"><i class="bi bi-image text-muted"></i></div>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark fw-medium" style="font-size: 13px;">
                                    {{ Str::limit($product->name, 50) }}
                                </a>
                                @if($product->vendor)
                                    <div class="text-muted" style="font-size: 11px;">Sold by: {{ $product->vendor->store_name }}</div>
                                @endif
                                <div class="text-muted" style="font-size: 12px;">৳{{ number_format($itemPrice, 0) }} each</div>
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div style="flex: 2;" class="d-flex justify-content-center my-2 my-md-0">
                            <form action="{{ route('cart.update') }}" method="POST" class="input-group" style="width: 110px;">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $itemId }}">
                                <button type="submit" name="quantity" value="{{ max(0, $itemQty - 1) }}" class="btn btn-outline-secondary btn-sm px-2 border-end-0"><i class="bi bi-dash"></i></button>
                                <input type="text" class="form-control form-control-sm text-center border-start-0 border-end-0 shadow-none fw-bold" value="{{ $itemQty }}" readonly style="max-width: 40px;">
                                <button type="submit" name="quantity" value="{{ $itemQty + 1 }}" class="btn btn-outline-secondary btn-sm px-2 border-start-0"><i class="bi bi-plus"></i></button>
                            </form>
                        </div>

                        {{-- Total --}}
                        <div style="flex: 2;" class="text-end">
                            <span class="fw-bold" style="color: var(--lm-primary); font-size: 15px;">৳{{ number_format($lineTotal, 0) }}</span>
                        </div>

                        {{-- Remove --}}
                        <div style="flex: 1;" class="text-end">
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $itemId }}">
                                <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Remove"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </div>
                    @endif
                @endforeach

                {{-- Bottom Actions --}}
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('products') }}" class="text-decoration-none fw-bold small" style="color: var(--lm-primary);">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted text-decoration-none p-0 small">Clear Cart</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-4">
            <div class="bg-white shadow-sm p-4 sticky-top" style="border-radius: 4px; top: 120px;">
                <h5 class="fw-bold mb-4 pb-3 border-bottom" style="font-size: 16px;">Order Summary</h5>

                <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                    <span class="text-muted">Subtotal ({{ $items->count() }} items)</span>
                    <span class="fw-bold">৳{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3" style="font-size: 13px;">
                    <span class="text-muted">Shipping</span>
                    <span class="fw-bold" style="color: var(--lm-success);">Calculated at checkout</span>
                </div>

                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold" style="font-size: 16px;">Total</span>
                    <span class="fw-bold" style="font-size: 20px; color: var(--lm-primary);">৳{{ number_format($subtotal, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius: 2px; font-size: 14px;">
                    PROCEED TO CHECKOUT
                </a>

                <div class="d-flex justify-content-center gap-2 mt-3 opacity-50" style="font-size: 11px;">
                    <span class="border rounded px-2 py-1 bg-light fw-bold">bKash</span>
                    <span class="border rounded px-2 py-1 bg-light fw-bold">Nagad</span>
                    <span class="border rounded px-2 py-1 bg-light fw-bold">VISA</span>
                    <span class="border rounded px-2 py-1 bg-light fw-bold">COD</span>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Empty Cart --}}
    <div class="bg-white text-center py-5 shadow-sm" style="border-radius: 4px;">
        <i class="bi bi-cart-x display-1 d-block mb-3" style="color: #ddd;"></i>
        <h4 class="fw-bold mb-2">Your cart is empty</h4>
        <p class="text-muted mb-4" style="font-size: 13px;">Looks like you haven't added any products to your cart yet.</p>
        <a href="{{ route('products') }}" class="btn btn-primary fw-bold px-5" style="border-radius: 2px; font-size: 14px;">
            START SHOPPING
        </a>
    </div>
    @endif
</div>
@endsection
