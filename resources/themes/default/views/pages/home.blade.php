@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', \App\Models\Setting::get('site_description', 'Discover premium tech and lifestyle products at Mondals.'))

@section('content')

@if(session('success'))
    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 max-w-lg w-full px-4 animate-slide-up">
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/90 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-xl backdrop-blur-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif
@if(session('info'))
    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 max-w-lg w-full px-4 animate-slide-up">
        <div class="p-4 rounded-xl bg-sky-50 dark:bg-sky-900/90 border border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200 flex items-center shadow-xl backdrop-blur-md" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition>
            <span class="font-medium">{{ session('info') }}</span>
        </div>
    </div>
@endif

<!-- Hero Section -->
<section class="relative overflow-hidden w-full h-[80vh] min-h-[600px] flex items-center justify-center bg-darkbg">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/30 blur-[120px] rounded-full mix-blend-screen mix-blend-color-dodge"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-600/30 blur-[120px] rounded-full mix-blend-screen mix-blend-color-dodge"></div>
        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDM5LjVoNDBWNDBIMHptMzkuNSAwVjBoLjV2NDB6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+PC9zdmc+')] mix-blend-overlay"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-slide-up">
        <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-indigo-300 text-sm font-semibold tracking-wider uppercase mb-6 backdrop-blur-md">
            New Collection 2026
        </span>
        <h1 class="text-5xl md:text-7xl font-extrabold text-white font-heading tracking-tight mb-6 drop-shadow-2xl">
            Future of <br/>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">Digital Shopping</span>
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-300 mb-10 leading-relaxed font-light">
            Elevate your lifestyle with premium electronics, cutting-edge gadgets, and exclusive fashion straight from top vendors across Bangladesh.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('products') }}" class="btn-primary py-4 px-10 text-lg shadow-[0_0_40px_rgba(79,70,229,0.5)]">
                Shop Now
            </a>
            <a href="{{ route('stores.index') }}" class="btn-outline border-white/30 text-white hover:bg-white hover:text-slate-900 backdrop-blur-sm py-4 px-10 text-lg">
                Explore Vendors
            </a>
            <a href="{{ route('register.vendor') }}" class="btn-outline border-white/30 text-white hover:bg-white hover:text-slate-900 backdrop-blur-sm py-4 px-10 text-lg">
                Sell with us
            </a>
        </div>
    </div>
</section>

<!-- Categories Strip -->
<section class="py-12 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 relative z-20 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between items-center gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products', ['category' => $category->slug]) }}" class="group flex flex-col items-center justify-center space-y-3 w-1/3 md:w-auto transform transition duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 group-hover:bg-primary group-hover:text-white dark:group-hover:bg-indigo-500 shadow-sm group-hover:shadow-lg transition-all">
                    <!-- Placeholder icon -->
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="font-medium text-sm text-slate-700 dark:text-slate-300 group-hover:text-primary dark:group-hover:text-indigo-400 transition">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-24 bg-slate-50 dark:bg-darkbg relative overflow-hidden">
    <!-- Abstract gradient -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-indigo-500/5 to-transparent dark:from-indigo-600/10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-heading font-extrabold text-slate-900 dark:text-white tracking-tight">Featured Picks</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg">Hand-picked premium selections just for you.</p>
            </div>
            <a href="{{ route('products') }}" class="hidden md:inline-flex items-center text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-800 dark:hover:text-indigo-300 group">
                View All <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        
        <div class="mt-10 text-center md:hidden">
            <a href="{{ route('products') }}" class="btn-outline w-full justify-center">View All Collections</a>
        </div>
    </div>
</section>

<!-- Promotional Banner -->
<section class="py-16 bg-white dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl overflow-hidden relative shadow-2xl group flex flex-col md:flex-row glass-panel bg-gradient-to-br from-indigo-900 to-slate-900 border-none">
            <div class="md:w-1/2 p-12 lg:p-16 flex flex-col justify-center relative z-10 order-2 md:order-1">
                <span class="text-indigo-400 font-bold tracking-widest uppercase text-sm mb-2">Limited Offer</span>
                <h3 class="text-4xl lg:text-5xl font-heading font-extrabold text-white leading-tight mb-6">Upgrade Your Setup Today.</h3>
                <p class="text-slate-300 text-lg mb-8 leading-relaxed font-light">Get up to 30% off on all premium gaming laptops and smartphone flagships. Valid till stock lasts.</p>
                <div>
                    <a href="{{ route('products') }}" class="bg-white text-slate-900 font-bold py-4 px-8 rounded-full hover:scale-105 transition-transform shadow-xl inline-block">Claim Discount</a>
                </div>
            </div>
            <div class="md:w-1/2 relative min-h-[300px] order-1 md:order-2">
                <!-- Fallback abstract visual -->
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 to-purple-600 opacity-80 mix-blend-multiply group-hover:scale-110 transition-transform duration-700"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1593640498182-fcb23b16cf4c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')] bg-cover bg-center mix-blend-overlay opacity-60"></div>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="py-24 bg-slate-50 dark:bg-darkbg relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-heading font-extrabold text-slate-900 dark:text-white tracking-tight">Newly Added</h2>
            <div class="h-1 w-20 bg-primary mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($newArrivals as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

@endsection
