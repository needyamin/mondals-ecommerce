@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-10 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-4 text-center md:text-left">
            Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Cart</span>
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if($items->count() > 0)
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Cart Items List -->
        <div class="lg:w-2/3">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="hidden sm:grid sm:grid-cols-12 gap-4 border-b border-slate-100 dark:border-slate-800 p-6 bg-slate-50 dark:bg-slate-800/50">
                    <div class="col-span-6 font-bold text-sm tracking-wider text-slate-500 uppercase">Product Details</div>
                    <div class="col-span-3 font-bold text-sm tracking-wider text-slate-500 uppercase text-center">Quantity</div>
                    <div class="col-span-3 font-bold text-sm tracking-wider text-slate-500 uppercase text-right">Price</div>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($items as $item)
                        @php
                            // Handle both authenticated (CartItem model) and guest (array)
                            if (auth()->check()) {
                                $product = $item->product;
                                $itemQty = $item->quantity;
                                $itemPrice = $item->price;
                                $lineTotal = $item->price * $item->quantity;
                                $itemId = $item->id;
                            } else {
                                $product = $item['product'] ?? null;
                                $itemQty = $item['quantity'];
                                $itemPrice = $product ? $product->price : 0;
                                $lineTotal = $item['line_total'] ?? 0;
                                $itemId = ($item['product_id'] ?? 0) . '-' . ($item['variant_id'] ?? 0);
                            }
                        @endphp
                        @if($product)
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-12 gap-6 items-center hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <div class="sm:col-span-6 flex items-center space-x-6">
                                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-700">
                                    @if($product->primary_image)
                                        <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="text-lg font-bold text-slate-900 dark:text-white font-heading hover:text-primary transition">{{ $product->name }}</a>
                                    @if($product->vendor)
                                        <span class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sold By: {{ $product->vendor->store_name }}</span>
                                    @endif
                                    <span class="text-xs text-slate-400 font-mono mt-1">৳{{ number_format($itemPrice, 2) }} each</span>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $itemId }}">
                                        <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600 self-start flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sm:col-span-3 flex justify-center">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-full h-10 border border-slate-200 dark:border-slate-700 p-1">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $itemId }}">
                                    <button type="submit" name="quantity" value="{{ max(0, $itemQty - 1) }}" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-600 hover:bg-white dark:hover:bg-slate-700 font-bold">−</button>
                                    <span class="w-8 text-center text-slate-900 dark:text-white font-semibold">{{ $itemQty }}</span>
                                    <button type="submit" name="quantity" value="{{ $itemQty + 1 }}" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-600 hover:bg-white dark:hover:bg-slate-700 font-bold">+</button>
                                </form>
                            </div>
                            <div class="sm:col-span-3 text-right">
                                <span class="text-lg font-bold text-slate-900 dark:text-white">৳{{ number_format($lineTotal, 2) }}</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('products') }}" class="text-indigo-500 font-semibold hover:text-indigo-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 font-medium hover:text-red-600 text-sm">Clear Cart</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="glass-panel p-8 rounded-3xl sticky top-28 border border-white/40 dark:border-slate-700 bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900">
                <h3 class="text-2xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-4">Order Summary</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                        <span>Items ({{ $items->count() }})</span>
                        <span class="font-medium text-slate-900 dark:text-white">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                        <span>Estimated Shipping</span>
                        <span class="font-medium text-emerald-600">Calculated at checkout</span>
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-slate-900 dark:text-white font-heading">Total</span>
                        <div class="text-right">
                            <span class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">৳{{ number_format($subtotal, 2) }}</span>
                            <span class="block text-xs text-slate-500 mt-1">VAT included</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('checkout') }}" class="btn-primary w-full py-4 text-center rounded-xl font-bold shadow-indigo-500/30 block">
                    Proceed to Checkout
                </a>
                
                <!-- Safe Payment Badges -->
                <div class="mt-6 flex justify-center space-x-3 opacity-60">
                    <div class="w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded border border-slate-300 dark:border-slate-600 flex items-center justify-center text-[8px] font-bold text-slate-500">VISA</div>
                    <div class="w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded border border-slate-300 dark:border-slate-600 flex items-center justify-center text-[8px] font-bold text-slate-500">bKash</div>
                    <div class="w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded border border-slate-300 dark:border-slate-600 flex items-center justify-center text-[8px] font-bold text-slate-500">COD</div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart State -->
    <div class="flex flex-col items-center justify-center py-20">
        <div class="w-28 h-28 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-8">
            <svg class="w-14 h-14 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
        </div>
        <h3 class="text-2xl font-extrabold font-heading text-slate-900 dark:text-white mb-3">Your cart is empty</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-8 text-center max-w-md">Looks like you haven't added any products yet. Browse our catalogue and find something you love!</p>
        <a href="{{ route('products') }}" class="btn-primary px-8 py-3 rounded-xl font-bold shadow-indigo-500/30">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
