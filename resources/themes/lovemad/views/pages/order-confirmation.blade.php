@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Success Header --}}
            <div class="bg-white shadow-sm text-center p-5 mb-3" style="border-radius: 4px;">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 80px; height: 80px; background: #e8f5e9;">
                    <i class="bi bi-check-lg" style="font-size: 40px; color: var(--lm-success);"></i>
                </div>
                <h2 class="fw-bold mb-2">Order Confirmed!</h2>
                <p class="text-muted mb-1">Thank you for your purchase.</p>
                <span class="d-inline-block px-3 py-1 rounded mt-2" style="background: var(--lm-orange-soft); color: var(--lm-primary); font-size: 14px; font-weight: 700;">
                    Order #{{ $order->order_number }}
                </span>
            </div>

            {{-- Order Details --}}
            <div class="bg-white shadow-sm mb-3" style="border-radius: 4px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="font-size: 13px;">
                    <span class="fw-bold">Order Items</span>
                    <span class="text-muted">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                @foreach($order->items as $item)
                    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="border rounded overflow-hidden flex-shrink-0 me-3" style="width: 52px; height: 52px;">
                                @if($item->product && $item->product->primary_image)
                                    <img src="{{ $item->product->display_image }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center"><i class="bi bi-image text-muted"></i></div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-medium" style="font-size: 13px;">{{ $item->product_name }}</div>
                                <div class="text-muted" style="font-size: 11px;">Qty: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</div>
                            </div>
                        </div>
                        <span class="fw-bold" style="font-size: 14px;">৳{{ number_format($item->total, 2) }}</span>
                    </div>
                @endforeach
                <div class="p-3 d-flex justify-content-between align-items-center" style="background: #f9f9f9;">
                    <div>
                        <div class="fw-bold" style="font-size: 14px;">Grand Total</div>
                        <div class="text-muted" style="font-size: 12px;">
                            Payment: <span class="text-uppercase fw-medium">{{ $order->payment_method }}</span>
                            &middot; Status: <span class="badge rounded-pill bg-warning text-dark" style="font-size: 10px;">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    <span class="fw-bold" style="font-size: 20px; color: var(--lm-primary);">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            {{-- Delivery Address --}}
            <div class="bg-white shadow-sm p-4 mb-3" style="border-radius: 4px;">
                <h6 class="fw-bold mb-3 d-flex align-items-center" style="font-size: 14px;">
                    <i class="bi bi-geo-alt text-primary me-2"></i> Delivering To
                </h6>
                <div style="font-size: 13px; color: #555;">
                    <div class="fw-bold mb-1">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</div>
                    <div>{{ $order->shipping_address_line_1 }}</div>
                    <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip_code }}</div>
                    <div>{{ $order->shipping_country }}</div>
                    <div class="mt-2 text-muted">Phone: {{ $order->shipping_phone }}</div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('products') }}" class="btn btn-primary fw-bold px-5" style="border-radius: 2px; font-size: 14px;">Continue Shopping</a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary fw-medium px-4" style="border-radius: 2px; font-size: 14px;">Go to Homepage</a>
            </div>

        </div>
    </div>
</div>
@push('scripts')
    @if(\App\Models\Plugin::isActiveSlug('marketing-tracking'))
    @include('marketing-tracking::partials.marketing-purchase', ['order' => $order])
    @endif
@endpush
@endsection
