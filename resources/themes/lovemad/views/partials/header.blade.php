{{-- Daraz-Style Top Bar --}}
<div class="py-1" style="background: var(--lm-secondary); color: #ccc; font-size: 12px;">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-telephone me-1"></i> Helpline: <a href="tel:+8801878578504" class="text-light text-decoration-none">01878578504</a></span>
            <span class="d-none d-md-inline">|</span>
            <span class="d-none d-md-inline"><i class="bi bi-truck me-1"></i> Free delivery on orders over ৳999</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            @auth
                @if(auth()->user()->hasRole('vendor'))
                    <a href="{{ route('vendor.dashboard') }}" class="text-light text-decoration-none small">Sell on Mondals</a>
                @endif
            @else
                <a href="{{ route('register') }}" class="text-light text-decoration-none small">Sell on Mondals</a>
            @endauth
            <span class="text-secondary">|</span>
            <a href="#" class="text-light text-decoration-none small"><i class="bi bi-download me-1"></i>Download App</a>
        </div>
    </div>
</div>

{{-- Main Header --}}
<header class="bg-white shadow-sm sticky-top" style="z-index: 1030;">
    <div class="container">
        <div class="d-flex align-items-center py-2 gap-3">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-decoration-none flex-shrink-0 me-3">
                <span style="font-size: 24px; font-weight: 900; color: var(--lm-primary); letter-spacing: -1px;">
                    {{ \App\Models\Setting::get('site_name', 'Mondals') }}
                </span>
            </a>

            {{-- Search Bar (Daraz-style) --}}
            <form action="{{ route('products') }}" method="GET" class="flex-grow-1 d-none d-md-block">
                <div class="input-group" style="height: 40px;">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control border-end-0 shadow-none"
                           placeholder="Search in Mondals..."
                           style="border-color: var(--lm-primary); font-size: 13px; border-radius: 0;">
                    <button type="submit" class="btn btn-primary px-4"
                            style="border-radius: 0;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Right Actions --}}
            <div class="d-flex align-items-center gap-2 ms-3 flex-shrink-0">
                {{-- Cart --}}
                @php
                    $cartCount = 0;
                    if (auth()->check()) {
                        $cartCount = \App\Models\CartItem::whereHas('cart', fn($q) => $q->where('user_id', auth()->id()))->sum('quantity');
                    } else {
                        $cartCount = collect(session('cart', []))->sum('quantity');
                    }
                @endphp
                <a href="{{ route('cart') }}" class="btn btn-light border-0 position-relative px-3 py-2" title="Cart">
                    <i class="bi bi-cart3 fs-5 text-dark"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: var(--lm-primary); font-size: 10px; transform: translate(-50%, -30%);">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- User Account --}}
                @auth
                    <div class="dropdown">
                        <button class="btn btn-light border-0 dropdown-toggle d-flex align-items-center gap-2 px-3 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-dark"></i>
                            <span class="d-none d-lg-inline small fw-medium">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 220px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold small">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ auth()->user()->email }}</div>
                            </li>
                            @if(auth()->user()->hasRole('admin'))
                                <li><a class="dropdown-item py-2 small" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 opacity-50"></i>Admin Panel</a></li>
                            @endif
                            @if(auth()->user()->hasRole('vendor'))
                                <li><a class="dropdown-item py-2 small" href="{{ route('vendor.dashboard') }}"><i class="bi bi-shop me-2 opacity-50"></i>Vendor Panel</a></li>
                            @endif
                            <li><a class="dropdown-item py-2 small" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid me-2 opacity-50"></i>My Account</a></li>
                            <li><a class="dropdown-item py-2 small" href="{{ route('customer.orders.index') }}"><i class="bi bi-box-seam me-2 opacity-50"></i>My Orders</a></li>
                            <li><a class="dropdown-item py-2 small" href="{{ route('customer.wishlist.index') }}"><i class="bi bi-heart me-2 opacity-50"></i>Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item py-2 small text-danger"><i class="bi bi-box-arrow-right me-2 opacity-50"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 py-2 fw-bold" style="font-size: 13px;">LOGIN</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 py-2 fw-bold d-none d-md-inline-block" style="font-size: 13px;">SIGNUP</a>
                @endauth
            </div>
        </div>

        {{-- Mobile Search --}}
        <div class="d-md-none pb-2">
            <form action="{{ route('products') }}" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control border-end-0 shadow-none"
                           placeholder="Search products..."
                           style="border-color: var(--lm-primary);">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Category Navigation Bar --}}
    <div style="background: var(--lm-secondary);">
        <div class="container">
            <nav class="d-flex align-items-center overflow-auto custom-scrollbar py-0" style="gap: 0;">
                @php
                    $navCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(10)->get();
                @endphp
                <a href="{{ route('products') }}" class="text-white text-decoration-none px-3 py-2 small fw-medium d-flex align-items-center flex-shrink-0" style="white-space: nowrap; border-right: 1px solid rgba(255,255,255,.1);">
                    <i class="bi bi-grid-fill me-2"></i> All Categories
                </a>
                @foreach($navCategories as $navCat)
                    <a href="{{ route('products', ['category' => $navCat->slug]) }}" class="text-white text-decoration-none px-3 py-2 small fw-normal flex-shrink-0 nav-cat-link" style="white-space: nowrap; opacity: .9;">
                        {{ $navCat->name }}
                    </a>
                @endforeach
                <a href="{{ route('stores.index') }}" class="text-white text-decoration-none px-3 py-2 small fw-normal flex-shrink-0 nav-cat-link ms-auto" style="white-space: nowrap; opacity: .9;">
                    <i class="bi bi-shop-window me-1"></i> Stores
                </a>
            </nav>
        </div>
    </div>
</header>

<style>
    .nav-cat-link:hover { background: rgba(255,255,255,.1); opacity: 1 !important; }
</style>
