@extends('layouts.app')

@section('title', $vendor->store_name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Vendor Header -->
    <div class="glass-panel p-8 md:p-12 rounded-3xl mb-12 relative overflow-hidden bg-gradient-to-tr from-indigo-900 via-slate-900 to-purple-900 border-none">
        
        <!-- Abstract Bg -->
        <div class="absolute inset-0 z-0 opacity-40 mix-blend-overlay bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')] bg-cover bg-center"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
            <div class="w-32 h-32 rounded-full border-4 border-white/20 shadow-2xl flex items-center justify-center bg-white dark:bg-slate-900 text-5xl font-extrabold text-primary mb-6 md:mb-0 md:mr-8 flex-shrink-0">
                {{ substr($vendor->store_name, 0, 1) }}
            </div>
            
            <div class="flex-grow text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-heading font-extrabold tracking-tight mb-2">{{ $vendor->store_name }}</h1>
                        <p class="text-indigo-200 text-lg font-light mb-4">Official Retail Partner</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center md:justify-start items-center gap-6 mt-4">
                    <div class="flex items-center text-amber-400">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                        <span class="font-bold text-xl">{{ number_format($vendor->products->flatMap->reviews->avg('rating') ?? 0, 1) }} <span class="text-sm text-indigo-200 font-normal">Average Rating</span></span>
                    </div>
                    <div class="px-4 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md text-sm font-medium">
                        Joined {{ $vendor->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Store Catalog</h2>
        <span class="text-slate-500 dark:text-slate-400 font-medium bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg">{{ $products->total() }} Products</span>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-20 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800">
            <svg class="mx-auto h-16 w-16 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">No products available yet.</h3>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-16 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
