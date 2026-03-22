@extends('layouts.customer')
@section('title', 'Dashboard')

@section('customer_content')
<div class="row g-4 mb-4">
    {{-- Total Orders --}}
    <div class="col-md-4">
        <div class="bg-white shadow-sm p-4 text-center h-100" style="border-radius: 4px;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px; background: rgba(13,110,253,0.1); color: #0d6efd; font-size: 20px;">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['total_orders'] }}</h3>
            <p class="text-muted small text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Total Orders</p>
        </div>
    </div>
    {{-- Pending Orders --}}
    <div class="col-md-4">
        <div class="bg-white shadow-sm p-4 text-center h-100" style="border-radius: 4px;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px; background: rgba(253,126,20,0.1); color: #fd7e14; font-size: 20px;">
                <i class="bi bi-clock-history"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['pending_orders'] }}</h3>
            <p class="text-muted small text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Pending</p>
        </div>
    </div>
    {{-- Wishlist Items --}}
    <div class="col-md-4">
        <div class="bg-white shadow-sm p-4 text-center h-100" style="border-radius: 4px;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px; background: rgba(220,53,69,0.1); color: #dc3545; font-size: 20px;">
                <i class="bi bi-heart"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['wishlist_count'] }}</h3>
            <p class="text-muted small text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Wishlist</p>
        </div>
    </div>
</div>

<div class="bg-white shadow-sm" style="border-radius: 4px;">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-uppercase">Recent Orders</h6>
        <a href="{{ route('customer.orders.index') }}" class="text-decoration-none" style="font-size: 13px; font-weight: 500;">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle" style="font-size: 13px;">
            <thead class="bg-light text-muted">
                <tr>
                    <th class="ps-4 fw-medium border-0 py-3">Order #</th>
                    <th class="fw-medium border-0 py-3">Date</th>
                    <th class="fw-medium border-0 py-3">Status</th>
                    <th class="text-end fw-medium border-0 py-3">Total</th>
                    <th class="text-end pe-4 fw-medium border-0 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td class="ps-4 fw-bold">#{{ $order->order_number }}</td>
                    <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        @if($order->status === 'completed')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Completed</span>
                        @elseif($order->status === 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Pending</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold">৳{{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size: 11px;">Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No recent orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
