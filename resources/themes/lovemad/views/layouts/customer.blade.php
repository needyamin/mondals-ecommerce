@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
             <div class="bg-white shadow-sm p-4 text-center mb-3" style="border-radius: 4px;">
                 <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background: var(--lm-orange-soft); color: var(--lm-primary); font-size: 28px; font-weight: 700;">
                     {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                 </div>
                 <h6 class="fw-bold mb-1">{{ auth()->user()->name }}</h6>
                 <p class="text-muted mb-0" style="font-size: 13px;">{{ auth()->user()->email }}</p>
             </div>

             <div class="bg-white shadow-sm" style="border-radius: 4px; overflow: hidden;">
                 <div class="list-group list-group-flush customer-nav-list" style="font-size: 14px;">
                     <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('customer.dashboard') ? 'active fw-bold' : 'text-muted' }} py-3">
                         <i class="bi bi-grid-fill me-2"></i> Dashboard
                     </a>
                     <a href="{{ route('customer.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('customer.orders.*') ? 'active fw-bold' : 'text-muted' }} py-3">
                         <i class="bi bi-box-seam me-2"></i> My Orders
                     </a>
                     <a href="{{ route('customer.wishlist.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('customer.wishlist.*') ? 'active fw-bold' : 'text-muted' }} py-3">
                         <i class="bi bi-heart-fill me-2"></i> Wishlist
                     </a>
                     <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('customer.profile') ? 'active fw-bold' : 'text-muted' }} py-3">
                         <i class="bi bi-person-gear me-2"></i> Account Settings
                     </a>
                     <div class="list-group-item list-group-item-action py-3 text-muted">
                         <form action="{{ route('logout') }}" method="POST" class="m-0">
                             @csrf
                             <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none shadow-none w-100 text-start" style="font-size: 14px;">
                                 <i class="bi bi-box-arrow-right me-2"></i> Log Out
                             </button>
                         </form>
                     </div>
                 </div>
             </div>
        </div>

        {{-- Main Content Space --}}
        <div class="col-lg-9">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-0 py-2 mb-3" style="font-size: 13px;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            @yield('customer_content')
        </div>
    </div>
</div>

@push('styles')
<style>
.customer-nav-list .list-group-item {
    border: none;
    border-bottom: 1px solid #f5f5f5;
    border-left: 3px solid transparent;
    transition: all 0.2s;
}
.customer-nav-list .list-group-item:last-child {
    border-bottom: none;
}
.customer-nav-list .list-group-item.active {
    background-color: var(--lm-orange-soft) !important;
    color: var(--lm-primary) !important;
    border-left: 3px solid var(--lm-primary) !important;
}
</style>
@endpush
@endsection
