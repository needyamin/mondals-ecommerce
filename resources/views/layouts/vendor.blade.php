<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vendor Terminal') | Mondals Ecommerce</title>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('vendorTheme') === 'dark' }"
      x-init="$watch('darkMode', val => localStorage.setItem('vendorTheme', val ? 'dark' : 'light'))"
      :class="{ 'dark': darkMode }" 
      class="flex h-screen overflow-hidden transition-colors duration-300">

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-slate-900/80 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-30 w-72 bg-slate-900 text-slate-300 flex flex-col transition-all duration-300 transform border-r border-slate-800 shadow-2xl lg:shadow-none lg:static">
        
        <div class="h-20 flex items-center px-6 bg-slate-900/50 backdrop-blur-md border-b border-slate-800">
            <div class="flex items-center space-x-3 w-full">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-vendor-400 to-teal-600 flex items-center justify-center text-white font-heading font-bold text-xl shadow-lg shadow-vendor-500/30">V</div>
                <div class="flex flex-col">
                    <span class="text-white font-heading font-bold text-lg tracking-tight leading-tight">Vendor<span class="text-vendor-400">Desk</span></span>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-4 py-6 scrollbar-hide space-y-1">
            <a href="{{ route('vendor.dashboard') }}" class="sidebar-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H14zM4 16h2v2H4zM14 16h2v2h-2z"></path></svg>
                Dashboard
            </a>
            
            <div class="px-2 py-3 mt-4 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-800 mb-2">My Shop</div>
            <a href="{{ route('vendor.products.index') ?? '#' }}" class="sidebar-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                My Inventory
            </a>
            <a href="{{ route('vendor.orders.index') ?? '#' }}" class="sidebar-link {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Received Orders
            </a>
            <a href="{{ route('vendor.settings.index') }}" class="sidebar-link {{ request()->routeIs('vendor.settings.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Store Settings
            </a>

            <div class="px-2 py-3 mt-4 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-800 mb-2">Wallet</div>
            <a href="{{ route('vendor.earnings.index') ?? '#' }}" class="sidebar-link {{ request()->routeIs('vendor.earnings.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8V7m0 1v8m0 0v1m2-12v12M8 4v16"></path></svg>
                Income Reports
            </a>
            <a href="{{ route('vendor.payouts.index') ?? '#' }}" class="sidebar-link {{ request()->routeIs('vendor.payouts.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Payout History
            </a>
        </nav>
        
        <div class="p-4 bg-slate-800 mt-auto flex flex-col space-y-2">
            @if(auth()->user() && auth()->user()->vendor)
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white border border-slate-600 font-bold uppercase">
                    {{ substr(auth()->user()->vendor->store_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-tight">{{ auth()->user()->vendor->store_name }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ auth()->user()->vendor->status == 'approved' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }} border border-transparent font-medium">{{ ucfirst(auth()->user()->vendor->status) }}</span>
                </div>
            </div>
            @endif
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-20 glass-header flex items-center justify-between px-6 lg:px-10 z-10 sticky top-0 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-slate-500 hover:text-vendor-600 focus:outline-none lg:hidden mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <h1 class="text-2xl font-bold font-heading">@yield('title')</h1>
            </div>
            
            <div class="flex items-center space-x-4 md:space-x-6">
                <button @click="darkMode = !darkMode" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-cloak x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>

                <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center text-sm font-medium text-vendor-600 dark:text-vendor-400 hover:underline">
                    Live Storefront &nearr;
                </a>
                
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-full text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-10 pb-24 mx-auto w-full max-w-7xl">
            @yield('content')
        </main>
    </div>
</body>
</html>
