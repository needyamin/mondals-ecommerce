@extends('layouts.customer')
@section('title', 'Order Details #' . $order->order_number)

@section('customer_content')
<div class="bg-white shadow-sm p-4 d-flex align-items-center mb-4" style="border-radius: 4px;">
    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i> Back</a>
    <h4 class="fw-bold mb-0">Order #{{ $order->order_number }}</h4>
</div>

{{-- Order Tracking Progress --}}
<div class="bg-white shadow-sm p-4 mb-4" style="border-radius: 4px;">
    <h6 class="text-uppercase fw-bold text-muted small mb-4">Order Journey</h6>
    <div class="position-relative w-100 d-flex justify-content-between align-items-center">
        @php
            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'completed'];
            $currentIdx = array_search($order->status, $statuses);
            if ($currentIdx === false) $currentIdx = -1;
        @endphp
        
        <div class="position-absolute" style="top: 15px; left: 10%; right: 10%; height: 2px; background: #e9ecef; z-index: 1;"></div>
        <div class="position-absolute bg-primary" style="top: 15px; left: 10%; height: 2px; width: {{ max(0, ($currentIdx / (count($statuses) - 1)) * 80) }}%; z-index: 2; transition: width .5s;"></div>

        @foreach($statuses as $idx => $st)
            <div class="position-relative text-center" style="z-index: 3; width: 60px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 {{ $idx <= $currentIdx ? 'bg-primary text-white border-primary' : 'bg-white text-muted border-secondary-subtle' }} border border-2" style="width: 30px; height: 30px; font-size: 14px;">
                    @if($idx < $currentIdx) <i class="bi bi-check-lg"></i> @else {{ $idx + 1 }} @endif
                </div>
                <div class="text-uppercase fw-bold {{ $idx <= $currentIdx ? 'text-primary' : 'text-muted' }}" style="font-size: 10px;">{{ $st }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="row g-4">
    {{-- Order Items --}}
    <div class="col-lg-8">
        <div class="bg-white shadow-sm" style="border-radius: 4px;">
            <div class="p-3 border-bottom"><h6 class="fw-bold mb-0">Order Items</h6></div>
            <div class="list-group list-group-flush">
                @foreach($order->items as $item)
                <div class="list-group-item p-4 d-flex align-items-center">
                    <div class="border rounded me-3 flex-shrink-0 bg-light d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; overflow: hidden;">
                        @if($item->product && $item->product->primary_image)
                        <img src="{{ asset('storage/' . $item->product->primary_image) }}" class="w-100 h-100 object-fit-cover">
                        @else
                        <i class="bi bi-image text-muted opacity-50"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h6 class="fw-bold mb-1 text-truncate" style="font-size: 13px;">{{ $item->product_name }}</h6>
                        <p class="text-muted small mb-0">Qty: {{ $item->quantity }} &times; ৳{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <div class="fw-bold ms-3" style="font-size: 14px;">৳{{ number_format($item->subtotal, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Order Summary sidebar --}}
    <div class="col-lg-4">
        {{-- Payment summary --}}
        <div class="bg-white shadow-sm p-4 mb-4" style="border-radius: 4px; font-size: 13px;">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Payment Summary</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span class="fw-medium">৳{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Shipping</span>
                <span class="fw-medium">৳{{ number_format($order->shipping_cost, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Tax</span>
                <span class="fw-medium">৳{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="d-flex justify-content-between mb-2 text-danger">
                <span>Discount</span>
                <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <hr class="my-3">
            <div class="d-flex justify-content-between mb-0">
                <span class="fw-bold" style="font-size: 16px;">Total</span>
                <span class="fw-bold" style="font-size: 18px; color: var(--lm-primary);">৳{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Shipping info --}}
        <div class="bg-white shadow-sm p-4" style="border-radius: 4px; font-size: 13px;">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Delivery & Payment</h6>
            
            <div class="mb-3">
                <div class="text-muted text-uppercase fw-bold small mb-1" style="font-size: 10px; letter-spacing: 1px;">Shipping Address</div>
                <div class="fw-medium">{{ $order->shipping_address['address'] ?? 'N/A' }}, {{ $order->shipping_address['city'] ?? '' }}</div>
            </div>
            
            <div>
                <div class="text-muted text-uppercase fw-bold small mb-1" style="font-size: 10px; letter-spacing: 1px;">Payment Method</div>
                <div class="fw-medium text-uppercase">{{ $order->payment_method }} <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} ms-1">{{ $order->payment_status }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
