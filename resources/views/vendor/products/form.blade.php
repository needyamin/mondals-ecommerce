@extends('layouts.vendor')

@section('title', $product ? 'Edit Product' : 'Add New Product')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group transition duration-300">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-vendor-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">{{ $product ? 'Edit Your Product' : 'Create New Listing' }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light">Enter the details of your collection's product for approval.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <a href="{{ route('vendor.products.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancel
            </a>
        </div>
    </div>

    <!-- Main Entry Form -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
        
        <div class="xl:col-span-8">
            <form action="{{ $product ? route('vendor.products.update', $product->id) : route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                @csrf
                @if($product) @method('PUT') @endif

                <!-- Section 1: Visual Experience -->
                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm relative overflow-hidden group">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm font-bold">01</span>
                        Product Essentials
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Product Title</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Signature Leather Carryall" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner transition-all text-slate-900 dark:text-white" required>
                            @error('name')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Stock Keeping Unit (SKU)</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="MNDL-BAG-BLK-001" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base font-mono focus:ring-2 focus:ring-vendor-500 shadow-inner transition-all text-slate-900 dark:text-white uppercase" required>
                            @error('sku')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Primary Category</label>
                            <select name="category_id" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner transition-all text-slate-900 dark:text-white" required>
                                <option value="">Select Categories</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $product->category_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                             <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Brand</label>
                             <select name="brand_id" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner transition-all text-slate-900 dark:text-white">
                                <option value="">No Brand (Generic)</option>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}" {{ old('brand_id', $product->brand_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Current Stock Level</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? '') }}" placeholder="0" min="0" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner transition-all text-slate-900 dark:text-white" required>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Narrative Entry -->
                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mr-3 text-sm font-bold">02</span>
                        Product Narrative & Description
                    </h3>
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Short Preview</label>
                            <textarea name="short_description" rows="2" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner resize-none text-slate-900 dark:text-white" placeholder="A compelling 1-2 sentence hook for browse lists.">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">Full Detailed Story</label>
                            <textarea name="description" rows="8" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" placeholder="Tell the customer everything they need to know about the quality, utility, and features.">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Value Assignment -->
                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center">
                        <span class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center mr-3 text-sm font-bold">03</span>
                        Commercial Pricing (BDT)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1 text-vendor-600 dark:text-vendor-400">Current Selling Price</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">৳</span>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" placeholder="0.00" class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-2xl font-bold focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest ml-1">RRP / Compare Price</label>
                            <div class="relative opacity-60">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">৳</span>
                                <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" placeholder="0.00" class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-2xl font-bold focus:ring-2 focus:ring-slate-400 shadow-inner text-slate-900 dark:text-white">
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-2 italic">Shows as a strike-through price if higher than sale price.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-vendor-600 to-teal-600 text-white rounded-2xl text-xl font-bold shadow-2xl shadow-vendor-600/30 hover:shadow-vendor-600/50 hover:scale-[1.01] transition-all transform flex items-center justify-center group tracking-tight">
                        <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        {{ $product ? 'Push Updated Catalog Entry' : 'Submit Catalog Entry for Approval' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Actions/Info -->
        <div class="xl:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-slate-900/30">
                <h4 class="text-lg font-bold font-heading mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-vendor-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
                    Media Pipeline
                </h4>
                <p class="text-slate-400 text-sm mb-6 font-light leading-relaxed">High-resolution photography increases conversion rates by up to 80% on our storefront.</p>
                
                <div class="border-2 border-dashed border-slate-700 rounded-2xl p-10 flex flex-col items-center justify-center text-center cursor-pointer hover:border-vendor-500 transition-colors group mb-6 bg-slate-800/20">
                    <svg class="w-12 h-12 text-slate-600 group-hover:text-vendor-400 mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500 group-hover:text-white transition-colors">Bulk Photo Upload</span>
                    <p class="text-[10px] text-slate-600 mt-2 font-bold uppercase tracking-widest">(Gallery management coming soon)</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3 bg-slate-800/40 p-3 rounded-xl">
                         <div class="w-2 h-2 rounded-full bg-vendor-500 animate-pulse"></div>
                         <span class="text-xs font-medium text-slate-300">Minimum 800x800 for Zoom</span>
                    </div>
                    <div class="flex items-center space-x-3 bg-slate-800/40 p-3 rounded-xl opacity-60">
                         <div class="w-2 h-2 rounded-full bg-slate-600"></div>
                         <span class="text-xs font-medium text-slate-400">White Background Optimized</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h4 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Approval Guidelines</h4>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Products are approved within 2-4 business hours by our review team.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Authentic descriptions and high-quality SKUs are prioritized for homepage feature spots.</span>
                    </li>
                    <li class="flex items-start opacity-40">
                        <svg class="w-5 h-5 mr-3 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-xs text-slate-500 dark:text-slate-400 italic font-medium">Duplicate listings or stock photo spam will lead to account restrictions.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
