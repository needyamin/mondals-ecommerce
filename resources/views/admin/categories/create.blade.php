@extends('layouts.admin')

@section('title', 'Add New Category')

@section('content')

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-brand-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Create Category</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Add a new taxonomic grouping for the catalog.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-8 p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-900/30 dark:border-rose-800 text-rose-600 dark:text-rose-400">
            <ul class="list-disc list-inside text-sm font-medium space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-10">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Taxonomy Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Category Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-8">
                
                <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6">Hierarchy & Meta</h3>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Parent Category</label>
                            <select name="parent_id" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors">
                                <option value="">None (Top Level)</option>
                                @foreach($parents as $id => $pName)
                                    <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>{{ $pName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div class="block w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded-full transition-colors target-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"></div>
                                </div>
                                <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">Active Visibility</span>
                            </label>
                            
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_featured" value="1" class="sr-only" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div class="block w-10 h-6 bg-slate-200 dark:bg-slate-700 rounded-full transition-colors target-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"></div>
                                </div>
                                <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">Feature on Homepage</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex justify-center sticky top-24">
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-1">
                        Save Category
                    </button>
                </div>

            </div>
        </div>
    </form>

    <style>
        input:checked ~ .dot { transform: translateX(100%); }
        input:checked ~ .target-bg { background-color: #0d9488; }
    </style>

@endsection
