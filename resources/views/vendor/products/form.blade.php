@extends('layouts.vendor')

@section('title', $product ? 'Edit Product' : 'Add New Product')

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

    <div class="mb-10 flex flex-col md:flex-row justify-between items-center group transition duration-300">
        <div class="z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $product ? 'Edit listing' : 'New listing' }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">{{ $product ? 'Update details — changes may require re-approval.' : 'Add a product to your catalog for admin review.' }}</p>
        </div>
        <div class="mt-6 md:mt-0 flex flex-wrap items-center justify-center gap-3">
            @if($product)
                <div class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-darkpanel text-slate-600 dark:text-slate-400 font-bold shadow-sm text-xs uppercase tracking-widest">
                    Status: <span class="text-vendor-600 dark:text-vendor-400">{{ ucfirst($product->status) }}</span>
                </div>
            @endif
            <a href="{{ route('vendor.products.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to inventory
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        <div class="xl:col-span-9 space-y-8">
            <form action="{{ $product ? route('vendor.products.update', $product->id) : route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @if($product) @method('PUT') @endif

                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 md:p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-vendor-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <h3 class="relative text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-100 dark:bg-vendor-900/40 text-vendor-700 dark:text-vendor-300 text-xs font-extrabold">01</span>
                        Product essentials
                    </h3>
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Product title</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Signature leather carryall" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                            @error('name')<p class="text-xs text-rose-500 font-medium ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="MNDL-BAG-BLK-001" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm font-mono uppercase tracking-tighter focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                            @error('sku')<p class="text-xs text-rose-500 font-medium ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Category</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                                <option value="">Select category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $product->category_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-rose-500 font-medium ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Brand</label>
                            <select name="brand_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                                <option value="">No brand</option>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}" {{ old('brand_id', $product->brand_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Stock quantity</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? '') }}" placeholder="0" min="0" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                        </div>
                        @include('vendor.products.gallery', ['product' => $product])
                    </div>
                </div>

                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 md:p-8 shadow-sm">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-100 dark:bg-vendor-900/40 text-vendor-700 dark:text-vendor-300 text-xs font-extrabold">02</span>
                        Description
                    </h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Short preview</label>
                            <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner resize-none text-slate-900 dark:text-white" placeholder="One or two lines for listing cards.">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Full description</label>
                            <textarea name="description" rows="8" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" placeholder="Details, materials, care, sizing…">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 md:p-8 shadow-sm">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-100 dark:bg-vendor-900/40 text-vendor-700 dark:text-vendor-300 text-xs font-extrabold">03</span>
                        Pricing & options
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-vendor-600 dark:text-vendor-400 uppercase tracking-widest ml-1 mb-2 block">Price</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Compare at</label>
                            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Cost</label>
                            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Low stock alert</label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" min="0" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Visibility</label>
                            <select name="visibility" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                                @foreach(['public'=>'Public','hidden'=>'Hidden','catalog_only'=>'Catalog only','search_only'=>'Search only'] as $v => $l)
                                    <option value="{{ $v }}" {{ old('visibility', $product->visibility ?? 'public') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Type</label>
                            <select name="is_digital" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                                <option value="0" {{ old('is_digital', $product->is_digital ?? false) ? '' : 'selected' }}>Physical</option>
                                <option value="1" {{ old('is_digital', $product->is_digital ?? false) ? 'selected' : '' }}>Digital</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer"><input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-vendor-600 focus:ring-vendor-500" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>Active</label>
                        <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer"><input type="checkbox" name="track_quantity" value="1" class="rounded border-slate-300 text-vendor-600 focus:ring-vendor-500" {{ old('track_quantity', $product->track_quantity ?? true) ? 'checked' : '' }}>Track stock</label>
                        <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer"><input type="checkbox" name="allow_backorder" value="1" class="rounded border-slate-300 text-vendor-600 focus:ring-vendor-500" {{ old('allow_backorder', $product->allow_backorder ?? false) ? 'checked' : '' }}>Backorder</label>
                        <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer"><input type="checkbox" name="is_taxable" value="1" class="rounded border-slate-300 text-vendor-600 focus:ring-vendor-500" {{ old('is_taxable', $product->is_taxable ?? true) ? 'checked' : '' }}>Taxable</label>
                    </div>
                </div>

                <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 md:p-8 shadow-sm">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-100 dark:bg-vendor-900/40 text-vendor-700 dark:text-vendor-300 text-xs font-extrabold">04</span>
                        Shipping & SEO
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Weight (g)</label>
                            <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Length (cm)</label>
                            <input type="number" step="0.01" name="length" value="{{ old('length', $product->length ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Width (cm)</label>
                            <input type="number" step="0.01" name="width" value="{{ old('width', $product->width ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Height (cm)</label>
                            <input type="number" step="0.01" name="height" value="{{ old('height', $product->height ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Meta title</label>
                            <input type="text" maxlength="70" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Meta description</label>
                            <textarea name="meta_description" maxlength="160" rows="2" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Meta keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}" placeholder="keyword, keyword" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-vendor-600 hover:bg-vendor-700 text-white rounded-2xl text-base font-bold shadow-lg shadow-vendor-500/30 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ $product ? 'Save changes' : 'Submit for approval' }}
                </button>
            </form>
        </div>

        <div class="xl:col-span-3 space-y-6">
            <div class="bg-vendor-600 dark:bg-vendor-900/40 rounded-3xl p-6 text-white shadow-xl shadow-vendor-600/20 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-vendor-200 mb-2">Checklist</p>
                <ul class="relative space-y-3 text-sm text-vendor-50 font-medium">
                    <li class="flex gap-2"><span class="text-vendor-200">✓</span> Square image 800×800px or larger</li>
                    <li class="flex gap-2"><span class="text-vendor-200">✓</span> Unique SKU</li>
                    <li class="flex gap-2"><span class="text-vendor-200">✓</span> Accurate price & stock</li>
                </ul>
            </div>
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Shortcuts</p>
                <div class="space-y-2">
                    <a href="{{ route('vendor.products.index') }}" class="flex items-center justify-center py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition">All products</a>
                    <a href="{{ route('vendor.orders.index') }}" class="flex items-center justify-center py-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">Orders</a>
                    <a href="{{ route('vendor.settings.index') }}" class="flex items-center justify-center py-3 rounded-xl border border-vendor-200 dark:border-vendor-800 text-vendor-700 dark:text-vendor-300 text-sm font-bold hover:bg-vendor-50 dark:hover:bg-vendor-900/20 transition">Store settings</a>
                </div>
            </div>
        </div>
    </div>
@endsection
