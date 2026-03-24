@extends('layouts.admin')
@section('title', 'Add New Product')

@section('content')

<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.products.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 transition-colors shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Create Product</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Add a new item to the platform catalog.</p>
    </div>
</div>

@if($errors->any())
<div class="mb-8 p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-900/30 dark:border-rose-800 text-rose-600 dark:text-rose-400">
    <ul class="list-disc list-inside text-sm font-medium space-y-1">
        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-10">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">

            {{-- General --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">General Information</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Product Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Short Description</label>
                        <textarea name="short_description" rows="2" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ old('short_description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Full Description</label>
                        <textarea name="description" rows="5" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ SMART IMAGE UPLOAD ═══ --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800"
                 x-data="createImageManager()">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">Product Images</h3>
                        <p class="text-xs text-slate-400 mt-1">First image becomes main. Drag to reorder before saving.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full"
                          :class="files.length ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300' : 'bg-slate-100 text-slate-500'"
                          x-text="files.length + ' image(s)'"></span>
                </div>

                {{-- Previews --}}
                <template x-if="files.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                        <template x-for="(f, idx) in files" :key="f.name + idx">
                            <div class="relative rounded-xl border-2 overflow-hidden transition-all cursor-grab active:cursor-grabbing"
                                 :class="idx === primaryIdx ? 'border-brand-500 ring-2 ring-brand-500/30' : 'border-slate-200 dark:border-slate-700'"
                                 draggable="true"
                                 @dragstart="dragIdx=idx; $event.dataTransfer.effectAllowed='move'"
                                 @dragend="dragIdx=null"
                                 @dragover.prevent @drop.prevent="onDrop(idx)">

                                <img :src="f.preview" alt="" class="w-full h-28 object-cover pointer-events-none bg-slate-100">

                                <template x-if="idx === primaryIdx">
                                    <span class="absolute top-1 left-1 text-[10px] font-bold bg-brand-600 text-white px-2 py-0.5 rounded shadow">&#9733; Main</span>
                                </template>

                                <div class="flex items-center gap-1 p-1.5 bg-slate-50 dark:bg-slate-800">
                                    <button type="button" @click="primaryIdx = idx"
                                            class="flex-1 text-[10px] font-bold py-1.5 rounded-lg transition"
                                            :class="idx === primaryIdx ? 'bg-brand-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-brand-100'">&#9733; Main</button>
                                    <button type="button" @click="removeFile(idx)"
                                            class="text-[10px] font-bold py-1.5 px-2.5 rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">&times;</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <input type="file" x-ref="fileInput" name="images[]" class="hidden" multiple accept="image/*" @change="onFilesSelected($event)">
                <input type="hidden" name="primary_image_index" :value="primaryIdx">

                {{-- Drop zone --}}
                <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all"
                     :class="dropHover ? 'border-brand-400 bg-brand-50/50 dark:bg-brand-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-brand-400'"
                     @dragover.prevent="dropHover=true" @dragleave.prevent="dropHover=false"
                     @drop.prevent="dropHover=false; onDropFiles($event)">
                    <svg class="mx-auto w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 16V4m0 0L8 8m4-4l4 4M4 14v4a2 2 0 002 2h12a2 2 0 002-2v-4"/></svg>
                    <button type="button" @click="$refs.fileInput.click()"
                            class="text-sm font-bold text-brand-600 dark:text-brand-400 hover:underline">Choose files</button>
                    <span class="text-sm text-slate-400 ml-1">or drag &amp; drop here</span>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP &bull; Max 5 MB each</p>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Pricing & Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Selling Price (৳) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Compare Price (৳)</label>
                        <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Cost Price (৳)</label>
                        <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">SKU Code <span class="text-rose-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 uppercase font-mono tracking-widest">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Stock Quantity <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity', 0) }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Low Stock Alert</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="track_quantity" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('track_quantity',true)?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Track Stock</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="allow_backorder" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('allow_backorder')?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Allow Backorder</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_taxable" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('is_taxable',true)?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Taxable</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_digital" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" {{ old('is_digital')?'checked':'' }}><span class="text-sm font-bold text-slate-700 dark:text-slate-300">Digital Product</span></label>
                </div>
            </div>

            {{-- Shipping & Dimensions --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Shipping & Dimensions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Weight (g)</label>
                        <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Length (cm)</label>
                        <input type="number" step="0.01" name="length" value="{{ old('length') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Width (cm)</label>
                        <input type="number" step="0.01" name="width" value="{{ old('width') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Height (cm)</label>
                        <input type="number" step="0.01" name="height" value="{{ old('height') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">SEO / Meta</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="70" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="2" maxlength="160" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">{{ old('meta_description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="comma, separated, keywords">
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-8">
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Categorization</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            @foreach(['draft'=>'Draft Mode','approved'=>'Approved / Live','pending'=>'Pending Review','rejected'=>'Rejected'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('status','draft')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Vendor <span class="text-rose-500">*</span></label>
                        <select name="vendor_id" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">Select...</option>
                            @foreach($vendors as $id=>$name)<option value="{{ $id }}" {{ old('vendor_id')==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">Select...</option>
                            @foreach($categories as $id=>$name)<option value="{{ $id }}" {{ old('category_id')==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Brand</label>
                        <select name="brand_id" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">None</option>
                            @foreach($brands as $id=>$name)<option value="{{ $id }}" {{ old('brand_id')==$id?'selected':'' }}>{{ $name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Visibility</label>
                        <select name="visibility" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            @foreach(['public'=>'Public','hidden'=>'Hidden','catalog_only'=>'Catalog Only','search_only'=>'Search Only'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('visibility','public')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative"><input type="checkbox" name="is_active" value="1" class="sr-only" {{ old('is_active',true)?'checked':'' }}><div class="block w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded-full target-bg"></div><div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"></div></div>
                            <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <div class="relative"><input type="checkbox" name="is_featured" value="1" class="sr-only" {{ old('is_featured')?'checked':'' }}><div class="block w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded-full target-bg"></div><div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"></div></div>
                            <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">Homepage Featured</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex justify-center sticky top-24">
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-1">Save Product Data</button>
            </div>
        </div>
    </div>
</form>

<script>
function createImageManager() {
    return {
        files: [],
        primaryIdx: 0,
        dragIdx: null,
        dropHover: false,

        onFilesSelected(e) {
            this.addFiles(e.target.files);
            e.target.value = '';
        },

        onDropFiles(e) {
            this.addFiles(e.dataTransfer.files);
        },

        addFiles(fileList) {
            var self = this;
            Array.from(fileList).forEach(function(f) {
                if (!f.type.startsWith('image/')) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    self.files.push({name: f.name, file: f, preview: ev.target.result});
                    self.syncInput();
                };
                reader.readAsDataURL(f);
            });
        },

        removeFile(idx) {
            this.files.splice(idx, 1);
            if (this.primaryIdx >= this.files.length) this.primaryIdx = Math.max(0, this.files.length - 1);
            this.syncInput();
        },

        onDrop(toIdx) {
            if (this.dragIdx === null || this.dragIdx === toIdx) return;
            var wasPrimary = this.dragIdx === this.primaryIdx;
            var moved = this.files.splice(this.dragIdx, 1)[0];
            this.files.splice(toIdx, 0, moved);
            if (wasPrimary) this.primaryIdx = toIdx;
            this.dragIdx = null;
            this.syncInput();
        },

        syncInput() {
            var dt = new DataTransfer();
            this.files.forEach(function(f){ dt.items.add(f.file); });
            this.$refs.fileInput.files = dt.files;
        }
    };
}
</script>

<style>
input:checked ~ .dot { transform: translateX(100%); }
input:checked ~ .target-bg { background-color: #0d9488; }
</style>

@endsection
