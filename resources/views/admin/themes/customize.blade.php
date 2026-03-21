@extends('layouts.admin')

@section('title', 'Customize Theme: ' . ucfirst($theme))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Customize: <span class="text-indigo-600 dark:text-indigo-400">{{ ucfirst($theme) }}</span></h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Fine-tune your storefront aesthetics and behavior</p>
        </div>
        <a href="{{ route('admin.themes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-widest shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Themes
        </a>
    </div>

    @if(empty($fields))
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-3xl p-8 text-center">
            <div class="w-16 h-16 bg-amber-100 dark:bg-amber-800/40 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-amber-900 dark:text-amber-100 mb-2">No Customizable Fields</h3>
            <p class="text-amber-700 dark:text-amber-300 max-w-md mx-auto">This theme doesn't expose any customizable parameters in its <code>theme.json</code> file.</p>
        </div>
    @else
        <form action="{{ route('admin.themes.customize.save') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="p-8 space-y-8">
                    @foreach($fields as $name => $config)
                        <div class="group relative">
                            <label for="field_{{ $name }}" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">
                                {{ $config['label'] ?? ucfirst($name) }}
                            </label>

                            @if(($config['type'] ?? 'text') === 'color')
                                <div class="flex items-center gap-4">
                                    <input type="color" 
                                           id="field_{{ $name }}" 
                                           name="custom[{{ $name }}]" 
                                           value="{{ $customization[$name] ?? ($config['default'] ?? '#000000') }}"
                                           class="h-12 w-24 rounded-xl border border-slate-200 dark:border-slate-700 p-1 bg-white dark:bg-slate-800 cursor-pointer">
                                    <input type="text" 
                                           value="{{ $customization[$name] ?? ($config['default'] ?? '#000000') }}"
                                           class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 uppercase"
                                           oninput="document.getElementById('field_{{ $name }}').value = this.value">
                                </div>
                            @elseif(($config['type'] ?? 'text') === 'boolean')
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="custom[{{ $name }}]" value="0">
                                    <input type="checkbox" 
                                           name="custom[{{ $name }}]" 
                                           value="1" 
                                           class="sr-only peer" 
                                           {{ ($customization[$name] ?? ($config['default'] ?? false)) ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                    <span class="ml-3 text-sm font-medium text-slate-600 dark:text-slate-400">Enabled</span>
                                </label>
                            @elseif(($config['type'] ?? 'text') === 'textarea')
                                <textarea name="custom[{{ $name }}]" 
                                          id="field_{{ $name }}" 
                                          rows="4"
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                >{{ $customization[$name] ?? ($config['default'] ?? '') }}</textarea>
                            @else
                                <input type="text" 
                                       name="custom[{{ $name }}]" 
                                       id="field_{{ $name }}" 
                                       value="{{ $customization[$name] ?? ($config['default'] ?? '') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            @endif

                            @if(isset($config['help']))
                                <p class="mt-2 text-xs text-slate-500 italic">{{ $config['help'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Footer Actions -->
                <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-200 transform hover:-translate-y-0.5">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
