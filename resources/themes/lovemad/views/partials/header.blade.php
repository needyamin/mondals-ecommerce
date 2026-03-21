<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand fs-4" href="{{ route('home') }}">
           <i class="fas fa-heart me-1"></i> {{ config('app.name', 'Mondals') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Shop</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                    <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-menu-item dropdown-item" href="{{ route('products') }}">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#">Men's Fashion</a></li>
                        <li><a class="dropdown-item" href="#">Women's Fashion</a></li>
                        <li><a class="dropdown-item" href="#">Electronics</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex me-lg-3 flex-grow-1 mx-lg-4 d-none d-lg-block">
                <div class="input-group">
                    <input class="form-control border-end-0 bg-light border-0" type="search" placeholder="Search for products..." aria-label="Search">
                    <button class="btn btn-light bg-light border-0 py-0" type="button">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                </div>
            </form>
            <div class="d-flex align-items-center">
                <a href="{{ route('cart') }}" class="btn btn-outline-dark position-relative border-0 me-2 rounded-circle hover-bg-light">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                        {{ app(\App\Services\CartService::class)->getCartCount() ?? 0 }}
                    </span>
                </a>
                @auth
                    <div class="dropdown">
                        <a class="btn btn-light rounded-pill px-3 py-2 border-0 dropdown-toggle d-flex align-items-center" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-circle-user fs-5 text-primary me-2"></i>
                            <span class="d-none d-md-inline text-truncate max-w-100">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" aria-labelledby="userMenu">
                            <li><a class="dropdown-item py-2 px-3" href="{{ route('customer.dashboard') }}"><i class="fas fa-gauge me-2 opacity-50"></i> Dashboard</a></li>
                            <li><a class="dropdown-item py-2 px-3" href="{{ route('customer.profile') }}"><i class="fas fa-user-circle me-2 opacity-50"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item py-2 px-3 text-danger"><i class="fas fa-sign-out-alt me-2 opacity-50"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill fw-600">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<style>
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .max-w-100 { max-width: 100px; display: inline-block; }
    .dropdown-menu-item:hover { background-color: #fff0f3; color: #ff004d; }
</style>
