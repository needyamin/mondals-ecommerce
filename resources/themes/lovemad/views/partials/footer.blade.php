<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="text-white mb-4 fs-3 fw-bold">
                    <i class="fas fa-heart text-primary me-2"></i>{{ config('app.name', 'Mondals') }}
                </h5>
                <p class="text-white-50">{{ @themeValue('footer_text', '© 2026 Lovemad E-Commerce. All rights reserved.') }}</p>
                <div class="d-flex mt-4">
                    <a href="#" class="me-3 text-white-50 fs-5 hvr-text-primary"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="me-3 text-white-50 fs-5 hvr-text-primary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-3 text-white-50 fs-5 hvr-text-primary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="me-3 text-white-50 fs-5 hvr-text-primary"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <h6 class="text-white mb-4 fw-bold text-uppercase small">Shop</h6>
                <ul class="list-unstyled text-white-50 small">
                    <li class="mb-2"><a href="{{ route('products') }}" class="text-white-50 text-decoration-none hvr-text-primary">All Products</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">New Arrivals</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">Featured Items</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">Special Offers</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <h6 class="text-white mb-4 fw-bold text-uppercase small">Company</h6>
                <ul class="list-unstyled text-white-50 small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">Our Story</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">Terms & Conditions</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hvr-text-primary">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="text-white mb-4 fw-bold text-uppercase small">Newsletter</h6>
                <p class="text-white-50 small mb-4">Be the first to get the latest updates and offers.</p>
                <form class="d-flex border-bottom border-white-50 pb-2">
                    <input type="email" class="form-control bg-transparent border-0 text-white placeholder-white-50 shadow-none ps-0" placeholder="Enter your email" aria-label="Enter your email">
                    <button class="btn btn-primary rounded-pill px-3 py-1 ms-2" type="button">Join</button>
                </form>
            </div>
        </div>
        <hr class="my-5 border-white-25">
        <div class="text-center text-white-50 small">
            Designed with <i class="fas fa-heart text-primary mx-1"></i> for Mondals E-Commerce
        </div>
    </div>
</footer>
<style>
    .hvr-text-primary:hover { color: var(--bs-primary) !important; transition: 0.3s; }
    .border-white-25 { border-color: rgba(255, 255, 255, 0.1) !important; }
    .placeholder-white-50::placeholder { color: rgba(255, 255, 255, 0.5); }
    .bg-dark { background-color: #0f172a !important; }
</style>
