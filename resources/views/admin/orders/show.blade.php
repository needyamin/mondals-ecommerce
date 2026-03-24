@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')

    <!-- Header & Navigation -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Order #{{ $order->order_number }}</h2>
                <div class="flex items-center gap-3 mt-1 text-sm text-slate-500 dark:text-slate-400">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $order->created_at->format('M d, Y h:i A') }}
                    </span>
                    <span>&bull;</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300">
                        Total items: {{ collect($order->items)->sum('quantity') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" rel="noopener" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 px-4 py-2.5 rounded-xl font-bold transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Document Area -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Order Line Items -->
            <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">Order Details</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border 
                        {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50 dark:text-amber-400' }}
                    ">
                        Payment: {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-full">Product</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Unit Price</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Qty</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden flex-shrink-0 mr-4 border border-slate-200 dark:border-slate-700">
                                            @if($item->product)
                                                <img src="{{ $item->product->display_image }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $item->product_name }}</h4>
                                            @if($item->options)
                                                <p class="text-xs text-slate-500 font-mono mt-0.5">
                                                    @foreach((array)$item->options as $key => $val)
                                                        {{ $key }}: {{ $val }}
                                                    @endforeach
                                                </p>
                                            @endif
                                            @if($item->product && $item->product->vendor)
                                                <p class="text-[10px] text-brand-600 dark:text-brand-400 font-bold tracking-wider uppercase mt-1">Vendor: {{ $item->product->vendor->store_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">৳{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">x{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-slate-900 dark:text-white">৳{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
                    <div class="w-full md:w-1/2 ml-auto space-y-3">
                        <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                            <span>Subtotal</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">৳{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                            <span>Shipping Method</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">৳{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-emerald-600">
                            <span>Discounts Applied</span>
                            <span class="font-medium">- ৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-extrabold text-slate-900 dark:text-white pt-3 border-t border-slate-200 dark:border-slate-700">
                            <span>Total</span>
                            <span class="text-brand-600 dark:text-brand-400">৳{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tracking & Status Manipulation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold font-heading text-slate-900 dark:text-white mb-6">Manage Status</h3>
                    
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Fulfillment State</label>
                            <select name="status" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 transition-colors">
                                @foreach(['pending','confirmed','processing','shipped','delivered','completed','cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Internal Notes</label>
                            <textarea name="comment" rows="2" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 transition-colors" placeholder="(Optional) Tracking URL, cancellation reason..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 text-white font-bold py-3 rounded-xl transition">
                            Update Order
                        </button>
                    </form>
                </div>
                
                <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold font-heading text-slate-900 dark:text-white mb-6">Manage Finance</h3>
                    
                    <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Payment Disposition</label>
                            <select name="payment_status" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition-colors">
                                @foreach(['pending','paid','failed','refunded'] as $st)
                                    <option value="{{ $st }}" {{ $order->payment_status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="pt-2">
                            <ul class="text-xs font-mono text-slate-500 space-y-2">
                                <li><strong>Gateway:</strong> {{ $order->payment_method ?? 'N/A' }}</li>
                                <li><strong>Tx Ref:</strong> {{ $order->transaction_id ?? 'N/A' }}</li>
                            </ul>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition">
                                Force Finance Sync
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            
        </div>
        
        <!-- Right Column: Context Information -->
        <div class="space-y-8">
            
            <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="font-bold flex items-center font-heading text-slate-900 dark:text-white mb-4">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Customer
                </h3>
                @if($order->user)
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-600 flex items-center justify-center font-bold mr-3 font-heading">{{ substr($order->user->name, 0, 1) }}</div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $order->user->name }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $order->user->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm italic text-slate-500">Guest Checkout</p>
                @endif
                
                <h3 class="font-bold flex items-center font-heading text-slate-900 dark:text-white mb-3 mt-6">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Delivery Destination
                </h3>
                
                @if($order->shipping_address_line_1)
                    <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                        <li class="font-bold text-slate-900 dark:text-white">
                            {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}
                        </li>
                        <li>{{ $order->shipping_address_line_1 }}</li>
                        @if($order->shipping_address_line_2)
                            <li>{{ $order->shipping_address_line_2 }}</li>
                        @endif
                        <li>
                            {{ implode(', ', array_filter([$order->shipping_city, $order->shipping_state, $order->shipping_zip_code])) }}
                        </li>
                        <li>{{ $order->shipping_country }}</li>
                        <li class="pt-2 font-mono text-xs">Ph: {{ $order->shipping_phone }}</li>
                    </ul>
                @else
                    <p class="text-sm italic text-slate-500">No structured shipping block provided.</p>
                @endif
            </div>

            <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 relative">
                <h3 class="font-bold flex items-center font-heading text-slate-900 dark:text-white mb-6">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Timeline Audit
                </h3>
                
                <div class="absolute left-[39px] top-16 bottom-[30px] w-px bg-slate-200 dark:bg-slate-700"></div>
                
                <ul class="space-y-6 relative">
                    <!-- Base Order Placed -->
                    <li class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 mr-3 z-10 text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">System Reception</p>
                            <p class="text-xs text-slate-500">Order successfully verified and entered queue.</p>
                            <p class="text-[10px] text-slate-400 font-mono mt-1">{{ $order->created_at->format('M d - H:i') }}</p>
                        </div>
                    </li>
                    
                    @if($order->statusHistory)
                        @foreach($order->statusHistory as $h)
                            <li class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/30 border-2 border-brand-200 dark:border-brand-800/50 flex items-center justify-center shrink-0 mr-3 z-10 text-brand-600 dark:text-brand-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="pb-2">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white capitalize">{{ $h->new_status }}</p>
                                    @if($h->comment)
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 italic border-l-2 border-slate-200 dark:border-slate-700 pl-2 py-1 leading-relaxed">{{ $h->comment }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $h->created_at->format('M d - H:i') }}</p>
                                        @if($h->user_id)
                                            <span class="text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-500 px-1.5 py-0.5 rounded font-medium">System Operator</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

        </div>
    </div>

@endsection
