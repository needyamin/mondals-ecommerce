@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Checkout</h1>
        <p class="text-muted">Fill in your details to complete your purchase</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row gx-lg-5">
            <!-- Left Side: Shipping & Payment -->
            <div class="col-lg-8">
                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i> Delivery Address
                        </h4>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">First Name *</label>
                                <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name', auth()->user()->name ?? '') }}" required class="form-control bg-light border-0 py-3 rounded-3 shadow-none">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Last Name *</label>
                                <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" required class="form-control bg-light border-0 py-3 rounded-3 shadow-none">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Full Address *</label>
                                <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Street, House No, Area" required class="form-control bg-light border-0 py-3 rounded-3 shadow-none">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">City *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required class="form-control bg-light border-0 py-3 rounded-3 shadow-none">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Phone Number *</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="+880 1XXXXXXXXX" class="form-control bg-light border-0 py-3 rounded-3 shadow-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="fas fa-truck text-primary me-3"></i> Shipping Method
                        </h4>
                        <div class="row g-3">
                            @foreach($availShipping as $sm)
                            <div class="col-md-6">
                                <input type="radio" class="btn-check shipping-radio" name="shipping_method" id="sm-{{ $sm['id'] }}" value="{{ $sm['id'] }}" data-cost="{{ $sm['cost'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="btn btn-outline-light text-start p-4 rounded-4 w-100 border-2 h-100 transition" for="sm-{{ $sm['id'] }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $sm['name'] }}</h6>
                                        <span class="fw-bold text-primary">৳{{ number_format($sm['cost'], 0) }}</span>
                                    </div>
                                    <p class="small text-muted mb-0">{{ $sm['estimated_days'] }}</p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="fas fa-wallet text-primary me-3"></i> Payment Method
                        </h4>
                        <div class="row g-3">
                            @foreach($paymentMethods as $pm)
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="pm-{{ $pm['id'] }}" value="{{ $pm['id'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="btn btn-outline-light text-start p-4 rounded-4 w-100 border-2 h-100 transition" for="pm-{{ $pm['id'] }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-circle-check text-primary me-2 d-none-checked"></i>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $pm['name'] }}</h6>
                                    </div>
                                    <p class="small text-muted mb-0">{{ $pm['description'] }}</p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Order Summary</h4>
                        <div class="mb-4 max-h-300 overflow-auto pe-2">
                            @foreach($cart->items as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 overflow-hidden border bg-light flex-shrink-0" style="width: 50px; height: 50px;">
                                    <img src="{{ $item->product->primary_image ? asset('storage/' . $item->product->primary_image) : 'https://placehold.co/100x100' }}" class="img-fluid w-100 h-100 object-fit-cover">
                                </div>
                                <div class="ms-3 flex-grow-1 min-w-0">
                                    <h6 class="small fw-bold mb-0 text-truncate">{{ $item->product->name }}</h6>
                                    <span class="small text-muted">Qty: {{ $item->quantity }}</span>
                                </div>
                                <div class="text-end ms-2">
                                    <span class="small fw-bold">৳{{ number_format($item->price * $item->quantity, 0) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-top pt-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="fw-bold small">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Shipping</span>
                                <span class="fw-bold small text-primary" id="shipping_display">Select Method</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-4 border-top pt-3">
                            <h5 class="fw-bold mb-0">Total</h5>
                            <h5 class="fw-bold text-primary mb-0" id="total_display">৳{{ number_format($subtotal, 2) }}</h5>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg mt-2">
                            🛒 Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .max-h-300 { max-height: 300px; }
    .btn-outline-light { background: #fdfdfd; border-color: #eee; }
    .btn-outline-light:hover { border-color: var(--bs-primary); background: #fffcfd; }
    .btn-check:checked + .btn-outline-light { border-color: var(--bs-primary); background: #fff5f7; box-shadow: 0 4px 15px rgba(255, 77, 109, 0.1); }
    .card { transition: 0.3s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = {{ $subtotal }};
    const shippingDisplay = document.getElementById('shipping_display');
    const totalDisplay = document.getElementById('total_display');
    const shippingRadios = document.querySelectorAll('.shipping-radio');

    function updateTotals() {
        const selected = document.querySelector('.shipping-radio:checked');
        if (selected) {
            const cost = parseFloat(selected.dataset.cost) || 0;
            shippingDisplay.textContent = '৳' + cost.toFixed(0);
            totalDisplay.textContent = '৳' + (subtotal + cost).toLocaleString('en-BD', {minimumFractionDigits: 2});
        }
    }

    shippingRadios.forEach(radio => radio.addEventListener('change', updateTotals));
    updateTotals(); 
});
</script>
@endsection
