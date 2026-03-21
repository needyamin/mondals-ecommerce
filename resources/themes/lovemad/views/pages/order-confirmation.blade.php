@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-heart display-1 text-primary animate-pulse"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Order Confirmed!</h2>
                    <p class="text-muted mb-4 lead">Thank you for shopping with love. Your order <strong>#{{ $order->id }}</strong> has been placed successfully.</p>
                    
                    <div class="bg-light p-4 rounded-4 text-start mb-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Status:</span>
                            <span class="badge rounded-pill bg-success px-3">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Total Amount:</span>
                            <span class="fw-bold">৳{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="small text-muted">Payment:</span>
                            <span class="fw-bold">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-primary btn-lg rounded-pill fw-bold py-3 shadow">View Order Status</a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill fw-bold py-3">Browse More Collections</a>
                    </div>
                </div>
            </div>
            
            <p class="text-muted small">A confirmation email has been sent to {{ auth()->user()->email ?? 'your email' }}.</p>
        </div>
    </div>
</div>

<style>
    @keyframes pulse-heart {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .animate-pulse { animation: pulse-heart 1.5s infinite ease-in-out; }
</style>
@endsection
