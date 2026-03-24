@extends('layouts.vendor')

@section('title', 'Inspecting Order: ' . $order->order_number)

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group transition duration-300 transform hover:-translate-y-1">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-vendor-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Order Terminal #{{ $order->id }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">Processing order from <span class="font-bold text-slate-900 dark:text-white">Customer: {{ $order->user->name ?? $order->shipping_first_name }}</span></p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <a href="{{ route('vendor.orders.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Return to Queue
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <!-- Order Progress & Items -->
        <div class="xl:col-span-8 space-y-8">
            
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm font-bold">01</span>
                    Inventory Extraction
                </h3>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($vendorItems as $item)
                        <div class="py-6 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-2xl flex-shrink-0 border border-slate-100 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                                @if($item->product && $item->product->primary_image)
                                    <img src="{{ $item->product->display_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1 tracking-tight">{{ $item->product_name }}</h4>
                                <div class="flex items-center justify-center md:justify-start space-x-4">
                                     <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-widest">SKU: {{ $item->sku }}</span>
                                     @if($item->variant_name)
                                        <span class="px-3 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-full text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $item->variant_name }}</span>
                                     @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-center md:items-end min-w-[120px]">
                                <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">৳{{ number_format($item->subtotal, 2) }}</span>
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $item->quantity }} Units &times; ৳{{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Revenue Split Insight -->
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-slate-900/40 relative overflow-hidden group">
                 <div class="absolute -right-24 -bottom-24 w-64 h-64 bg-vendor-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                 <h3 class="text-xl font-bold font-heading mb-8 flex items-center text-vendor-400 uppercase tracking-[0.2em] border-b border-white/10 pb-4">Revenue Settlement Analysis</h3>
                 
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Sale Gross Value</span>
                        <div class="flex items-baseline">
                             <span class="text-xs font-bold text-slate-400 mr-1">৳</span>
                             <span class="text-3xl font-extrabold font-heading">{{ number_format($vendorSubtotal, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                         <span class="text-[10px] font-bold uppercase tracking-widest text-vendor-400 mb-2">Platform Contribution</span>
                         <div class="flex items-baseline">
                             <span class="text-xs font-bold text-vendor-400/50 mr-1">৳</span>
                             <span class="text-3xl font-extrabold font-heading text-vendor-400">-{{ number_format($commission, 2) }}</span>
                         </div>
                         <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-2 bg-slate-800 px-2 py-0.5 rounded-full inline-block">{{ auth()->user()->vendor->commission_rate }}% Standard Fee</p>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 mb-2 font-heading">Final Net Settlement</span>
                        <div class="flex items-baseline">
                            <span class="text-xs font-bold text-emerald-500/50 mr-1">৳</span>
                            <span class="text-5xl font-black font-heading text-emerald-400 tracking-tighter">{{ number_format($vendorEarning, 2) }}</span>
                        </div>
                    </div>
                 </div>
            </div>
            
            @if($order->statusHistory->count() > 0)
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Historical Lifecycle Events
                </h3>
                <div class="space-y-6 pl-4 border-l border-slate-100 dark:border-slate-800 ml-4 relative">
                    @foreach($order->statusHistory as $history)
                        <div class="relative">
                            <div class="absolute -left-[25px] top-0 w-4 h-4 rounded-full bg-slate-900 dark:bg-slate-700 border-4 border-white dark:border-darkpanel"></div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">{{ ucfirst($history->new_status) }}</span>
                                <span class="text-xs text-slate-400 font-medium mb-2">{{ $history->created_at->format('M d, Y') }} at {{ $history->created_at->format('h:i A') }}</span>
                                <p class="text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-transparent hover:border-slate-100 transition-colors">{{ $history->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar Summary -->
        <div class="xl:col-span-4 space-y-8 h-sticky top-24">
            
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-6 font-heading">Operations Payload</h4>
                
                <div class="space-y-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Logistics Center</span>
                        <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                             <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                             <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $order->shipping_address_line_1 }}</p>
                             <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">{{ $order->shipping_city }}, {{ $order->shipping_zip_code }}, {{ $order->shipping_country }}</p>
                             <p class="text-sm font-mono text-vendor-600 dark:text-vendor-400 mt-3 font-bold">{{ $order->shipping_phone }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Finance Overview</span>
                        <div class="flex items-center justify-between text-sm py-2 group">
                            <span class="text-slate-400 group-hover:text-slate-600 transition-colors">Payment Method</span>
                            <span class="font-black font-mono text-slate-900 dark:text-white uppercase tracking-tighter">{{ $order->payment_method }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm py-2 group">
                            <span class="text-slate-400 group-hover:text-slate-600 transition-colors">Payment Status</span>
                            <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-200 dark:border-emerald-800/50">{{ $order->payment_status }}</span>
                        </div>
                   </div>

                   @if($order->notes)
                   <div class="flex flex-col pt-4">
                        <span class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-2">Customer Direct Note</span>
                        <div class="bg-rose-50 dark:bg-rose-900/10 p-4 rounded-2xl border border-rose-100 dark:border-rose-900/50">
                             <p class="text-sm text-rose-700 dark:text-rose-400 italic">"{{ $order->notes }}"</p>
                        </div>
                   </div>
                   @endif
                </div>

                <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-3 text-center">Fulfillment Action Required?</p>
                    <button class="w-full py-4 bg-slate-900 hover:bg-black text-white rounded-2xl text-sm font-bold shadow-xl transition-all hover:-translate-y-1 block text-center mb-3 group">
                        Confirm Raw Inventory Prep
                    </button>
                    <button class="w-full py-4 bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 text-slate-500 rounded-2xl text-sm font-bold shadow-sm transition-all block text-center hover:bg-slate-50 dark:hover:bg-slate-800">
                        Raise Protocol Dispute
                    </button>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/40 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 flex items-center">
                 <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm mr-4">
                     <svg class="w-6 h-6 text-vendor-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 </div>
                 <div>
                    <h5 class="text-sm font-bold text-slate-900 dark:text-white">Shipping Protocol</h5>
                    <p class="text-xs text-slate-500 font-medium">Please ensure you print and attach the fulfillment slip to the package.</p>
                 </div>
            </div>

        </div>
    </div>
@endsection
