@extends('layouts.customer')
@section('title', 'My Wishlist')

@section('customer_content')
<div class="bg-white shadow-sm mb-4 p-4 border-bottom d-flex justify-content-between align-items-center" style="border-radius: 4px;">
    <h5 class="fw-bold mb-0"><i class="bi bi-heart-fill text-danger me-2"></i>Saved Products</h5>
    <span class="badge bg-danger rounded-pill">{{ $wishlistItems->total() }} items</span>
</div>

@if($wishlistItems->count() > 0)
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        @foreach($wishlistItems as $item)
        <div class="col">
            <div class="bg-white shadow-sm h-100 d-flex flex-column product-card-lm position-relative" style="border-radius: 4px; overflow: hidden;">
                <div class="position-relative bg-light" style="aspect-ratio: 1/1;">
                    @if($item->product->primary_image)
                    <img src="{{ $item->product->display_image }}" alt="" class="w-100 h-100 object-fit-cover">
                    @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted opacity-25">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                    
                    {{-- Remove Button --}}
                    <form action="{{ route('customer.wishlist.toggle') }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" title="Remove from wishlist">
                            <i class="bi bi-x-lg" style="font-size: 14px;"></i>
                        </button>
                    </form>
                </div>
                
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <h6 class="fw-bold text-truncate mb-2" style="font-size: 13px;">{{ $item->product->name }}</h6>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary" style="font-size: 16px;">৳{{ number_format($item->product->price, 0) }}</span>
                        <a href="{{ route('product.detail', $item->product->slug) }}" class="btn btn-outline-primary btn-sm" style="font-size: 11px; border-radius: 2px;">View</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4 p-3 bg-white shadow-sm d-flex justify-content-center" style="border-radius: 4px;">
        {{ $wishlistItems->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="bg-white shadow-sm p-5 text-center" style="border-radius: 4px;">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 bg-light text-muted" style="width: 80px; height: 80px; font-size: 32px;">
            <i class="bi bi-heartbreak"></i>
        </div>
        <h4 class="fw-bold mb-2">No Saved Items</h4>
        <p class="text-muted small mb-4">Save products you're interested in for quick access later.</p>
        <a href="{{ route('products') }}" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 2px; font-size: 14px;">Browse Products</a>
    </div>
@endif
@endsection
