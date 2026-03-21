<header class="fixed w-full z-50 transition-all duration-300 backdrop-blur-md bg-white/70 dark:bg-darkbg/70 border-b border-slate-200 dark:border-slate-800" x-data="{ scrolled: false, searchOpen: false, searchQuery: '' }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 transition-all duration-300" :class="{ 'h-16': scrolled }">
            
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-purple-600 dark:from-indigo-400 dark:to-purple-400 font-heading tracking-tight">
                    {{ \App\Models\Setting::get('site_name', 'Mondals') }}
                </a>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="text-base font-medium {{ request()->routeIs('home') ? 'text-primary dark:text-indigo-400' : 'text-slate-700 dark:text-slate-200' }} hover:text-primary dark:hover:text-indigo-400 transition-colors">Home</a>
                <a href="{{ route('products') }}" class="text-base font-medium {{ request()->routeIs('products') ? 'text-primary dark:text-indigo-400' : 'text-slate-700 dark:text-slate-200' }} hover:text-primary dark:hover:text-indigo-400 transition-colors">Shop</a>
                <a href="{{ route('stores.index') }}" class="text-base font-medium {{ request()->routeIs('stores.*') ? 'text-primary dark:text-indigo-400' : 'text-slate-700 dark:text-slate-200' }} hover:text-primary dark:hover:text-indigo-400 transition-colors">Vendors</a>
                <a href="{{ route('cart') }}" class="text-base font-medium {{ request()->routeIs('cart') ? 'text-primary dark:text-indigo-400' : 'text-slate-700 dark:text-slate-200' }} hover:text-primary dark:hover:text-indigo-400 transition-colors">Cart</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-6">
                <!-- Search Toggle -->
                <button @click="searchOpen = !searchOpen" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-indigo-400 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-indigo-400 transition-colors focus:outline-none">
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-cloak x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <!-- Cart Icon with Live Count -->
                @php
                    $cartCount = 0;
                    if (auth()->check()) {
                        $cartCount = \App\Models\CartItem::whereHas('cart', fn($q) => $q->where('user_id', auth()->id()))->sum('quantity');
                    } else {
                        $cartCount = collect(session('cart', []))->sum('quantity');
                    }
                @endphp
                <a href="{{ route('cart') }}" class="relative text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-indigo-400 transition-colors group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-primary dark:bg-indigo-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg group-hover:scale-110 transition-transform">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- User Account -->
                @auth
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-indigo-400 transition-colors focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Admin Dashboard</a>
                            @endif
                            @if(auth()->user()->hasRole('vendor'))
                                <a href="{{ route('vendor.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Vendor Dashboard</a>
                            @endif
                            <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">My Account</a>
                            <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">My Orders</a>
                            <a href="{{ route('customer.wishlist.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Wishlist</a>
                            <a href="{{ route('cart') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">My Cart</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-bold">Sign Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-primary transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2 text-sm shadow-md">Join Now</a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-500 hover:text-primary dark:text-slate-400 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-cloak x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Search Bar Dropdown -->
    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="absolute w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <form action="{{ route('products') }}" method="GET" class="flex items-center">
                <svg class="w-5 h-5 text-slate-400 absolute ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="q" x-model="searchQuery" value="{{ request('q') }}" placeholder="Search products, brands, categories..." class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" autofocus>
                <button type="submit" class="ml-3 btn-primary px-6 py-3 text-sm">Search</button>
                <button type="button" @click="searchOpen = false" class="ml-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile view dropdown -->
    <div x-show="mobileMenuOpen" x-transition class="md:hidden absolute w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 {{ request()->routeIs('home') ? 'bg-slate-50 dark:bg-slate-800' : '' }}">Home</a>
            <a href="{{ route('products') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 {{ request()->routeIs('products') ? 'bg-slate-50 dark:bg-slate-800' : '' }}">Shop</a>
            <a href="{{ route('stores.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800">Vendors</a>
            <a href="{{ route('cart') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 {{ request()->routeIs('cart') ? 'bg-slate-50 dark:bg-slate-800' : '' }}">
                Cart @if($cartCount > 0)<span class="text-xs bg-primary text-white px-2 py-0.5 rounded-full ml-1">{{ $cartCount }}</span>@endif
            </a>
            @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-primary dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">Sign In</a>
            @endguest
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Sign Out</button>
                </form>
            @endauth
        </div>

        <!-- Mobile Search -->
        <div class="px-4 pb-4">
            <form action="{{ route('products') }}" method="GET" class="flex items-center">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-l-xl text-slate-900 dark:text-white text-sm">
                <button type="submit" class="bg-primary text-white px-4 py-2.5 rounded-r-xl text-sm font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>
</header>
