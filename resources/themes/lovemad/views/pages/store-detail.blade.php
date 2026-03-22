@extends('layouts.app')

@section('title', $vendor->store_name)

@section('content')
<div class="container py-3">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('stores.index') }}" class="text-decoration-none text-muted">Stores</a></li>
            <li class="breadcrumb-item active">{{ $vendor->store_name }}</li>
        </ol>
    </nav>

    {{-- Store Header Banner --}}
    <div class="position-relative overflow-hidden mb-3 text-white" style="background: linear-gradient(135deg, var(--lm-secondary) 0%, #1a1a2e 100%); border-radius: 4px;">
        <div class="position-absolute end-0 top-0 bottom-0" style="width: 40%; background: linear-gradient(135deg, transparent, rgba(248,86,6,.2));"></div>
        <div class="position-relative p-4 p-md-5 d-flex flex-column flex-md-row align-items-center" style="z-index: 2;">
            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0 me-md-4 mb-3 mb-md-0"
                 style="width: 80px; height: 80px; font-size: 32px; font-weight: 900; color: var(--lm-primary);">
                {{ substr($vendor->store_name, 0, 1) }}
            </div>
            <div class="text-center text-md-start flex-grow-1">
                <h1 class="fw-bold mb-1" style="font-size: 24px;">{{ $vendor->store_name }}</h1>
                <p class="mb-3 opacity-75" style="font-size: 13px;">Official Retail Partner</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start" style="font-size: 13px;">
                    <span class="d-inline-flex align-items-center">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <strong>{{ number_format($vendor->products->flatMap->reviews->avg('rating') ?? 0, 1) }}</strong>
                        <span class="opacity-75 ms-1">Average Rating</span>
                    </span>
                    <span class="d-inline-flex align-items-center rounded-pill px-3 py-1" style="background: rgba(255,255,255,.1);">
                        Joined {{ $vendor->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="section-title-bar mb-0">
        <h2><i class="bi bi-grid me-2"></i>Store Catalog</h2>
        <span class="text-muted" style="font-size: 13px;">{{ $products->total() }} Products</span>
    </div>

    {{-- Products --}}
    @if($products->isEmpty())
        <div class="bg-white text-center py-5" style="border-radius: 0 0 4px 4px;">
            <i class="bi bi-box-seam display-4 d-block mb-3" style="opacity: .2;"></i>
            <h5 class="fw-bold">No products available yet.</h5>
        </div>
    @else
        <div class="bg-white p-3">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2">
                @foreach($products as $product)
                    <div class="col">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
