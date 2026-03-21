@extends('layouts.admin')

@section('title', isset($page) ? 'Modify Content Node' : 'Initialize Page Registry')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- CMS Header -->
    <div class="mb-12 flex items-center justify-between group">
        <div>
            <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight underline decoration-purple-500 decoration-4 underline-offset-8 italic">
                Content {{ isset($page) ? 'Modification' : 'Registration' }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-6 font-medium uppercase tracking-[0.2em] text-[10px] group-hover:text-purple-500 transition-colors">
                {{ isset($page) ? 'Overriding established informational metadata.' : 'Initialize new informational node in infrastructure.' }}
            </p>
        </div>
        <a href="{{ route('admin.cms.pages') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-purple-600 shadow-xl transition-all hovrer:rotate-90">
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

    <form action="{{ isset($page) ? route('admin.cms.pages.update', $page->id) : route('admin.cms.pages.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10 pb-24">
        @csrf
        @if(isset($page)) @method('PUT') @endif

        <!-- Main Payload: Informational Content -->
        <div class="lg:col-span-8 space-y-10">
            <div class="bg-white dark:bg-darkpanel p-10 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 relative overflow-hidden group/card shadow-slate-200/50">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl group-hover/card:scale-110 transition-transform duration-700"></div>
                
                <div class="space-y-10 relative z-10">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 font-mono">Informational Subject</label>
                        <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" placeholder="Page Deployment Title..." class="w-full px-8 py-5 bg-slate-50 dark:bg-slate-900/50 border-none rounded-3xl text-xl focus:ring-2 focus:ring-purple-500 shadow-inner transition-all text-slate-900 dark:text-white font-black italic tracking-tight" required>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 font-mono">Payload Data (Content HTML)</label>
                        <textarea name="content" rows="18" placeholder="Enter HTML content registry..." class="w-full px-8 py-8 bg-slate-50 dark:bg-slate-900/50 border-none rounded-[2rem] text-sm focus:ring-2 focus:ring-purple-500 shadow-inner transition-all text-slate-900 dark:text-white font-mono leading-relaxed" required>{{ old('content', $page->content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: SEO & Ecosystem -->
        <div class="lg:col-span-4 space-y-10">
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl border border-white/5 relative overflow-hidden">
                <div class="space-y-10">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono">Ecosystem Protocol</label>
                        <label class="relative flex items-center p-6 bg-slate-800 rounded-3xl cursor-pointer hover:bg-slate-700 transition group border-2 border-transparent has-[:checked]:border-purple-500 shadow-sm shadow-black/20">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-slate-200 dark:bg-slate-800 text-purple-500 focus:ring-purple-500 shadow-inner">
                            <span class="ml-4 flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.1em] text-white">Active Registry</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1">Accept public traffic.</span>
                            </span>
                        </label>
                    </div>

                    <div class="space-y-4 pt-8 border-t border-white/5">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest block font-mono mb-6">Discovery Metrics (SEO)</h4>
                        
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Global Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-white font-bold text-xs focus:ring-1 focus:ring-purple-500 shadow-inner">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Global Meta Description</label>
                                <textarea name="meta_description" rows="3" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-3.5 text-white font-bold text-xs focus:ring-1 focus:ring-purple-500 shadow-inner">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10">
                        <button type="submit" class="w-full py-5 bg-purple-600 hover:bg-purple-700 text-white rounded-[1.5rem] font-black shadow-2xl shadow-purple-500/30 transition-all hover:scale-[1.03] active:scale-95 text-xs uppercase tracking-[0.3em] font-heading">
                            {{ isset($page) ? 'Commit Registry' : 'Initialize Node' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
