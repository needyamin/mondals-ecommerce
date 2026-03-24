@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    
    <!-- Success Animation -->
    <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
        <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-4">
        Order <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">Confirmed!</span>
    </h1>
    <p class="text-lg text-slate-500 dark:text-slate-400 mb-2">Thank you for your purchase.</p>
    <p class="text-sm font-mono text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 inline-block px-4 py-2 rounded-lg border border-indigo-100 dark:border-indigo-800/50 mb-10">
        Order #{{ $order->order_number }}
    </p>

    <!-- Order Details Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden text-left mb-10">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold font-heading text-slate-900 dark:text-white">Order Items</h3>
            <span class="text-xs font-mono text-slate-500">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
        </div>
        
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($order->items as $item)
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-700 overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->primary_image)
                            <img src="{{ $item->product->display_image }}" alt="" class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white">{{ $item->product_name }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Qty: {{ $item->quantity }} &times; ৳{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
                <span class="font-bold text-slate-900 dark:text-white">৳{{ number_format($item->total, 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-slate-900 dark:text-white font-heading">Grand Total</span>
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">৳{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between items-center mt-3 text-sm text-slate-500">
                <span>Payment Method</span>
                <span class="font-medium text-slate-700 dark:text-slate-300 uppercase">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between items-center mt-2 text-sm text-slate-500">
                <span>Status</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
    </div>

    <!-- Delivery Info -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 text-left mb-10">
        <h3 class="font-bold font-heading text-slate-900 dark:text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Delivering To
        </h3>
        <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
        <p class="text-sm text-slate-500 mt-1">{{ $order->shipping_address_line_1 }}</p>
        <p class="text-sm text-slate-500">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip_code }}</p>
        <p class="text-sm text-slate-500">{{ $order->shipping_country }}</p>
        <p class="text-sm text-slate-500 mt-2 font-mono">Phone: {{ $order->shipping_phone }}</p>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('products') }}" class="btn-primary px-8 py-3 rounded-xl font-bold shadow-indigo-500/30">
            Continue Shopping
        </a>
        <a href="{{ route('home') }}" class="px-8 py-3 rounded-xl font-bold border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            Go to Homepage
        </a>
    </div>
</div>
@push('scripts')
    @include('partials.marketing-purchase', ['order' => $order])
@endpush
@endsection
