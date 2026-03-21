@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-10 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-2 text-center md:text-left">
            Secure <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Checkout</span>
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-center md:text-left">Complete your order by filling in the delivery details below.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('warning') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400">
            <ul class="list-disc list-inside text-sm font-medium space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Left: Shipping & Payment -->
            <div class="lg:w-2/3 space-y-8">
                
                <!-- Shipping Address -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Delivery Address
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name', auth()->user()->name ?? '') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Last Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Address <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="House No, Road, Area..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">City <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required placeholder="Dhaka" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Division / State</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" placeholder="Dhaka Division" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">ZIP / Postal Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required placeholder="1200" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Country <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_country" value="{{ old('shipping_country', 'Bangladesh') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="+880 1XXXXXXXXX" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Shipping Method Selection -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Shipping Method
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($availShipping as $sm)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="shipping_method" value="{{ $sm['id'] }}" data-cost="{{ $sm['cost'] }}" class="sr-only peer shipping-radio" {{ $loop->first ? 'checked' : '' }}>
                                <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 peer-checked:border-indigo-50 dark:peer-checked:bg-indigo-900/20 transition-all group-hover:border-indigo-300">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            @if(str_contains($sm['id'], 'pathao'))
                                                <div class="w-8 h-8 rounded-lg bg-rose-500 p-1 mr-3 flex items-center justify-center text-white font-black text-xs">P</div>
                                            @elseif(str_contains($sm['id'], 'flat'))
                                                <div class="w-8 h-8 rounded-lg bg-blue-500 p-1 mr-3 flex items-center justify-center text-white font-black text-xs">FR</div>
                                            @endif
                                            <div>
                                                <h4 class="font-bold text-slate-900 dark:text-white">{{ $sm['name'] }}</h4>
                                                <p class="text-xs text-slate-500 mt-1">{{ $sm['estimated_days'] ?? 'Standard delivery' }}</p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-indigo-600">
                                            @if($sm['cost'] > 0) ৳{{ number_format($sm['cost'], 0) }} @else Free @endif
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 text-center py-8 text-slate-500">
                                <p>No shipping methods available. Check Plugin Settings.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Payment Method
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($paymentMethods as $pm)
                            <label class="relative cursor-pointer group" id="payment-{{ $pm['id'] }}">
                                <input type="radio" name="payment_method" value="{{ $pm['id'] }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                                <div class="p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-700 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 transition-all group-hover:border-indigo-300">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-white">{{ $pm['name'] }}</h4>
                                            <p class="text-xs text-slate-500 mt-1">{{ $pm['description'] }}</p>
                                        </div>
                                        @if($pm['id'] === 'cod')
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                        @elseif($pm['id'] === 'bkash')
                                            <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-pink-600 font-extrabold text-xs">bK</div>
                                        @elseif($pm['id'] === 'nagad')
                                            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 font-extrabold text-xs">N</div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 text-center py-8 text-slate-500">
                                <p>No payment methods available. Please configure them in Admin Settings.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-4">Order Notes</h3>
                    <textarea name="notes" rows="3" placeholder="Any special instructions for delivery..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:w-1/3">
                <div class="glass-panel p-8 rounded-3xl sticky top-28 border border-white/40 dark:border-slate-700 bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900">
                    <h3 class="text-2xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-4">Your Order</h3>
                    
                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                        @foreach($cart->items as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex-shrink-0 overflow-hidden border border-slate-200 dark:border-slate-700">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ asset('storage/' . $item->product->primary_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $item->product->name ?? 'Product' }}</p>
                                        <p class="text-xs text-slate-500">x{{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white ml-3 flex-shrink-0">৳{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4 space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>Subtotal</span>
                            <span class="font-medium">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>Shipping</span>
                            <span class="font-medium text-emerald-600" id="shipping_display">Select method</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                        <div class="flex justify-between items-end">
                            <span class="text-lg font-bold text-slate-900 dark:text-white font-heading">Total</span>
                            <span class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight" id="total_display">৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" id="placeOrderBtn" class="btn-primary w-full py-4 text-center rounded-xl font-bold shadow-indigo-500/30 text-lg">
                        🛒 Place Order
                    </button>
                    
                    <p class="text-center text-xs text-slate-400 mt-4">By placing an order you agree to our <a href="#" class="underline hover:text-slate-600">Terms & Conditions</a></p>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = {{ $subtotal }};
    const shippingDisplay = document.getElementById('shipping_display');
    const totalDisplay = document.getElementById('total_display');
    const shippingRadios = document.querySelectorAll('.shipping-radio');

    function updateTotals() {
        const selected = document.querySelector('.shipping-radio:checked');
        if (selected) {
            const cost = parseFloat(selected.dataset.cost) || 0;
            shippingDisplay.textContent = cost > 0 ? '৳' + cost.toFixed(0) : 'Free';
            totalDisplay.textContent = '৳' + (subtotal + cost).toLocaleString('en-BD', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }

    shippingRadios.forEach(radio => radio.addEventListener('change', updateTotals));
    updateTotals(); // Initial calculation
});
</script>
@endsection
