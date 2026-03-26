@php
    $galleryPayload = [];
    if ($product) {
        foreach ($product->images->sortBy('sort_order')->values() as $i) {
            $galleryPayload[] = ['id' => $i->id, 'url' => $i->display_url, 'is_primary' => (bool) $i->is_primary];
        }
    }
@endphp

@if($product)
<div class="md:col-span-2 space-y-3" x-data="vendorProductGalleryEditor(@js($galleryPayload))">
    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Product images</label>
    @if($product->primary_image)
        <p class="text-xs text-slate-500 dark:text-slate-400">Main storefront image updates when you set &#9733; Main and save.</p>
    @endif

    <div class="flex items-center justify-between mb-1">
        <p class="text-xs text-slate-400">Drag to reorder &bull; &#9733; = main &bull; &#10005; marks removal on save</p>
        <span class="text-xs font-bold px-3 py-1 rounded-full"
              :class="totalImgCount() ? 'bg-vendor-100 text-vendor-700 dark:bg-vendor-900/40 dark:text-vendor-300' : 'bg-slate-100 text-slate-500'"
              x-text="totalImgCount() + ' image(s)'"></span>
    </div>

    <template x-if="existing.length > 0">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
            <template x-for="(img, idx) in existing" :key="img.id">
                <div class="relative rounded-xl border-2 overflow-hidden transition-all"
                     :class="img.removed ? 'opacity-30 scale-95 border-rose-300 grayscale' : (img.id == primaryId ? 'border-vendor-500 ring-2 ring-vendor-500/30' : 'border-slate-200 dark:border-slate-700')"
                     draggable="true"
                     @dragstart="dragIdx=idx; $event.dataTransfer.effectAllowed='move'"
                     @dragend="dragIdx=null"
                     @dragover.prevent
                     @drop.prevent="onDropExisting(idx)">
                    <div class="relative group cursor-grab active:cursor-grabbing">
                        <img :src="img.url" alt="" class="w-full h-28 object-cover bg-slate-100 pointer-events-none">
                    </div>
                    <template x-if="img.id == primaryId && !img.removed">
                        <span class="absolute top-1 left-1 text-[10px] font-bold bg-vendor-600 text-white px-2 py-0.5 rounded shadow">&#9733; Main</span>
                    </template>
                    <div class="flex items-center gap-1 p-1.5 bg-slate-50 dark:bg-slate-800">
                        <button type="button" @click="if(!img.removed) primaryId=img.id"
                                class="flex-1 text-[10px] font-bold py-1.5 rounded-lg transition"
                                :class="img.id == primaryId ? 'bg-vendor-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'">&#9733;</button>
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

    <template x-if="newFiles.length > 0">
        <div class="mb-4">
            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2">New uploads (save to apply)</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <template x-for="(nf, nIdx) in newFiles" :key="nf.id">
                    <div class="relative rounded-xl border-2 border-dashed border-emerald-300 dark:border-emerald-700 overflow-hidden">
                        <img :src="nf.preview || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'" alt="" class="w-full h-28 object-cover bg-slate-100">
                        <div class="p-1.5 bg-emerald-50 dark:bg-emerald-900/20 flex items-center gap-1">
                            <p class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 truncate flex-1" x-text="nf.name"></p>
                            <button type="button" @click="removeNewFile(nIdx)" class="text-[10px] font-bold py-1 px-2 rounded-lg bg-rose-100 text-rose-700">&#10005;</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <input type="file" x-ref="fileInput" name="images[]" class="hidden" multiple accept="image/*" @change="onFilesSelected($event)">

    <div class="border-2 border-dashed rounded-2xl p-5 text-center transition-all cursor-pointer"
         :class="dropHover ? 'border-vendor-400 bg-vendor-50/50 dark:bg-vendor-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-vendor-400'"
         @click="$refs.fileInput.click()"
         @dragover.prevent="dropHover=true" @dragleave.prevent="dropHover=false"
         @drop.prevent="dropHover=false; onDropFiles($event)">
        <p class="text-sm"><span class="font-bold text-vendor-600 dark:text-vendor-400">Click to browse</span> <span class="text-slate-400">or drag &amp; drop</span></p>
        <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP &bull; Max 4 MB each</p>
    </div>

    <template x-if="primaryId">
        <input type="hidden" name="set_primary" :value="primaryId">
    </template>
    @error('images')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
    @error('images.*')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
</div>
@else
<div class="md:col-span-2 space-y-2" x-data="createImageManager()">
    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Product images</label>
    <div class="flex items-center justify-between mb-2">
        <p class="text-xs text-slate-400">First selected becomes main unless you pick another. Drag to reorder before saving.</p>
        <span class="text-xs font-bold px-3 py-1 rounded-full"
              :class="files.length ? 'bg-vendor-100 text-vendor-700 dark:bg-vendor-900/40 dark:text-vendor-300' : 'bg-slate-100 text-slate-500'"
              x-text="files.length + ' image(s)'"></span>
    </div>

    <template x-if="files.length > 0">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
            <template x-for="(f, idx) in files" :key="f.name + '-' + idx">
                <div class="relative rounded-xl border-2 overflow-hidden transition-all cursor-grab active:cursor-grabbing"
                     :class="idx === primaryIdx ? 'border-vendor-500 ring-2 ring-vendor-500/30' : 'border-slate-200 dark:border-slate-700'"
                     draggable="true"
                     @dragstart="dragIdx=idx; $event.dataTransfer.effectAllowed='move'"
                     @dragend="dragIdx=null"
                     @dragover.prevent @drop.prevent="onDrop(idx)">
                    <img :src="f.preview || 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'" alt="" class="w-full h-24 object-cover pointer-events-none bg-slate-100">
                    <template x-if="idx === primaryIdx">
                        <span class="absolute top-1 left-1 text-[10px] font-bold bg-vendor-600 text-white px-2 py-0.5 rounded shadow">&#9733; Main</span>
                    </template>
                    <div class="flex items-center gap-1 p-1.5 bg-slate-50 dark:bg-slate-800">
                        <button type="button" @click="primaryIdx = idx"
                                class="flex-1 text-[10px] font-bold py-1.5 rounded-lg transition"
                                :class="idx === primaryIdx ? 'bg-vendor-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'">&#9733; Main</button>
                        <button type="button" @click="removeFile(idx)"
                                class="text-[10px] font-bold py-1.5 px-2.5 rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">&times;</button>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <input type="file" x-ref="fileInput" name="images[]" class="hidden" multiple accept="image/*" @change="onFilesSelected($event)">
    <input type="hidden" name="primary_image_index" :value="primaryIdx">

    <div class="border-2 border-dashed rounded-2xl p-5 text-center transition-all"
         :class="dropHover ? 'border-vendor-400 bg-vendor-50/50 dark:bg-vendor-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-vendor-400'"
         @dragover.prevent="dropHover=true" @dragleave.prevent="dropHover=false"
         @drop.prevent="dropHover=false; onDropFiles($event)">
        <button type="button" @click="$refs.fileInput.click()" class="text-sm font-bold text-vendor-600 dark:text-vendor-400 hover:underline">Choose files</button>
        <span class="text-sm text-slate-400 ml-1">or drag & drop here</span>
        <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP &bull; Max 4 MB each</p>
    </div>
    @error('images')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
    @error('images.*')<p class="text-xs text-rose-500 font-bold ml-1 mt-1">{{ $message }}</p>@enderror
</div>
@endif

@include('partials.product-gallery-alpine')
