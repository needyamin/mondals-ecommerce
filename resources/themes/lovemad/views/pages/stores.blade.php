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

    <form method="get" action="{{ route('stores.index') }}" class="bg-white p-3 mb-3 shadow-sm d-flex flex-column flex-md-row flex-wrap gap-2 align-items-stretch align-items-md-end" style="border-radius: 4px;">
        <div class="flex-grow-1" style="min-width: 200px;">
            <label class="form-label small text-muted mb-1">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Store name, city, description…" style="border-radius: 2px;">
        </div>
        <div style="min-width: 160px;">
            <label class="form-label small text-muted mb-1">Sort</label>
            <select class="form-select form-select-sm" name="sort" style="border-radius: 2px;">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                <option value="name" @selected(request('sort') === 'name')>A–Z</option>
                <option value="name_desc" @selected(request('sort') === 'name_desc')>Z–A</option>
                <option value="products" @selected(request('sort') === 'products')>Most products</option>
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" style="border-radius: 2px;">Search</button>
            <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 2px;">Clear</a>
        </div>
    </form>

    <p class="text-muted mb-3" style="font-size: 12px;">Showing {{ $vendors->firstItem() ?? 0 }}–{{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }}</p>

    @if($vendors->isEmpty())
        <div class="bg-white text-center py-5 shadow-sm" style="border-radius: 4px;">
            <i class="bi bi-shop display-4 d-block mb-3" style="opacity: .2;"></i>
            <h5 class="fw-bold">No stores found</h5>
            <a href="{{ route('stores.index') }}" class="btn btn-primary btn-sm mt-2">Show all</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            @foreach($vendors as $vendor)
                @php($cardLoc = collect([$vendor->city, $vendor->state, $vendor->country])->filter(fn ($v) => filled($v))->implode(' · '))
                <div class="col">
                    <div class="bg-white shadow-sm h-100 d-flex flex-column card-hover" style="border-radius: 4px;">
                        <div class="position-relative flex-shrink-0">
                            <div class="position-relative overflow-hidden" style="height: 160px; border-radius: 4px 4px 0 0; background: linear-gradient(135deg, var(--lm-secondary), #1a1a2e);">
                                @if($vendor->display_banner)
                                <img src="{{ $vendor->display_banner }}" alt="" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">
                                @endif
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,.45), transparent); pointer-events: none;"></div>
                            </div>
                            <div class="position-absolute start-50" style="bottom: 0; transform: translate(-50%, 50%); z-index: 3;">
                                <div class="rounded-circle overflow-hidden border border-3 border-white bg-white shadow d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                    <img src="{{ $vendor->display_image }}" alt="{{ $vendor->store_name }}" class="w-100 h-100" style="object-fit: contain; object-position: center; padding: 2px;">
                                </div>
                            </div>
                        </div>
                        <div class="p-3 pt-4 flex-grow-1 d-flex flex-column text-center">
                            <h5 class="fw-bold mt-2 mb-1" style="font-size: 16px;">{{ $vendor->store_name }}</h5>
                            @if(filled($cardLoc))
                            <div class="text-muted small text-truncate px-1" title="{{ $cardLoc }}"><i class="bi bi-geo-alt"></i> {{ $cardLoc }}</div>
                            @endif
                            <p class="text-muted small mt-2 mb-0 flex-grow-1 text-start" style="font-size: 12px; line-height: 1.45;">
                                {{ \Illuminate\Support\Str::limit($vendor->description ?? 'Verified partner on Mondals.', 120) }}
                            </p>
                            <div class="d-flex gap-2 mt-3 pt-2 border-top">
                                <div class="flex-fill text-center py-2 rounded bg-light">
                                    <div class="fw-bold" style="font-size: 18px; color: var(--lm-primary);">{{ $vendor->published_products_count }}</div>
                                    <div class="text-muted text-uppercase" style="font-size: 9px; letter-spacing: .06em;">Products</div>
                                </div>
                                <div class="flex-fill text-center py-2 rounded bg-light">
                                    @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                                    <div class="fw-bold" style="font-size: 18px; color: var(--lm-star);">{{ number_format($vendor->products->avg(fn($p) => $p->reviews->avg('rating')) ?? 0, 1) }}</div>
                                    @else
                                    <div class="fw-bold text-muted" style="font-size: 18px;">—</div>
                                    @endif
                                    <div class="text-muted text-uppercase" style="font-size: 9px; letter-spacing: .06em;">Rating</div>
                                </div>
                            </div>
                            <span class="text-muted small d-block mb-2">Joined {{ $vendor->created_at->format('M Y') }}</span>
                            <a href="{{ route('stores.show', $vendor->slug) }}" class="btn btn-outline-primary btn-sm w-100 fw-bold mt-auto" style="border-radius: 2px; font-size: 13px;">Visit store</a>
                        </div>
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
