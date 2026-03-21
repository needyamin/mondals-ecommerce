@extends('layouts.admin')

@section('title', isset($item) ? 'Modify Brand Identity' : 'Brand Registration')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight underline decoration-brand-500 decoration-4 underline-offset-8">
                Brand {{ isset($item) ? 'Modification' : 'Registration' }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-6 font-medium uppercase tracking-[0.2em] text-[10px]">
                {{ isset($item) ? 'Overriding established manufacturer metadata.' : 'Initialize new manufacturer node in ecosystem.' }}
            </p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 shadow-xl transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-10 p-6 rounded-3xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/30">
            <ul class="list-disc list-inside space-y-2 text-sm font-black uppercase tracking-tight">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('admin.brands.update', $item->id) : route('admin.brands.store') }}" method="POST" class="space-y-8 pb-20">
        @csrf
        @if(isset($item)) @method('PUT') @endif

        <div class="bg-white dark:bg-darkpanel p-10 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-500/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 flex items-center">
                <span class="w-2 h-2 rounded-full bg-brand-500 mr-3 animate-pulse"></span>
                Manufacturing Parameters
            </h3>

            <div class="space-y-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Designation</label>
                        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" placeholder="e.g. Mondal Textiles" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold" required>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate URL</label>
                        <input type="url" name="website" value="{{ old('website', $item->website ?? '') }}" placeholder="https://mondal.com" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identity Vector (Logo URL)</label>
                    <input type="text" name="logo" value="{{ old('logo', $item->logo ?? '') }}" placeholder="URL to SVG or PNG logo" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold">
                    @if(isset($item) && $item->logo)
                        <div class="mt-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl inline-block shadow-lg">
                            <img src="{{ $item->logo }}" alt="Preview" class="h-12 object-contain">
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate Abstract (Description)</label>
                    <textarea name="description" rows="4" placeholder="Brief manufacturer historical summary..." class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold">{{ old('description', $item->description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <label class="relative flex items-center p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-900 transition-all group border-2 border-transparent has-[:checked]:border-brand-500 shadow-sm">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-slate-200 dark:bg-slate-800 text-brand-500 focus:ring-brand-500 shadow-inner">
                        <span class="ml-4 flex flex-col">
                            <span class="text-[11px] font-black uppercase tracking-[0.1em] text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">Operational Status</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Enable manufacturer in public catalog.</span>
                        </span>
                    </label>

                    <label class="relative flex items-center p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-900 transition-all group border-2 border-transparent has-[:checked]:border-brand-500 shadow-sm">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured ?? false) ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-slate-200 dark:bg-slate-800 text-emerald-500 focus:ring-brand-500 shadow-inner">
                        <span class="ml-4 flex flex-col">
                            <span class="text-[11px] font-black uppercase tracking-[0.1em] text-slate-900 dark:text-white group-hover:text-emerald-600 transition-colors">Premium Promotion</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Surface manufacturer in featured zones.</span>
                        </span>
                    </label>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white rounded-[1.5rem] font-black shadow-2xl shadow-brand-500/30 transition-all hover:scale-[1.03] active:scale-95 text-xs uppercase tracking-[0.3em]">
                        {{ isset($item) ? 'Commit Modifications' : 'Initialize Protocol' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
