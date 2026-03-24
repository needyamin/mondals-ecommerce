@extends('layouts.app')

@section('title', 'Registered Stores')

@section('content')
<div class="container py-3">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active">Stores</li>
        </ol>
    </nav>

    <div class="section-title-bar mb-0">
        <h2><i class="bi bi-shop-window me-2"></i>Official Stores</h2>
        <span class="text-muted" style="font-size: 13px;">{{ $vendors->total() }} stores</span>
    </div>
    <div class="bg-white p-3 mb-3">
        <p class="text-muted mb-0" style="font-size: 13px;">Explore exclusive collections from verified premium vendors on Mondals.</p>
    </div>

    @if($vendors->isEmpty())
        <div class="bg-white text-center py-5 shadow-sm" style="border-radius: 4px;">
            <i class="bi bi-shop display-4 d-block mb-3" style="opacity: .2;"></i>
            <h5 class="fw-bold">No active vendors found.</h5>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            @foreach($vendors as $vendor)
                <div class="col">
                    <div class="bg-white shadow-sm p-4 h-100 d-flex flex-column card-hover" style="border-radius: 4px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                 style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--lm-primary), var(--lm-accent)); color: #fff; font-size: 22px; font-weight: 700;">
                                {{ substr($vendor->store_name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" style="font-size: 16px;">{{ $vendor->store_name }}</h5>
                                <span class="text-muted" style="font-size: 12px;">Joined {{ $vendor->created_at->format('M Y') }}</span>
                            </div>
                        </div>

                        <p class="text-muted mb-3 flex-grow-1" style="font-size: 13px;">
                            {{ \Illuminate\Support\Str::limit($vendor->description ?? 'Premium vendor committed to quality products.', 100) }}
                        </p>

                        <div class="d-flex border-top pt-3 mb-3">
                            <div class="text-center w-50 border-end">
                                <div class="fw-bold" style="font-size: 18px; color: var(--lm-primary);">{{ $vendor->products_count }}</div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Products</div>
                            </div>
                            <div class="text-center w-50">
                                <div class="fw-bold" style="font-size: 18px; color: var(--lm-star);">
                                    @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                                    {{ number_format($vendor->products->avg(fn($p) => $p->reviews->avg('rating')) ?? 0, 1) }}
                                    @else
                                    —
                                    @endif
                                </div>
                                <div class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Rating</div>
                            </div>
                        </div>

                        <a href="{{ route('stores.show', $vendor->slug) }}" class="btn btn-outline-primary btn-sm w-100 fw-bold" style="border-radius: 2px; font-size: 13px;">
                            Visit Store
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $vendors->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
