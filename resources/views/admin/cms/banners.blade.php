@extends('layouts.admin')

@section('title', 'Manage Banners')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Promotional Banners</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Control slider artwork and hero graphics across storefront areas.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-banner-modal')" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Deploy New Banner
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-64">Preview</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Metadata</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Sort Pos</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="h-20 w-40 bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                @if($banner->image)
                                    <img src="{{ Storage::url($banner->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900 dark:text-white block">{{ $banner->title }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $banner->position }}</span>
                        </td>
                        
                        <td class="px-6 py-4 text-sm font-extrabold text-slate-900 dark:text-white">
                            #{{ $banner->sort_order }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border 
                                {{ $banner->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400' }}
                            ">
                                {{ $banner->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="#" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">Edit</a>
                            <form action="{{ route('admin.cms.banners.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this banner image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-rose-50 text-xs font-bold text-rose-600 dark:bg-rose-900/20 dark:text-rose-400 transition-colors">Del</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            No marketing banners deployed into slider rotation.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($banners) && $banners->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            {{ $banners->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

    <!-- Banner Deployment Modal -->
    <div x-data="{ open: false, banner: null }" 
         x-show="open" 
         @open-banner-modal.window="open = true" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-hidden"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md" @click="open = false"></div>
        
        <div class="bg-white dark:bg-darkpanel w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all duration-300 scale-100"
             x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="px-10 py-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/10">
                <h3 class="text-xl font-black font-heading text-slate-900 dark:text-white uppercase tracking-tight italic underlined decoration-brand-500 decoration-8 underline-offset-4">Banner Orchestration</h3>
                <button @click="open = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.cms.banners.store') }}" method="POST" class="p-10 space-y-6 overflow-y-auto max-h-[70vh] scrollbar-hide">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Title Designation</label>
                        <input type="text" name="title" required class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-5 py-3.5 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-brand-500 shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Storefront Position</label>
                        <select name="position" class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-5 py-3.5 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-brand-500 shadow-inner appearance-none">
                            <option value="home_slider">Main Carousel</option>
                            <option value="sidebar">Sidebar Widget</option>
                            <option value="promo_strip">Strip Header</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Asset (Image URL)</label>
                    <input type="text" name="image" required placeholder="URL to banner asset..." class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-5 py-3.5 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-brand-500 shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Navigation Link Target</label>
                    <input type="text" name="link" placeholder="https://..." class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-5 py-3.5 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-brand-500 shadow-inner">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sequence Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl px-5 py-3.5 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-brand-500 shadow-inner">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 dark:peer-focus:ring-brand-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-brand-600"></div>
                            <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Live Status</span>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-4">
                    <button type="button" @click="open = false" class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition">Standby</button>
                    <button type="submit" class="px-10 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl transition hover:scale-[1.03] active:scale-95">Engage Protocol</button>
                </div>
            </form>
        </div>
    </div>

@endsection
