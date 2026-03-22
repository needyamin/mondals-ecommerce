@extends('layouts.customer')
@section('title', 'My Orders')

@section('customer_content')
<div class="bg-white shadow-sm mb-4 p-4 border-bottom d-flex justify-content-between align-items-center" style="border-radius: 4px;">
    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>Order History</h5>
    <span class="badge bg-secondary rounded-pill">{{ $orders->total() }} Orders</span>
</div>

<div class="bg-white shadow-sm" style="border-radius: 4px;">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle" style="font-size: 13px;">
            <thead class="bg-light text-muted">
                <tr>
                    <th class="ps-4 fw-medium border-0 py-3">Order #</th>
                    <th class="fw-medium border-0 py-3">Date</th>
                    <th class="fw-medium border-0 py-3">Payment</th>
                    <th class="fw-medium border-0 py-3">Status</th>
                    <th class="text-end fw-medium border-0 py-3">Total</th>
                    <th class="text-end pe-4 fw-medium border-0 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="ps-4 fw-bold">#{{ $order->order_number }}</td>
                    <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $order->payment_status === 'paid' ? 'success' : 'danger' }} border border-{{ $order->payment_status === 'paid' ? 'success' : 'danger' }}-subtle">{{ ucfirst($order->payment_status) }}</span>
                    </td>
                    <td>
                        @if($order->status === 'completed')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Completed</span>
                        @elseif($order->status === 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Pending</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold" style="color: var(--lm-primary);">৳{{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size: 11px;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">You haven't placed any orders yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-3 border-top d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
