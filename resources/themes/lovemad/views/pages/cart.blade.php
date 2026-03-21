@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Your Cart</h1>
        <p class="text-muted">Review your items before checkout</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($items->count() > 0)
    <div class="row gx-lg-5">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 small fw-bold text-muted text-uppercase">Product</th>
                                <th class="px-4 py-3 small fw-bold text-muted text-uppercase text-center">Quantity</th>
                                <th class="px-4 py-3 small fw-bold text-muted text-uppercase text-end">Total</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $item)
                            @php
                                if (auth()->check()) {
                                    $product = $item->product;
                                    $itemQty = $item->quantity;
                                    $lineTotal = $item->price * $item->quantity;
                                    $itemId = $item->id;
                                    $price = $item->price;
                                } else {
                                    $product = $item['product'] ?? null;
                                    $itemQty = $item['quantity'];
                                    $lineTotal = $item['line_total'] ?? 0;
                                    $itemId = ($item['product_id'] ?? 0);
                                    $price = $product ? $product->price : 0;
                                }
                            @endphp
                            @if($product)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 overflow-hidden border bg-light" style="width: 70px; height: 70px;">
                                            <img src="{{ $product->primary_image ? asset('storage/' . $product->primary_image) : 'https://placehold.co/100x100' }}" class="img-fluid w-100 h-100 object-fit-cover">
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="fw-bold mb-0"><a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a></h6>
                                            <span class="small text-muted">৳{{ number_format($price, 0) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="d-flex justify-content-center">
                                        <form action="{{ route('cart.update') }}" method="POST" class="input-group" style="width: 120px;">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $itemId }}">
                                            <button type="submit" name="quantity" value="{{ max(0, $itemQty - 1) }}" class="btn btn-light border-0 py-1"><i class="fas fa-minus small"></i></button>
                                            <input type="text" class="form-control bg-light border-0 text-center fw-bold small py-1" value="{{ $itemQty }}" readonly>
                                            <button type="submit" name="quantity" value="{{ $itemQty + 1 }}" class="btn btn-light border-0 py-1"><i class="fas fa-plus small"></i></button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <span class="fw-bold">৳{{ number_format($lineTotal, 0) }}</span>
                                </td>
                                <td class="px-4 py-4 text-end">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $itemId }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 shadow-none"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white p-4 border-0 text-end">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted small text-decoration-none">Clear Shopping Cart</button>
                    </form>
                </div>
            </div>
            <a href="{{ route('products') }}" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="fas fa-arrow-left me-2"></i> Continue Shopping</a>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Cart Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <span class="fw-bold text-success">Included</span>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="fw-bold mb-0">Order Total</h5>
                        <h5 class="fw-bold text-primary mb-0">৳{{ number_format($subtotal, 2) }}</h5>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">Proceed to Checkout</a>
                    
                    <div class="mt-4 text-center">
                        <div class="d-flex justify-content-center gap-3 opacity-50">
                            <i class="fab fa-cc-visa fs-4"></i>
                            <i class="fab fa-cc-mastercard fs-4"></i>
                            <i class="fas fa-mobile-screen fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-shopping-basket display-1 text-light"></i>
        </div>
        <h3 class="fw-bold">Your cart is empty</h3>
        <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
        <a href="{{ route('products') }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
