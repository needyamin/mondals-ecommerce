@extends('layouts.app')

@section('title', $vendor->store_name)

@section('content')
@php($psort = request('sort', '-created_at'))
<div class="container py-3">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('stores.index') }}" class="text-decoration-none text-muted">Stores</a></li>
            <li class="breadcrumb-item active">{{ $vendor->store_name }}</li>
        </ol>
    </nav>

    {{-- Store Header Banner --}}
    <div class="position-relative overflow-hidden mb-3 text-white" style="background: linear-gradient(135deg, var(--lm-secondary) 0%, #1a1a2e 100%); border-radius: 4px;@if($vendor->display_banner) background-image: linear-gradient(135deg, rgba(0,0,0,.55), rgba(26,26,46,.75)), url('{{ $vendor->display_banner }}'); background-size: cover; background-position: center; @endif">
        <div class="position-absolute end-0 top-0 bottom-0" style="width: 40%; background: linear-gradient(135deg, transparent, rgba(248,86,6,.2));"></div>
        <div class="position-relative p-4 p-md-5 d-flex flex-column flex-md-row align-items-center" style="z-index: 2;">
            <div class="rounded-circle bg-white overflow-hidden d-flex align-items-center justify-content-center flex-shrink-0 me-md-4 mb-3 mb-md-0"
                 style="width: 80px; height: 80px;">
                <img src="{{ $vendor->display_image }}" alt="" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="text-center text-md-start flex-grow-1">
                <h1 class="fw-bold mb-1" style="font-size: 24px;">{{ $vendor->store_name }}</h1>
                <p class="mb-3 opacity-75" style="font-size: 13px;">Official Retail Partner</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start" style="font-size: 13px;">
                    @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                    <span class="d-inline-flex align-items-center">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <strong>{{ number_format($vendor->products->flatMap->reviews->avg('rating') ?? 0, 1) }}</strong>
                        <span class="opacity-75 ms-1">Avg. rating</span>
                    </span>
                    @endif
                    <span class="d-inline-flex align-items-center rounded-pill px-3 py-1" style="background: rgba(255,255,255,.1);">
                        {{ $products->total() }} products
                    </span>
                    <span class="d-inline-flex align-items-center rounded-pill px-3 py-1" style="background: rgba(255,255,255,.1);">
                        Joined {{ $vendor->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(filled($vendor->description) || filled($vendor->email) || filled($vendor->phone) || count($vendor->address_lines))
    <div class="mb-3">
        <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center py-2 fw-bold shadow-sm" style="border-radius: 4px;" type="button" data-bs-toggle="collapse" data-bs-target="#storeInfoCollapse" aria-expanded="false" aria-controls="storeInfoCollapse">
            <span><i class="bi bi-shop-window me-2"></i>Store information <span class="fw-normal text-muted small">(about, contact, location)</span></span>
            <i class="bi bi-chevron-down"></i>
        </button>
        <div class="collapse" id="storeInfoCollapse">
            <div class="row g-3 mt-2">
                @if(filled($vendor->description))
                <div class="col-12 col-lg-8">
                    <div class="bg-white shadow-sm h-100 p-4 border-start border-4 border-primary" style="border-radius: 4px;">
                        <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 11px; letter-spacing: .06em;"><i class="bi bi-info-circle me-1"></i>About</div>
                        <p class="mb-0 text-secondary" style="font-size: 14px; white-space: pre-line; line-height: 1.6;">{{ $vendor->description }}</p>
                    </div>
                </div>
                @endif
                <div class="col-12 @if(filled($vendor->description)) col-lg-4 @endif">
                    <div class="bg-white shadow-sm p-4 h-100" style="border-radius: 4px;">
                        @if(filled($vendor->email) || filled($vendor->phone))
                        <div class="text-muted text-uppercase fw-bold mb-3" style="font-size: 11px; letter-spacing: .06em;">Contact</div>
                        @if(filled($vendor->email))
                        <div class="mb-3 pb-3 border-bottom"><i class="bi bi-envelope me-2 text-primary"></i><a href="mailto:{{ $vendor->email }}" class="text-break small">{{ $vendor->email }}</a></div>
                        @endif
                        @if(filled($vendor->phone))
                        <div class="mb-3 @if(!count($vendor->address_lines)) mb-0 @else pb-3 border-bottom @endif"><i class="bi bi-telephone me-2 text-primary"></i><a href="tel:{{ preg_replace('/\s+/', '', $vendor->phone) }}" class="small">{{ $vendor->phone }}</a></div>
                        @endif
                        @endif
                        @if(count($vendor->address_lines))
                        <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 11px; letter-spacing: .06em;">Location</div>
                        <p class="mb-0 text-secondary small" style="white-space: pre-line; line-height: 1.55;">{{ implode("\n", $vendor->address_lines) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="section-title-bar mb-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h2 class="mb-0"><i class="bi bi-grid me-2"></i>Catalog</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted" style="font-size: 13px;">{{ $products->total() }} items</span>
            <form method="get" class="d-flex align-items-center gap-1">
                <label class="small text-muted mb-0">Sort</label>
                <select name="sort" class="form-select form-select-sm" style="width: auto; min-width: 11rem; border-radius: 2px;" onchange="this.form.submit()">
                    <option value="-created_at" @selected($psort === '-created_at')>Newest</option>
                    <option value="price" @selected($psort === 'price')>Price ↑</option>
                    <option value="-price" @selected($psort === '-price')>Price ↓</option>
                    <option value="name" @selected($psort === 'name')>Name A–Z</option>
                    <option value="-name" @selected($psort === '-name')>Name Z–A</option>
                </select>
            </form>
        </div>
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
