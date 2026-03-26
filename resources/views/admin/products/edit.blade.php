@extends('layouts.admin')
@section('title', 'Edit Product')

@section('content')
<div x-data="productEditor()" x-init="init()">

<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Edit Product</h2>
            <p class="text-slate-400 text-sm mt-0.5">
                <span class="font-mono">{{ $item->sku }}</span>
                &bull; Created {{ $item->created_at->diffForHumans() }}
                &bull; <span x-text="views"></span> views &bull; <span x-text="sales"></span> sold
            </p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('product.detail', $item->slug) }}" target="_blank" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            View on store
        </a>
        <template x-if="dirty">
            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-full animate-pulse">Unsaved changes</span>
        </template>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-900/30 dark:border-rose-800 text-rose-600 dark:text-rose-400">
    <ul class="list-disc list-inside text-sm font-medium space-y-1">
        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="pb-10" @input="dirty=true" @change="dirty=true">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">

            {{-- ═══ GENERAL ═══ --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    General Information
                </h3>
                <div class="space-y-5">
                    <div>
                        <label class="lbl">Product Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="name" required class="inp">
                        <p class="text-xs text-slate-400 mt-1">Slug: <span class="font-mono text-brand-600 dark:text-brand-400" x-text="slug"></span></p>
                    </div>
                    <div>
                        <label class="lbl">Short Description</label>
                        <textarea name="short_description" rows="2" class="inp" maxlength="500" x-model="shortDesc">{{ old('short_description', $item->short_description) }}</textarea>
                        <p class="text-xs text-right mt-1" :class="shortDesc.length > 450 ? 'text-amber-500' : 'text-slate-400'" x-text="shortDesc.length + '/500'"></p>
                    </div>
                    <div>
                        <label class="lbl">Full Description</label>
                        <textarea name="description" rows="6" class="inp">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ IMAGE MANAGER ═══ --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Product Images
                    </h3>
                    <span class="badge" :class="totalImgCount() ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300' : 'bg-slate-100 text-slate-500'" x-text="totalImgCount() + ' image(s)'"></span>
                </div>
                <p class="text-xs text-slate-400 mb-5">Drag to reorder &bull; &#9733; = main image &bull; &#10005; to mark for removal</p>

                {{-- Existing --}}
                <template x-if="existing.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-5">
                        <template x-for="(img, idx) in existing" :key="img.id">
                            <div class="relative rounded-xl border-2 overflow-hidden transition-all duration-200"
                                 :class="img.removed ? 'opacity-30 scale-95 border-rose-300 grayscale' : (img.id == primaryId ? 'border-brand-500 ring-2 ring-brand-500/30 shadow-md' : 'border-slate-200 dark:border-slate-700 hover:border-slate-400')"
                                 draggable="true" :style="dragIdx===idx ? 'opacity:0.5' : ''"
                                 @dragstart="dragIdx=idx; $event.dataTransfer.effectAllowed='move'"
                                 @dragend="dragIdx=null"
                                 @dragover.prevent="dragOver=idx"
                                 @dragleave="dragOver=null"
                                 @drop.prevent="onDropExisting(idx)">

                                <div class="relative group cursor-grab active:cursor-grabbing">
                                    <img :src="img.url" alt="" class="w-full h-32 object-cover bg-slate-100">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                    <span class="absolute top-1.5 right-1.5 text-[9px] font-bold bg-black/50 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition" x-text="'#' + (idx+1)"></span>
                                </div>

                                <template x-if="img.id == primaryId && !img.removed">
                                    <span class="absolute top-1.5 left-1.5 text-[10px] font-bold bg-brand-600 text-white px-2 py-0.5 rounded shadow">&#9733; Main</span>
                                </template>

                                <div class="flex items-center gap-1 p-1.5 bg-slate-50 dark:bg-slate-800">
                                    <button type="button" @click="if(!img.removed) primaryId=img.id"
                                            class="flex-1 text-[10px] font-bold py-1.5 rounded-lg transition"
                                            :class="img.id == primaryId ? 'bg-brand-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-brand-100'">&#9733;</button>
                                    <button type="button" @click="toggleRemove(img)"
                                            class="text-[10px] font-bold py-1.5 px-2 rounded-lg transition"
                                            :class="img.removed ? 'bg-emerald-600 text-white' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300'"
                                            x-text="img.removed ? 'Undo' : '&#10005;'"></button>
                                </div>

                                <input type="hidden" name="image_order[]" :value="img.id" :disabled="img.removed">
                                <template x-if="img.removed">
                                    <input type="hidden" name="remove_images[]" :value="img.id">
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="existing.length === 0 && newFiles.length === 0">
                    <div class="text-center py-8 text-slate-400">
                        <svg class="mx-auto w-12 h-12 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm font-medium">No images yet</p>
                    </div>
                </template>

                {{-- New files preview --}}
                <template x-if="newFiles.length > 0">
                    <div class="mb-5">
                        <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New uploads (save to apply)
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <template x-for="(nf, nIdx) in newFiles" :key="nf.id">
                                <div class="relative rounded-xl border-2 border-dashed border-emerald-300 dark:border-emerald-700 overflow-hidden group">
                                    <img :src="nf.preview" alt="" class="w-full h-32 object-cover bg-slate-100">
                                    <div class="p-1.5 bg-emerald-50 dark:bg-emerald-900/20 flex items-center gap-1">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 truncate" x-text="nf.name"></p>
                                            <p class="text-[9px] text-emerald-500" x-text="nf.size"></p>
                                        </div>
                                        <button type="button" @click="removeNewFile(nIdx)"
                                                class="text-[10px] font-bold py-1 px-2 rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 hover:bg-rose-200 transition flex-shrink-0">&#10005;</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <input type="file" x-ref="fileInput" name="images[]" class="hidden" multiple accept="image/*" @change="onFilesSelected($event)">

                <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all cursor-pointer"
                     :class="dropHover ? 'border-brand-400 bg-brand-50/50 dark:bg-brand-900/20 scale-[1.01]' : 'border-slate-300 dark:border-slate-600 hover:border-brand-400'"
                     @click="$refs.fileInput.click()"
                     @dragover.prevent="dropHover=true" @dragleave.prevent="dropHover=false"
                     @drop.prevent="dropHover=false; onDropFiles($event)">
                    <svg class="mx-auto w-8 h-8 mb-2" :class="dropHover ? 'text-brand-500' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm"><span class="font-bold text-brand-600 dark:text-brand-400">Click to browse</span> <span class="text-slate-400">or drag &amp; drop</span></p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP &bull; Max 5 MB each</p>
                </div>

                <template x-if="primaryId">
                    <input type="hidden" name="set_primary" :value="primaryId">
                </template>
            </div>

            {{-- ═══ PRICING ═══ --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pricing & Inventory
                </h3>

                {{-- Live pricing summary --}}
                <div class="grid grid-cols-3 gap-3 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">Discount</p>
                        <p class="text-lg font-extrabold" :class="discountPct > 0 ? 'text-rose-600' : 'text-slate-400'" x-text="discountPct > 0 ? discountPct + '%' : '—'"></p>
                    </div>
                    <div class="text-center border-x border-slate-200 dark:border-slate-700">
                        <p class="text-xs text-slate-500 mb-1">Profit Margin</p>
                        <p class="text-lg font-extrabold" :class="marginPct > 0 ? 'text-emerald-600' : 'text-slate-400'" x-text="marginPct > 0 ? marginPct + '%' : '—'"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">Stock Status</p>
                        <p class="text-sm font-extrabold" :class="stockClass" x-text="stockLabel"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="lbl">Selling Price (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="price" x-model.number="price" required class="inp">
                    </div>
                    <div>
                        <label class="lbl">Compare / MRP (৳)</label>
                        <input type="number" step="0.01" name="compare_price" x-model.number="comparePrice" class="inp">
                    </div>
                    <div>
                        <label class="lbl">Cost Price (৳)</label>
                        <input type="number" step="0.01" name="cost_price" x-model.number="costPrice" class="inp">
                    </div>
                    <div>
                        <label class="lbl">SKU <span class="text-rose-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $item->sku) }}" required class="inp uppercase font-mono tracking-widest">
                    </div>
                    <div>
                        <label class="lbl">Stock Qty <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" x-model.number="qty" required class="inp">
                    </div>
                    <div>
                        <label class="lbl">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" x-model.number="lowStock" class="inp">
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <label class="chk"><input type="checkbox" name="track_quantity" value="1" class="cb" {{ old('track_quantity',$item->track_quantity)?'checked':'' }}> Track Stock</label>
                    <label class="chk"><input type="checkbox" name="allow_backorder" value="1" class="cb" {{ old('allow_backorder',$item->allow_backorder)?'checked':'' }}> Backorder</label>
                    <label class="chk"><input type="checkbox" name="is_taxable" value="1" class="cb" {{ old('is_taxable',$item->is_taxable)?'checked':'' }}> Taxable</label>
                    <label class="chk"><input type="checkbox" name="is_digital" value="1" class="cb" x-model="isDigital" {{ old('is_digital',$item->is_digital)?'checked':'' }}> Digital</label>
                </div>
            </div>

            {{-- ═══ SHIPPING (hidden when digital) ═══ --}}
            <div x-show="!isDigital" x-transition class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Shipping & Dimensions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    <div><label class="lbl">Weight (g)</label><input type="number" step="0.01" name="weight" value="{{ old('weight', $item->weight) }}" class="inp"></div>
                    <div><label class="lbl">Length (cm)</label><input type="number" step="0.01" name="length" value="{{ old('length', $item->length) }}" class="inp"></div>
                    <div><label class="lbl">Width (cm)</label><input type="number" step="0.01" name="width" value="{{ old('width', $item->width) }}" class="inp"></div>
                    <div><label class="lbl">Height (cm)</label><input type="number" step="0.01" name="height" value="{{ old('height', $item->height) }}" class="inp"></div>
                </div>
            </div>

            {{-- ═══ SEO ═══ --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        SEO / Meta
                    </h3>
                    <button type="button" @click="autoSeo()" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">Auto-fill from name</button>
                </div>
                {{-- Google preview --}}
                <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <p class="text-xs text-slate-400 mb-2">Google Preview</p>
                    <p class="text-blue-700 dark:text-blue-400 text-base font-medium truncate" x-text="metaTitle || name || 'Product Title'"></p>
                    <p class="text-emerald-700 dark:text-emerald-500 text-xs truncate">{{ url('/product') }}/<span x-text="slug"></span></p>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mt-1 line-clamp-2" x-text="metaDesc || shortDesc || 'No description set'"></p>
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="lbl">Meta Title</label>
                        <input type="text" name="meta_title" x-model="metaTitle" maxlength="70" class="inp">
                        <p class="text-xs text-right mt-1" :class="metaTitle.length > 60 ? 'text-amber-500' : 'text-slate-400'" x-text="metaTitle.length + '/70'"></p>
                    </div>
                    <div>
                        <label class="lbl">Meta Description</label>
                        <textarea name="meta_description" rows="2" x-model="metaDesc" maxlength="160" class="inp"></textarea>
                        <p class="text-xs text-right mt-1" :class="metaDesc.length > 140 ? 'text-amber-500' : 'text-slate-400'" x-text="metaDesc.length + '/160'"></p>
                    </div>
                    <div>
                        <label class="lbl">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $item->meta_keywords) }}" class="inp" placeholder="comma, separated, keywords">
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT SIDEBAR ═══ --}}
        <div class="space-y-8">
            {{-- Publish --}}
            <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-5">Publish</h3>
                <div class="space-y-4">
                    <div>
                        <label class="lbl">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="inp">
                            @foreach(['approved'=>'Approved / Live','pending'=>'Pending Review','draft'=>'Draft Mode','rejected'=>'Rejected'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('status',$item->status)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="lbl">Visibility</label>
                        <select name="visibility" class="inp">
                            @foreach(['public'=>'Public','hidden'=>'Hidden','catalog_only'=>'Catalog Only','search_only'=>'Search Only'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('visibility',$item->visibility)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-6 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_active" value="1" class="cb" {{ old('is_active',$item->is_active)?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Active</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_featured" value="1" class="cb" {{ old('is_featured',$item->is_featured)?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Featured</span></label>
                    </div>
                </div>
            </div>

            {{-- Categories --}}
            <div class="bg-white dark:bg-darkpanel p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-5">Organization</h3>
                <div class="space-y-4">
                    <div>
                        <label class="lbl">Vendor <span class="text-rose-500">*</span></label>
                        <select name="vendor_id" required class="inp">
                            <option value="">Select...</option>
                            @foreach($vendors as $id=>$name)<option value="{{ $id }}" {{ old('vendor_id',$item->vendor_id)==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="lbl">Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="inp">
                            <option value="">Select...</option>
                            @foreach($categories as $id=>$name)<option value="{{ $id }}" {{ old('category_id',$item->category_id)==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="lbl">Brand</label>
                        <select name="brand_id" class="inp">
                            <option value="">None</option>
                            @foreach($brands as $id=>$name)<option value="{{ $id }}" {{ old('brand_id',$item->brand_id)==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="sticky top-20 space-y-3">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.products.index') }}" class="block w-full text-center text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 py-2">Cancel</a>
            </div>
        </div>
    </div>
</form>
</div>

<script>
function productEditor() {
    var imgData = @json($item->images->sortBy('sort_order')->values()->map(fn($i) => ['id'=>$i->id,'url'=>$i->display_url,'is_primary'=>(bool)$i->is_primary]));
    var primary = null;
    imgData.forEach(function(i){ if(i.is_primary) primary = i.id; });

    return {
        dirty: false,
        views: {{ $item->views_count }},
        sales: {{ $item->sales_count }},
        name: @json(old('name', $item->name)),
        shortDesc: @json(old('short_description', $item->short_description ?? '')),
        price: {{ old('price', $item->price) ?: 0 }},
        comparePrice: {{ old('compare_price', $item->compare_price) ?: 0 }},
        costPrice: {{ old('cost_price', $item->cost_price) ?: 0 }},
        qty: {{ old('quantity', $item->quantity) ?: 0 }},
        lowStock: {{ old('low_stock_threshold', $item->low_stock_threshold) ?: 5 }},
        isDigital: {{ old('is_digital', $item->is_digital) ? 'true' : 'false' }},
        metaTitle: @json(old('meta_title', $item->meta_title ?? '')),
        metaDesc: @json(old('meta_description', $item->meta_description ?? '')),

        existing: imgData.map(function(i){ return {id:i.id, url:i.url, removed:false}; }),
        primaryId: primary,
        newFiles: [],
        dragIdx: null,
        dragOver: null,
        dropHover: false,
        fileCounter: 0,

        init() {
            var self = this;
            this.$nextTick(function(){ self.dirty = false; });
            var form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', function () { self.syncNativeInput(); });
            }
        },

        get slug() {
            return this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        },

        get discountPct() {
            if (!this.comparePrice || this.comparePrice <= this.price) return 0;
            return Math.round(((this.comparePrice - this.price) / this.comparePrice) * 100);
        },

        get marginPct() {
            if (!this.costPrice || !this.price || this.costPrice >= this.price) return 0;
            return Math.round(((this.price - this.costPrice) / this.price) * 100);
        },

        get stockLabel() {
            if (this.qty <= 0) return 'Out of Stock';
            if (this.qty <= this.lowStock) return 'Low Stock';
            return 'In Stock';
        },

        get stockClass() {
            if (this.qty <= 0) return 'text-rose-600';
            if (this.qty <= this.lowStock) return 'text-amber-600';
            return 'text-emerald-600';
        },

        autoSeo() {
            if (!this.name) return;
            this.metaTitle = this.name.substring(0, 70);
            this.metaDesc = (this.shortDesc || this.name).substring(0, 160);
        },

        totalImgCount() {
            return this.existing.filter(function(i){return !i.removed;}).length + this.newFiles.length;
        },

        toggleRemove(img) {
            img.removed = !img.removed;
            if (img.removed && this.primaryId == img.id) {
                var next = this.existing.find(function(i){return !i.removed;});
                this.primaryId = next ? next.id : null;
            }
        },

        onDropExisting(toIdx) {
            if (this.dragIdx === null || this.dragIdx === toIdx) return;
            var moved = this.existing.splice(this.dragIdx, 1)[0];
            this.existing.splice(toIdx, 0, moved);
            this.dragIdx = null;
            this.dragOver = null;
        },

        onFilesSelected(e) {
            var fl = Array.from(e.target.files);
            e.target.value = '';
            this.addFiles(fl);
        },

        onDropFiles(e) {
            this.addFiles(e.dataTransfer.files);
        },

        addFiles(fileList) {
            var self = this;
            Array.from(fileList).forEach(function(f) {
                if (!f.type.startsWith('image/')) return;
                var id = 'new-' + (++self.fileCounter);
                var sz = f.size < 1048576 ? (f.size/1024).toFixed(0) + ' KB' : (f.size/1048576).toFixed(1) + ' MB';
                var idx = self.newFiles.length;
                self.newFiles.push({id: id, name: f.name, file: f, preview: '', size: sz});
                var reader = new FileReader();
                reader.onload = function(ev) {
                    self.newFiles[idx].preview = ev.target.result;
                };
                reader.readAsDataURL(f);
            });
            this.syncNativeInput();
        },

        removeNewFile(idx) {
            this.newFiles.splice(idx, 1);
            this.syncNativeInput();
        },

        syncNativeInput() {
            var dt = new DataTransfer();
            this.newFiles.forEach(function(nf){ dt.items.add(nf.file); });
            this.$refs.fileInput.files = dt.files;
        }
    };
}
</script>

<style>
.lbl{display:block;font-size:.875rem;font-weight:700;margin-bottom:.375rem}
.inp{width:100%;border:1px solid #e2e8f0;border-radius:.75rem;padding:.625rem 1rem;font-size:.875rem;transition:all .15s}
.inp:focus{outline:none;box-shadow:0 0 0 2px rgba(99,102,241,.4);border-color:#6366f1}
.cb{border-radius:.25rem;border:1px solid #cbd5e1;color:#6366f1}
.chk{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.875rem;font-weight:700}
.badge{font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px}
</style>

@endsection
