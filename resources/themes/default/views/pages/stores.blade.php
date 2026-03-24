@extends('layouts.app')

@section('title', 'Registered Stores')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-10 text-center animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-4">
            Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Retail Partners</span>
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">Explore exclusive catalogs from verified premium vendors operating directly inside the Mondals network.</p>
    </div>

    @if($vendors->isEmpty())
        <div class="text-center py-24 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-medium text-slate-900 dark:text-white">No active vendors found.</h3>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($vendors as $vendor)
                <div class="glass-panel p-8 rounded-3xl flex flex-col items-center text-center group border border-slate-100 dark:border-slate-700 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-24 h-24 mb-6 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold font-heading shadow-xl shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                        {{ substr($vendor->store_name, 0, 1) }}
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ $vendor->store_name }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 flex-grow">
                        {{ \Illuminate\Support\Str::limit($vendor->description ?? 'Premium vendor committed to high quality products and excellent customer service.', 100) }}
                    </p>
                    
                    <div class="flex justify-between w-full border-t border-slate-100 dark:border-slate-700/50 pt-4 mb-6">
                        <div class="text-center w-1/2 border-r border-slate-100 dark:border-slate-700/50">
                            <span class="block text-2xl font-bold text-slate-900 dark:text-white">{{ $vendor->products_count }}</span>
                            <span class="text-xs text-slate-500 uppercase tracking-wider">Products</span>
                        </div>
                        <div class="text-center w-1/2">
                            @if(\App\Models\Plugin::isActiveSlug('product-reviews'))
                            <span class="block text-2xl font-bold text-amber-500">{{ number_format($vendor->products->avg(fn($p) => $p->reviews->avg('rating')) ?? 0, 1) }}</span>
                            @else
                            <span class="block text-2xl font-bold text-slate-300 dark:text-slate-600">—</span>
                            @endif
                            <span class="text-xs text-slate-500 uppercase tracking-wider">Rating</span>
                        </div>
                    </div>

                    <a href="{{ route('stores.show', $vendor->slug) }}" class="btn-outline w-full rounded-2xl py-3">View Catalog</a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $vendors->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
