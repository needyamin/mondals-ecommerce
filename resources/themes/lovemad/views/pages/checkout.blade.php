@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-3">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart') }}" class="text-decoration-none text-muted">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-3">
            {{-- Left: Shipping & Payment --}}
            <div class="col-lg-8">
                {{-- Delivery Address --}}
                <div class="bg-white shadow-sm p-4 mb-3" style="border-radius: 4px;">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="font-size: 16px;">
                        <i class="bi bi-geo-alt-fill text-primary me-2 fs-5"></i> Delivery Address
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name', auth()->user()->name ?? '') }}" required
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" required
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Address <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="House No, Road, Area..."
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">City <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required placeholder="Dhaka"
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Division / State</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" placeholder="Dhaka Division"
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">ZIP Code <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required placeholder="1200"
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Country <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_country" value="{{ old('shipping_country', 'Bangladesh') }}" required
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="+880 1XXXXXXXXX"
                                   class="form-control bg-light border-0 shadow-none" style="font-size: 13px; padding: 10px 14px;">
                        </div>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="bg-white shadow-sm p-4 mb-3" style="border-radius: 4px;">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="font-size: 16px;">
                        <i class="bi bi-truck text-primary me-2 fs-5"></i> Shipping Method
                    </h5>
                    <div class="row g-3">
                        @forelse($availShipping as $sm)
                            <div class="col-md-6">
                                <input type="radio" class="btn-check shipping-radio" name="shipping_method" id="sm-{{ $sm['id'] }}" value="{{ $sm['id'] }}" data-cost="{{ $sm['cost'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="btn w-100 text-start p-3 border-2 shipping-label" for="sm-{{ $sm['id'] }}" style="border-radius: 4px;">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold" style="font-size: 13px;">{{ $sm['name'] }}</span>
                                        <span class="fw-bold" style="font-size: 13px; color: var(--lm-primary);">
                                            @if($sm['cost'] > 0) ৳{{ number_format($sm['cost'], 0) }} @else Free @endif
                                        </span>
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $sm['estimated_days'] ?? 'Standard delivery' }}</div>
                                </label>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted" style="font-size: 13px;">No shipping methods available.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white shadow-sm p-4 mb-3" style="border-radius: 4px;">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="font-size: 16px;">
                        <i class="bi bi-credit-card text-primary me-2 fs-5"></i> Payment Method
                    </h5>
                    <div class="row g-3">
                        @forelse($paymentMethods as $pm)
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="pm-{{ $pm['id'] }}" value="{{ $pm['id'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="btn w-100 text-start p-3 border-2 payment-label" for="pm-{{ $pm['id'] }}" style="border-radius: 4px;">
                                    <div class="fw-bold mb-1" style="font-size: 13px;">{{ $pm['name'] }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $pm['description'] }}</div>
                                </label>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted" style="font-size: 13px;">No payment methods available.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Order Notes --}}
                <div class="bg-white shadow-sm p-4 mb-3" style="border-radius: 4px;">
                    <h5 class="fw-bold mb-3" style="font-size: 16px;">Order Notes</h5>
                    <textarea name="notes" rows="2" placeholder="Any special instructions..." class="form-control bg-light border-0 shadow-none" style="font-size: 13px;">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="col-lg-4">
                <div class="bg-white shadow-sm p-4 sticky-top" style="border-radius: 4px; top: 120px;">
                    <h5 class="fw-bold mb-4 pb-3 border-bottom" style="font-size: 16px;">Your Order</h5>

                    <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                        @foreach($cart->items as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="border rounded overflow-hidden flex-shrink-0 me-2" style="width: 44px; height: 44px;">
                                    @if($item->product && $item->product->primary_image)
                                        <img src="{{ asset('storage/' . $item->product->primary_image) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center"><i class="bi bi-image text-muted small"></i></div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-w-0 me-2">
                                    <div class="text-truncate" style="font-size: 12px;">{{ $item->product->name ?? 'Product' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">x{{ $item->quantity }}</div>
                                </div>
                                <span class="fw-bold flex-shrink-0" style="font-size: 13px;">৳{{ number_format($item->price * $item->quantity, 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-medium">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13px;">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-medium" style="color: var(--lm-primary);" id="shipping_display">Select method</span>
                        </div>
                    </div>

                    <div class="border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold" style="font-size: 16px;">Total</span>
                            <span class="fw-bold" style="font-size: 20px; color: var(--lm-primary);" id="total_display">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius: 2px; font-size: 14px;">
                        🛒 PLACE ORDER
                    </button>

                    <p class="text-center text-muted mt-3" style="font-size: 11px;">
                        By placing an order you agree to our <a href="#" class="text-decoration-underline">Terms & Conditions</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .shipping-label, .payment-label {
        background: #fdfdfd;
        border-color: #e0e0e0 !important;
        transition: all .2s;
    }
    .shipping-label:hover, .payment-label:hover {
        border-color: var(--lm-primary) !important;
    }
    .btn-check:checked + .shipping-label,
    .btn-check:checked + .payment-label {
        border-color: var(--lm-primary) !important;
        background: var(--lm-orange-soft) !important;
        box-shadow: 0 2px 8px rgba(248, 86, 6, .1);
    }
</style>
@endpush

@push('scripts')
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
            shippingDisplay.textContent = cost > 0 ? '৳' + cost.toFixed(0) : 'Free';
            totalDisplay.textContent = '৳' + (subtotal + cost).toLocaleString('en-BD', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }

    shippingRadios.forEach(radio => radio.addEventListener('change', updateTotals));
    updateTotals();
});
</script>
@endpush

@endsection
