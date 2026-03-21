@extends('layouts.admin')

@section('title', 'Manage Plugins')

@section('content')

    <!-- Ecosystem Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-darkpanel rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Packages</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white font-heading">{{ $plugins->count() }}</h3>
        </div>
        <div class="bg-white dark:bg-darkpanel rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Integrations</p>
            <h3 class="text-3xl font-black text-emerald-500 font-heading">{{ $plugins->where('status', 'active')->count() }}</h3>
        </div>
        <div class="bg-white dark:bg-darkpanel rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Discovery Status</p>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-2 flex items-center">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                Ecosystem Synchronized
            </p>
        </div>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">System Extensions</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Govern 3rd-party modular extensions and internal integration protocols.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.plugins.index') }}" class="btn btn-outline bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 p-2.5 rounded-xl transition shadow-sm hover:bg-slate-50" title="Refresh Ecosystem">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </a>
            <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="plugin_zip" accept=".zip" required class="hidden" id="plugin_zip_input" onchange="this.form.submit()">
                <label for="plugin_zip_input" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center cursor-pointer transform hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Upload Module
                </label>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-8 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-1/3">Plugin Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Version</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">State</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($plugins as $plugin)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors {{ !$plugin->is_active ? 'opacity-80' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center flex-shrink-0 mt-1 mr-4">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white font-heading">{{ $plugin->name }}</h3>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-500 px-2 py-0.5 rounded uppercase font-bold tracking-tighter">{{ $plugin->slug }}</span>
                                        @if($plugin->author)
                                            <span class="text-[10px] text-slate-400">by 
                                                @if($plugin->author_url)
                                                    <a href="{{ $plugin->author_url }}" target="_blank" class="text-brand-500 hover:underline inline-flex items-center">
                                                        {{ $plugin->author }}
                                                        <svg class="w-2 h-2 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                @else
                                                    {{ $plugin->author }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 whitespace-normal line-clamp-2 max-w-sm leading-relaxed">
                                        {{ $plugin->description ?? 'No metadata description provided by the module vendor.' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-sm font-mono text-slate-500 dark:text-slate-400">
                            v{{ $plugin->version ?? '1.0.0' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($plugin->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400">
                                    Deactivated
                                </span>
                            @endif
                            
                            @if($plugin->dependencies)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($plugin->dependencies as $dep => $ver)
                                        <span class="text-[10px] text-slate-400 border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded" title="Requires {{ $dep }} {{ $ver }}">{{ $dep }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right space-x-1">
                            @if(!$plugin->installed_at)
                                <form action="{{ route('admin.plugins.install', $plugin->slug) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold bg-brand-50 text-brand-600 hover:bg-brand-100 px-4 py-2 rounded-xl transition-all shadow-sm">Initialize Data</button>
                                </form>
                            @else
                                <a href="{{ route('admin.plugins.settings', $plugin->slug) }}" class="inline-block text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-brand-500/50 transition-all">Configure</a>
                                
                                @if($plugin->is_active)
                                    <form action="{{ route('admin.plugins.disable', $plugin->slug) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-amber-600 hover:text-amber-700 px-3 py-2 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">Disable</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.plugins.enable', $plugin->slug) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 px-3 py-2 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">Enable</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.plugins.uninstall', $plugin->slug) }}" method="POST" class="inline-block" onsubmit="return confirm('Wipe database tables for this module?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-slate-400 hover:text-amber-600 px-3 py-2 transition-colors">Uninstall</button>
                                </form>

                                <form action="{{ route('admin.plugins.destroy', $plugin->slug) }}" method="POST" class="inline-block" onsubmit="return confirm('CRITICAL: This will PERMANENTLY DELETE ALL FILES for this module from the server disk. Continue?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-slate-300 hover:text-rose-600 px-3 py-2 transition-colors" title="Delete from Disk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Ecosystem Empty</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">No 3rd-party modules or integration packages discovered in `app/Plugins/`.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
