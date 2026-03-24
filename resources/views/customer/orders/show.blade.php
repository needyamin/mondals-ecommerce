@extends('layouts.customer')
@section('title', 'Order Details #' . $order->order_number)

@section('content')
<div class="space-y-8">
    <div class="flex items-center space-x-4">
        <a href="{{ route('customer.orders.index') }}" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-primary transition-all shadow-sm group">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-heading font-bold text-slate-900 dark:text-white">Order #{{ $order->order_number }}</h2>
    </div>

    <!-- Order Track -->
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
        <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-8">Order Journey</h4>
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-4">
            @php
                $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'completed'];
                $currentIdx = array_search($order->status, $statuses);
            @endphp
            @foreach ($statuses as $idx => $st)
                <div class="flex flex-1 items-center w-full">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $idx <= $currentIdx ? 'bg-primary border-primary text-white' : 'border-slate-200 dark:border-slate-800 text-slate-300' }}">
                            @if ($idx < $currentIdx) <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @else {{ $idx + 1 }} @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase mt-2 {{ $idx <= $currentIdx ? 'text-primary' : 'text-slate-400' }}">{{ $st }}</span>
                    </div>
                    @if ($idx < count($statuses) - 1)
                    <div class="hidden md:block flex-1 h-0.5 mx-4 {{ $idx < $currentIdx ? 'bg-primary' : 'bg-slate-100 dark:bg-slate-800' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Products -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden text-sm">
                <div class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 font-bold text-slate-900 dark:text-white">Order Items</div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($order->items as $item)
                    <div class="p-6 flex items-center space-x-6">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex-shrink-0 overflow-hidden">
                            @if($item->product->primary_image)
                            <img src="{{ $item->product->display_image }}" alt="" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2H4z"/></svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <h5 class="font-bold text-slate-900 dark:text-white truncate">{{ $item->product_name }}</h5>
                            <p class="text-xs text-slate-400">Qty: {{ $item->quantity }} × TK {{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="font-bold text-slate-900 dark:text-white text-right">TK {{ number_format($item->subtotal, 2) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Summary & Details -->
        <div class="space-y-6 text-sm">
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
                <h5 class="font-bold text-slate-900 dark:text-white mb-6">Payment Summary</h5>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                        <span class="text-slate-900 dark:text-white font-medium">TK {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Shipping</span>
                        <span class="text-slate-900 dark:text-white font-medium">TK {{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Tax</span>
                        <span class="text-slate-900 dark:text-white font-medium">TK {{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-rose-500">
                        <span>Discount</span>
                        <span>-TK {{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-slate-100 dark:border-slate-800 pt-4 text-lg font-bold">
                        <span class="text-slate-900 dark:text-white">Total</span>
                        <span class="text-primary">TK {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
                <h5 class="font-bold text-slate-900 dark:text-white mb-4">Delivery & Payment</h5>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-1">Shipping Address</p>
                        <p class="text-slate-900 dark:text-slate-300 leading-relaxed">{{ $order->shipping_address['address'] ?? 'N/A' }}, {{ $order->shipping_address['city'] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-1">Payment Method</p>
                        <p class="text-slate-900 dark:text-slate-300">{{ strtoupper($order->payment_method) }} ({{ $order->payment_status }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
