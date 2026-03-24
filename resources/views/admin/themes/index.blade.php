@extends('layouts.admin')

@section('title', 'Manage Themes')

@section('content')

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Storefront Themes</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage the visual appearance of your public-facing marketplace.</p>
        </div>
        <a href="{{ route('admin.themes.customize') }}" class="inline-flex items-center bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 text-brand-700 dark:text-brand-400 font-medium px-5 py-2.5 rounded-xl hover:bg-brand-50 dark:hover:bg-brand-900/20 transition shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            Customize active theme
        </a>
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

    <div class="mb-8 bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/40">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Upload theme</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">ZIP must contain one folder (theme slug) with <code class="text-xs bg-slate-200/80 dark:bg-slate-700 px-1.5 py-0.5 rounded">theme.json</code> and a <code class="text-xs bg-slate-200/80 dark:bg-slate-700 px-1.5 py-0.5 rounded">views</code> directory. Max 20&nbsp;MB.</p>
        </div>
        <form id="theme-upload-form" action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div id="theme-dropzone" class="relative rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-600 bg-slate-50/50 dark:bg-slate-800/30 px-6 py-10 text-center transition-colors hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50/30 dark:hover:bg-brand-900/10">
                <input type="file" name="theme_zip" id="theme_zip" accept=".zip,application/zip" class="sr-only" required>
                <label for="theme_zip" class="cursor-pointer block">
                    <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-400 mb-4 mx-auto">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 9L19 13m0 0l-4-4m4 4l-4 4"/></svg>
                    </span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Drop a theme .zip here or <span class="text-brand-600 dark:text-brand-400 underline decoration-2 underline-offset-2">browse</span></span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-2">Only <strong>.zip</strong> — extracted into <code class="text-[11px] bg-slate-200/80 dark:bg-slate-700 px-1 rounded">resources/themes/</code></span>
                </label>
                <p id="theme-zip-name" class="mt-4 text-sm font-medium text-brand-600 dark:text-brand-400 min-h-[1.25rem]"></p>
            </div>
            @error('theme_zip')
                <p class="mt-3 text-sm font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <button type="submit" class="inline-flex items-center bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-brand-500/25 transition">
                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Install theme
                </button>
                <button type="button" id="theme-zip-clear" class="text-sm font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 px-3 py-2 hidden">Clear file</button>
            </div>
        </form>
    </div>

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

    <script>
    (function () {
        var input = document.getElementById('theme_zip');
        var drop = document.getElementById('theme-dropzone');
        var nameEl = document.getElementById('theme-zip-name');
        var clearBtn = document.getElementById('theme-zip-clear');
        if (!input || !drop) return;

        function setFile(file) {
            if (!file || !file.name.toLowerCase().endsWith('.zip')) return;
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            nameEl.textContent = file.name;
            clearBtn.classList.remove('hidden');
        }

        input.addEventListener('change', function () {
            var f = this.files && this.files[0];
            if (f) { nameEl.textContent = f.name; clearBtn.classList.remove('hidden'); }
            else { nameEl.textContent = ''; clearBtn.classList.add('hidden'); }
        });

        clearBtn.addEventListener('click', function () {
            input.value = '';
            nameEl.textContent = '';
            clearBtn.classList.add('hidden');
        });

        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                drop.classList.add('border-brand-500', 'bg-brand-50/50', 'dark:bg-brand-900/20');
            });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                drop.classList.remove('border-brand-500', 'bg-brand-50/50', 'dark:bg-brand-900/20');
            });
        });
        drop.addEventListener('drop', function (e) {
            var f = e.dataTransfer.files && e.dataTransfer.files[0];
            if (f) setFile(f);
        });
    })();
    </script>

@endsection
