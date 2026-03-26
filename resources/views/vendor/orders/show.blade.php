@extends('layouts.vendor')

@section('title', 'Inspecting Order: ' . $order->order_number)

@section('content')
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mb-10 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group transition duration-300 transform hover:-translate-y-1 print:shadow-none">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-vendor-500/5 rounded-full blur-3xl pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Order #{{ $order->order_number }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">Customer: <span class="font-bold text-slate-900 dark:text-white">{{ optional($order->user)->name ?? trim(($order->shipping_first_name ?? '').' '.($order->shipping_last_name ?? '')) ?: '—' }}</span></p>
            @if(!($onlyThisVendor ?? true))
                <p class="mt-3 text-sm text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 px-4 py-2 rounded-xl border border-amber-200 dark:border-amber-800 max-w-xl">This order includes items from other sellers. Only the store can change the main order status. Your line items are shown below.</p>
            @endif
        </div>
        <div class="relative z-10 mt-6 md:mt-0 flex flex-wrap gap-2 no-print">
             <button type="button" onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </button>
             <a href="{{ route('vendor.orders.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Return to Queue
            </a>
        </div>
    </div>

    @php
        $st = $order->status;
        $lifecycle = [
            ['key' => 'pending', 'label' => 'Pending'],
            ['key' => 'confirmed', 'label' => 'Confirmed'],
            ['key' => 'processing', 'label' => 'Processing'],
            ['key' => 'shipped', 'label' => 'Shipped'],
            ['key' => 'delivered', 'label' => 'Delivered'],
        ];
        $badTerminal = in_array($st, ['cancelled', 'refunded', 'failed'], true);
        $activeIdx = match ($st) {
            'pending' => 0,
            'confirmed' => 1,
            'processing' => 2,
            'shipped' => 3,
            'delivered' => 4,
            'completed' => 5,
            'on_hold' => 0,
            default => -1,
        };
        if ($badTerminal) {
            $activeIdx = -1;
        }
    @endphp
    <div class="mb-8 rounded-3xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-darkpanel p-6 md:p-8 shadow-sm print:shadow-none">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-6">Order lifecycle</h3>
        @if($badTerminal)
            <div class="rounded-2xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/20 px-4 py-3 text-sm text-rose-800 dark:text-rose-200">
                This order is <strong>{{ str_replace('_', ' ', $st) }}</strong>. No further fulfillment steps apply.
            </div>
        @else
            <div class="max-w-5xl mx-auto w-full">
                <div class="flex w-full items-center">
                    @foreach($lifecycle as $i => $step)
                        @php
                            $done = $activeIdx > $i;
                            $here = $activeIdx === $i;
                        @endphp
                        @if($i > 0)
                            <div class="h-1 flex-1 min-w-[8px] rounded-full {{ $activeIdx >= $i ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700' }}"></div>
                        @endif
                        <div class="shrink-0 flex h-10 w-10 items-center justify-center rounded-full text-xs font-black border-2
                            @if($done) border-emerald-600 bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300
                            @elseif($here) border-vendor-600 bg-vendor-50 dark:bg-vendor-950/50 text-vendor-800 dark:text-vendor-300 shadow-md shadow-vendor-500/15
                            @else border-slate-200 dark:border-slate-600 bg-white dark:bg-darkpanel text-slate-500 dark:text-slate-400
                            @endif
                        ">{{ $done ? '✓' : ($i + 1) }}</div>
                    @endforeach
                </div>
                <div class="flex w-full items-start mt-3">
                    @foreach($lifecycle as $i => $step)
                        @php
                            $done = $activeIdx > $i;
                            $here = $activeIdx === $i;
                        @endphp
                        @if($i > 0)
                            <div class="flex-1 min-w-[8px]"></div>
                        @endif
                        <div class="shrink-0 w-12 md:w-14 text-center text-[9px] sm:text-[10px] font-bold uppercase leading-tight {{ $done || $here ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">{{ $step['label'] }}</div>
                    @endforeach
                </div>
            </div>
            <p class="mt-6 text-xs text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4">
                <span class="font-bold text-slate-700 dark:text-slate-300">Now:</span> {{ ucfirst(str_replace('_', ' ', $st)) }}
                @if($st === 'on_hold')
                    <span class="text-amber-600 dark:text-amber-400 font-medium"> — on hold; check with the store if needed.</span>
                @endif
            </p>
        @endif
        <dl class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3 border border-slate-100 dark:border-slate-700">
                <dt class="text-slate-500 font-bold uppercase tracking-wide">Placed</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3 border border-slate-100 dark:border-slate-700">
                <dt class="text-slate-500 font-bold uppercase tracking-wide">Paid</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $order->paid_at ? $order->paid_at->format('M j, Y g:i A') : '—' }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3 border border-slate-100 dark:border-slate-700">
                <dt class="text-slate-500 font-bold uppercase tracking-wide">Shipped</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $order->shipped_at ? $order->shipped_at->format('M j, Y g:i A') : '—' }}</dd>
            </div>
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3 border border-slate-100 dark:border-slate-700">
                <dt class="text-slate-500 font-bold uppercase tracking-wide">Delivered</dt>
                <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $order->delivered_at ? $order->delivered_at->format('M j, Y g:i A') : '—' }}</dd>
            </div>
        </dl>
        @if(filled($order->transaction_id))
            <p class="mt-4 text-xs text-slate-500"><span class="font-bold text-slate-600 dark:text-slate-400">Payment ref:</span> <span class="font-mono">{{ $order->transaction_id }}</span></p>
        @endif
        @if(filled($order->shipping_method_name))
            <p class="mt-2 text-xs text-slate-500"><span class="font-bold text-slate-600 dark:text-slate-400">Shipping method:</span> {{ $order->shipping_method_name }}</p>
        @endif
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
                 <div class="absolute -right-24 -bottom-24 w-64 h-64 bg-vendor-500/10 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
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
                         <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-2 bg-slate-800 px-2 py-0.5 rounded-full inline-block">{{ number_format($commissionRate ?? 10, 1) }}% Standard Fee</p>
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
                            <div class="pointer-events-none absolute -left-[25px] top-0 w-4 h-4 rounded-full bg-slate-900 dark:bg-slate-700 border-4 border-white dark:border-darkpanel"></div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">{{ ucfirst(str_replace('_', ' ', $history->old_status ?? '—')) }} → {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</span>
                                <span class="text-xs text-slate-400 font-medium mb-2">{{ $history->created_at->format('M d, Y') }} at {{ $history->created_at->format('h:i A') }} · {{ $history->user->name ?? 'System' }}</span>
                                <p class="text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-transparent hover:border-slate-100 transition-colors">{{ $history->comment ?: '—' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar Summary -->
        <div class="xl:col-span-4 space-y-8 xl:sticky xl:top-24 no-print">
            
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-700">
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div><span class="text-slate-500">Status:</span> <span class="font-bold text-slate-900 dark:text-white uppercase">{{ $order->status }}</span></div>
                        <div><span class="text-slate-500">Payment:</span> <span class="font-bold text-slate-900 dark:text-white uppercase">{{ $order->payment_status }}</span></div>
                    </div>
                </div>
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
                            @php
                                $pst = $order->payment_status;
                                $psty = match ($pst) {
                                    'paid' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                    'pending' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/50',
                                    'failed' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800/50',
                                    default => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $psty }}">{{ $pst }}</span>
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
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-3 text-center">Fulfillment</p>
                    @if(!empty($canMarkProcessing))
                        <form method="POST" action="{{ route('vendor.orders.advance', $order->id) }}" class="mb-3 space-y-2">
                            @csrf
                            <input type="hidden" name="step" value="processing">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Note (optional, saved in history)</label>
                            <textarea name="note" rows="2" maxlength="500" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-vendor-500" placeholder="e.g. picking started, batch ID…"></textarea>
                            <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-vendor-600 text-white rounded-2xl text-sm font-bold shadow-xl transition-all block text-center">Mark as processing</button>
                        </form>
                    @endif
                    @if(!empty($canMarkShipped))
                        <form method="POST" action="{{ route('vendor.orders.advance', $order->id) }}" class="mb-3 space-y-2">
                            @csrf
                            <input type="hidden" name="step" value="shipped">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Note (optional — tracking, carrier, package ID)</label>
                            <textarea name="note" rows="2" maxlength="500" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm text-slate-800 dark:text-slate-200 p-3 focus:ring-2 focus:ring-vendor-500" placeholder="e.g. Pathao tracking: ABC123"></textarea>
                            <button type="submit" class="w-full py-4 bg-vendor-600 hover:bg-vendor-700 text-white rounded-2xl text-sm font-bold shadow-xl transition-all block text-center">Mark as shipped</button>
                        </form>
                    @endif
                    @if(empty($canMarkProcessing) && empty($canMarkShipped))
                        <p class="text-xs text-slate-500 text-center mb-3">
                            @if(!($onlyThisVendor ?? true))
                                Multi-vendor order — contact the store to change status.
                            @else
                                No status action for «{{ $order->status }}».
                            @endif
                        </p>
                    @endif
                    @if($order->user?->email)
                        <a href="mailto:{{ $order->user->email }}?subject={{ rawurlencode('Order '.$order->order_number) }}" class="w-full py-4 bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl text-sm font-bold shadow-sm transition-all block text-center hover:bg-slate-50 dark:hover:bg-slate-800 mb-3">Email customer</a>
                    @endif
                    <a href="{{ route('vendor.settings.index') }}" class="w-full py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-2xl text-sm font-bold block text-center hover:bg-slate-100 dark:hover:bg-slate-800">Store settings</a>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/40 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 flex items-center no-print">
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
<style>@media print { .no-print { display: none !important; } }</style>
@endsection
