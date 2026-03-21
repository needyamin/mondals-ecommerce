@extends('layouts.admin')

@section('title', 'Manage Themes')

@section('content')

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Storefront Themes</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage the visual appearance of your public-facing marketplace.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.themes.customize') }}" class="btn btn-outline bg-white dark:bg-darkpanel border border-brand-200 dark:border-brand-800 text-brand-700 dark:text-brand-400 font-medium px-5 py-2.5 rounded-xl hover:bg-brand-50 dark:hover:bg-brand-900/20 transition flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Customize Active Theme
            </a>
            <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="theme_zip" accept=".zip" required class="text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700 cursor-pointer">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Upload Theme
                </button>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($themes as $index => $themeData)
            @php $slug = $themeData['slug']; $isActive = ($activeTheme === $slug); @endphp
            <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border {{ $isActive ? 'border-brand-500 ring-2 ring-brand-500/20' : 'border-slate-100 dark:border-slate-800' }} overflow-hidden group flex flex-col relative transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                
                @if($isActive)
                    <div class="absolute top-4 right-4 z-10 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>
                        Active Theme
                    </div>
                @endif
                
                <!-- Theme Preview (Skeleton or Thumbnail) -->
                <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden border-b border-slate-100 dark:border-slate-800/80">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 dark:from-indigo-900/20 dark:to-purple-900/20 flex flex-col justify-between p-4 mix-blend-multiply dark:mix-blend-overlay">
                        <!-- Mockup Navbar -->
                        <div class="w-full h-8 bg-white/60 dark:bg-slate-900/40 rounded-lg flex items-center px-3 space-x-2 backdrop-blur-sm shadow-sm">
                            <div class="w-4 h-4 rounded-full bg-slate-300/80 dark:bg-slate-600/50"></div>
                            <div class="flex-grow"></div>
                            <div class="w-10 h-3 rounded bg-slate-200/80 dark:bg-slate-700/50"></div>
                            <div class="w-10 h-3 rounded bg-slate-200/80 dark:bg-slate-700/50"></div>
                        </div>
                        <!-- Mockup Hero -->
                        <div class="w-full h-24 bg-brand-600/10 dark:bg-brand-500/20 rounded-xl backdrop-blur-sm flex flex-col justify-center items-center px-4 space-y-2 border border-white/20 dark:border-white/5">
                            <div class="w-1/2 h-4 rounded bg-brand-500/30 dark:bg-brand-400/40"></div>
                            <div class="w-3/4 h-2 rounded bg-slate-300/50 dark:bg-slate-600/30"></div>
                            <div class="w-16 h-5 rounded-lg bg-brand-500 dark:bg-brand-600 shadow-sm mt-1"></div>
                        </div>
                    </div>
                </div>

                <!-- Theme Metadata -->
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                {{ $themeData['name'] ?? Str::headline($slug) }}
                            </h3>
                            <span class="text-xs font-mono text-slate-400 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-100 dark:border-slate-700">
                                v{{ $themeData['version'] ?? '1.0.0' }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 leading-relaxed">
                            {{ $themeData['description'] ?? 'A highly optimized, fully responsive theme mapped natively for Mondals Ecommerce architecture.' }}
                        </p>
                        
                        <div class="flex items-center text-xs text-slate-400 mb-6">
                            <span class="font-medium mr-2 text-slate-600 dark:text-slate-300">Author:</span>
                            {{ $themeData['author'] ?? 'System Core' }}
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-100 dark:border-slate-800/80 flex justify-between items-center mt-auto">
                        @if(!$isActive)
                            <form action="{{ route('admin.themes.activate', $slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300 font-bold text-sm flex items-center px-3 py-1.5 rounded-lg hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Activate Theme
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.themes.customize') }}" class="text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300 font-bold text-sm flex items-center px-3 py-1.5 rounded-lg hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                Customize
                            </a>
                        @endif
                        
                        <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Theme Options">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 border-dashed">
                <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Themes Found</h4>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Upload a new theme module to alter the storefront visualization.</p>
            </div>
        @endforelse
    </div>

@endsection
