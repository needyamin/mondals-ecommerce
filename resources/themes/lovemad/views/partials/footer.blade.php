{{-- Daraz-Style Footer --}}
<footer style="background: var(--lm-secondary); color: #999; font-size: 13px;" class="mt-5">
    <div class="container py-5">
        <div class="row g-4">
            {{-- Brand & App --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white fw-bold mb-3" style="font-size: 22px; letter-spacing: -1px;">
                    {{ \App\Models\Setting::get('site_name', 'Mondals') }}
                </h5>
                <p class="mb-3" style="line-height: 1.7;">
                    {{ \App\Models\Setting::get('site_description', 'Your one-stop online shopping destination in Bangladesh. Best prices, authentic products.') }}
                </p>
                <div class="d-flex gap-2 mb-3">
                    @php
                        $facebook = \App\Models\Setting::get('social_facebook', '#', 'general');
                        $instagram = \App\Models\Setting::get('social_instagram', '#', 'general');
                        $twitter = \App\Models\Setting::get('social_twitter', '#', 'general');
                    @endphp
                    <a href="{{ $facebook }}" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;opacity:.6;" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $instagram }}" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;opacity:.6;" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $twitter }}" target="_blank" rel="noopener" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;opacity:.6;" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            {{-- Customer Service --}}
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">Customer Care</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-decoration-none" style="color: #999;">Help Center</a></li>
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-decoration-none" style="color: #999;">How to Buy</a></li>
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-decoration-none" style="color: #999;">Returns & Refunds</a></li>
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-decoration-none" style="color: #999;">Shipping Info</a></li>
                    @auth
                        <li class="mb-2"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none" style="color: #999;">Track Order</a></li>
                    @else
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none" style="color: #999;">Track Order</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Shop --}}
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">Shop</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-decoration-none" style="color: #999;">All Products</a></li>
                    <li class="mb-2"><a href="{{ route('products', ['sort' => 'latest']) }}" class="text-decoration-none" style="color: #999;">New Arrivals</a></li>
                    <li class="mb-2"><a href="{{ route('stores.index') }}" class="text-decoration-none" style="color: #999;">Store Directory</a></li>
                    <li class="mb-2"><a href="{{ route('cart') }}" class="text-decoration-none" style="color: #999;">My Cart</a></li>
                </ul>
            </div>

            {{-- Make Money --}}
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">Earn With Us</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('register') }}" class="text-decoration-none" style="color: #999;">Sell on Mondals</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none" style="color: #999;">Affiliate Program</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none" style="color: #999;">Terms & Conditions</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none" style="color: #999;">Privacy Policy</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">Newsletter</h6>
                <p class="mb-3">Get exclusive deals and latest product updates delivered to your inbox.</p>
                <form class="d-flex mb-3">
                    <input type="email" class="form-control form-control-sm bg-dark text-white border-secondary rounded-0" placeholder="Your email" style="font-size: 13px;">
                    <button type="button" class="btn btn-primary btn-sm rounded-0 px-3 fw-bold" style="font-size: 12px;">GO</button>
                </form>
                <p class="mb-0" style="font-size: 11px; opacity: .6;">No spam. Unsubscribe anytime.</p>
            </div>
        </div>
    </div>

    {{-- Payment Methods Bar --}}
    <div style="background: rgba(0,0,0,.3);">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span style="font-size: 12px; opacity: .7;">
                        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Mondals E-Commerce') }}. All rights reserved.
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <div class="d-inline-flex gap-2 align-items-center">
                        <span class="d-inline-flex align-items-center justify-content-center bg-white rounded px-2" style="height: 28px; font-size: 10px; font-weight: 700; color: #333;">bKash</span>
                        <span class="d-inline-flex align-items-center justify-content-center bg-white rounded px-2" style="height: 28px; font-size: 10px; font-weight: 700; color: #333;">Nagad</span>
                        <span class="d-inline-flex align-items-center justify-content-center bg-white rounded px-2" style="height: 28px; font-size: 10px; font-weight: 700; color: #333;">VISA</span>
                        <span class="d-inline-flex align-items-center justify-content-center bg-white rounded px-2" style="height: 28px; font-size: 10px; font-weight: 700; color: #333;">MasterCard</span>
                        <span class="d-inline-flex align-items-center justify-content-center bg-white rounded px-2" style="height: 28px; font-size: 10px; font-weight: 700; color: #333;">COD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    footer a:hover { color: var(--lm-primary) !important; }
</style>
