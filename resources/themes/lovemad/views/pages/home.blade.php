@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', \App\Models\Setting::get('site_description', 'Best online shopping in Bangladesh. Discover amazing deals on electronics, fashion & more.'))

@section('content')

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success border-0 shadow-sm rounded-0 d-flex align-items-center py-2" style="font-size: 13px;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    </div>
@endif
@if(session('info'))
    <div class="container mt-3">
        <div class="alert alert-info border-0 shadow-sm rounded-0 py-2" style="font-size: 13px;" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
        </div>
    </div>
@endif

{{-- =================== HERO BANNER SECTION =================== --}}
<section class="mb-3" style="background: linear-gradient(135deg, var(--lm-primary) 0%, #ff8c42 100%);">
    <div class="container py-4 py-md-5">
        <div class="row align-items-center">
            <div class="col-md-7 text-white text-center text-md-start mb-4 mb-md-0">
                <div class="d-inline-block mb-2 px-3 py-1 rounded-pill" style="background: rgba(255,255,255,.2); font-size: 12px; font-weight: 600;">
                    <i class="bi bi-lightning-charge-fill me-1"></i> MEGA DEALS
                </div>
                <h1 class="fw-bold mb-3" style="font-size: clamp(28px, 4vw, 48px); line-height: 1.2;">
                    {{ themeValue('hero_title', 'Mega Sale is Live!') }}
                </h1>
                <p class="mb-4 opacity-75" style="font-size: 16px;">
                    {{ themeValue('hero_subtitle', 'Up to 70% OFF on thousands of products') }}
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                    <a href="{{ route('products') }}" class="btn btn-light btn-lg fw-bold px-5" style="border-radius: 2px; font-size: 14px; color: var(--lm-primary);">
                        SHOP NOW
                    </a>
                    <a href="{{ route('stores.index') }}" class="btn btn-outline-light btn-lg fw-medium px-4" style="border-radius: 2px; font-size: 14px;">
                        View Stores
                    </a>
                    <a href="{{ route('register.vendor') }}" class="btn btn-outline-light btn-lg fw-medium px-4" style="border-radius: 2px; font-size: 14px;">
                        Sell with us
                    </a>
                </div>
            </div>
            <div class="col-md-5 text-center">
                {{-- Feature highlights --}}
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-white text-center" style="backdrop-filter: blur(10px);">
                            <i class="bi bi-truck fs-3 d-block mb-2"></i>
                            <div class="fw-bold" style="font-size: 12px;">FREE DELIVERY</div>
                            <div style="font-size: 11px; opacity: .8;">Over ৳999</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-white text-center" style="backdrop-filter: blur(10px);">
                            <i class="bi bi-shield-check fs-3 d-block mb-2"></i>
                            <div class="fw-bold" style="font-size: 12px;">AUTHENTIC</div>
                            <div style="font-size: 11px; opacity: .8;">100% Genuine</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-white text-center" style="backdrop-filter: blur(10px);">
                            <i class="bi bi-arrow-repeat fs-3 d-block mb-2"></i>
                            <div class="fw-bold" style="font-size: 12px;">EASY RETURN</div>
                            <div style="font-size: 11px; opacity: .8;">7 Days Return</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-white text-center" style="backdrop-filter: blur(10px);">
                            <i class="bi bi-credit-card fs-3 d-block mb-2"></i>
                            <div class="fw-bold" style="font-size: 12px;">SECURE PAY</div>
                            <div style="font-size: 11px; opacity: .8;">bKash / COD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =================== CATEGORIES STRIP =================== --}}
<section class="bg-white py-3 mb-3 shadow-sm">
    <div class="container">
        <div class="d-flex flex-nowrap overflow-auto custom-scrollbar gap-2 pb-1" style="scroll-snap-type: x mandatory;">
            @foreach($categories as $category)
                <a href="{{ route('products', ['category' => $category->slug]) }}"
                   class="text-decoration-none flex-shrink-0 text-center px-3 py-2 rounded-3 cat-pill"
                   style="scroll-snap-align: start; min-width: 100px;">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle" style="width: 48px; height: 48px; background: var(--lm-orange-soft);  color: var(--lm-primary); font-size: 20px;">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <div class="fw-medium text-dark" style="font-size: 12px; white-space: nowrap;">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- =================== FLASH SALE / FEATURED PRODUCTS =================== --}}
<section class="container mb-3">
    <div class="section-title-bar">
        <h2><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Flash Sale</h2>
        <a href="{{ route('products') }}" class="text-decoration-none small fw-bold" style="color: var(--lm-primary);">
            SHOP MORE <i class="bi bi-chevron-right ms-1" style="font-size: 10px;"></i>
        </a>
    </div>
    <div class="bg-white p-3">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2">
            @foreach($featuredProducts as $product)
                <div class="col">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =================== PROMOTIONAL BANNER =================== --}}
<section class="container mb-3">
    <div class="row g-2">
        <div class="col-md-8">
            <div class="position-relative overflow-hidden h-100" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); min-height: 200px; border-radius: 4px;">
                <div class="position-absolute top-50 start-0 translate-middle-y p-4 p-md-5 text-white" style="max-width: 60%; z-index: 2;">
                    <span class="badge bg-warning text-dark fw-bold mb-2" style="font-size: 11px;">LIMITED OFFER</span>
                    <h3 class="fw-bold mb-2" style="font-size: clamp(18px, 2.5vw, 28px);">Upgrade Your Setup Today</h3>
                    <p class="mb-3 d-none d-md-block" style="font-size: 13px; opacity: .8;">Up to 30% off on premium electronics. Valid till stock lasts.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary btn-sm fw-bold px-4" style="border-radius: 2px;">Claim Discount</a>
                </div>
                <div class="position-absolute end-0 top-0 bottom-0" style="width: 50%; background: linear-gradient(135deg, transparent 0%, rgba(248,86,6,.3) 100%);"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex flex-column gap-2 h-100">
                <div class="flex-fill position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--lm-primary) 0%, #ff8c42 100%); min-height: 96px; border-radius: 4px;">
                    <div class="p-3 text-white">
                        <div class="fw-bold" style="font-size: 14px;">Free Delivery</div>
                        <div style="font-size: 12px; opacity: .8;">On orders over ৳999</div>
                    </div>
                    <i class="bi bi-truck position-absolute end-0 bottom-0 pe-3 pb-2" style="font-size: 40px; color: rgba(255,255,255,.2);"></i>
                </div>
                <div class="flex-fill position-relative overflow-hidden" style="background: linear-gradient(135deg, #00a862 0%, #00c471 100%); min-height: 96px; border-radius: 4px;">
                    <div class="p-3 text-white">
                        <div class="fw-bold" style="font-size: 14px;">Sell on Mondals</div>
                        <div style="font-size: 12px; opacity: .8;">Start earning today</div>
                    </div>
                    <i class="bi bi-shop position-absolute end-0 bottom-0 pe-3 pb-2" style="font-size: 40px; color: rgba(255,255,255,.2);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =================== NEW ARRIVALS / JUST FOR YOU =================== --}}
<section class="container mb-4">
    <div class="section-title-bar">
        <h2><i class="bi bi-stars text-primary me-2"></i>Just For You</h2>
        <a href="{{ route('products', ['sort' => 'latest']) }}" class="text-decoration-none small fw-bold" style="color: var(--lm-primary);">
            VIEW MORE <i class="bi bi-chevron-right ms-1" style="font-size: 10px;"></i>
        </a>
    </div>
    <div class="bg-white p-3">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2">
            @foreach($newArrivals as $product)
                <div class="col">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('styles')
<style>
    .cat-pill { transition: all .2s; }
    .cat-pill:hover { background: var(--lm-orange-soft); }
    .cat-pill:hover div:first-child { transform: scale(1.1); transition: transform .2s; }
</style>
@endpush

@endsection
