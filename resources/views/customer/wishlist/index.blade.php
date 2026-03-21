@extends('layouts.customer')
@section('title', 'My Wishlist')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-heading font-bold text-slate-900 dark:text-white">Saved Products</h2>
        <span class="text-sm font-bold text-rose-500">{{ $wishlistItems->total() }} items</span>
    </div>

    @if($wishlistItems->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 font-sans">
        @foreach($wishlistItems as $item)
        <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-sm group hover:translate-y-[-4px] transition-all duration-300">
            <div class="relative w-full h-48 rounded-2xl bg-slate-100 dark:bg-slate-800 overflow-hidden mb-6">
                @if($item->product->primary_image)
                <img src="{{ asset('storage/' . $item->product->primary_image) }}" alt="" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2H4z"/></svg>
                </div>
                @endif
                <form action="{{ route('customer.wishlist.toggle') }}" method="POST" class="absolute top-4 right-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <button type="submit" class="w-10 h-10 rounded-full flex items-center justify-center bg-rose-500 text-white shadow-lg shadow-rose-500/30 hover:bg-rose-600 active:scale-95 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </form>
            </div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-2 truncate">{{ $item->product->name }}</h4>
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-primary">TK {{ number_format($item->product->price, 2) }}</span>
                <a href="{{ route('product.detail', $item->product->slug) }}" class="text-xs font-bold text-slate-400 hover:text-primary transition-colors">Details &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $wishlistItems->links() }}
    </div>
    @else
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-20 text-center shadow-sm">
        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-6 text-slate-200">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-2">No Saved Items</h3>
        <p class="text-slate-500 dark:text-slate-400 font-light text-sm mb-8">Save products you're interested in for quick access later.</p>
        <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-10 py-3 rounded-2xl bg-primary text-white font-bold shadow-lg shadow-primary/30 hover:bg-primaryHover hover:scale-[1.02] transform transition-all active:scale-95">Browse Products</a>
    </div>
    @endif
</div>
@endsection
