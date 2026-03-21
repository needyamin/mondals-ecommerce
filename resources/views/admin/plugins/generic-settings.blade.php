@extends('layouts.admin')

@section('title', 'Plugin Settings: ' . $plugin->name)

@section('content')

    <div class="mb-8">
        <a href="{{ route('admin.plugins.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-brand-600 transition-colors mb-4 group">
            <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Ecosystem
        </a>
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $plugin->name }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Configure module-specific environmental parameters and credentials.</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-8">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 font-heading flex items-center">
                <svg class="w-5 h-5 mr-3 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Dynamic Settings Configuration
            </h3>

            @if(empty($plugin->settings))
                <div class="px-6 py-10 text-center bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                    <p class="text-slate-500 dark:text-slate-400 italic">This plugin exposes no configurable parameters to the ecosystem.</p>
                </div>
            @else
                <form action="{{ route('admin.plugins.settings.save', $plugin->slug) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($plugin->settings as $key => $value)
                            <div class="space-y-2">
                                <label for="setting_{{ $key }}" class="block text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ str_replace('_', ' ', $key) }}</label>
                                
                                @if(is_array($value) || is_object($value))
                                    <textarea name="settings[{{ $key }}]" id="setting_{{ $key }}" rows="4" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-brand-500 focus:border-brand-500 py-3 px-4 text-xs font-mono">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
                                @elseif(is_bool($value))
                                    <select name="settings[{{ $key }}]" id="setting_{{ $key }}" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-brand-500 focus:border-brand-500 py-3 px-4">
                                        <option value="1" {{ $value ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ !$value ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                @elseif(str_contains($key, 'password') || str_contains($key, 'secret') || str_contains($key, 'token'))
                                    <input type="password" name="settings[{{ $key }}]" id="setting_{{ $key }}" value="{{ (string)$value }}" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-brand-500 focus:border-brand-500 py-3 px-4" placeholder="••••••••••••••••">
                                @else
                                    <input type="text" name="settings[{{ $key }}]" id="setting_{{ $key }}" value="{{ (string)$value }}" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-brand-500 focus:border-brand-500 py-3 px-4">
                                @endif
                                
                                @if(isset($plugin->default_settings) && isset($plugin->default_settings[$key]))
                                    @php $def = $plugin->default_settings[$key]; @endphp
                                    <span class="text-[10px] text-slate-400 font-medium block">Default: 
                                        @if(is_bool($def)) {{ $def ? 'On' : 'Off' }} 
                                        @elseif(is_array($def)) (Complex Data)
                                        @else {{ (string)$def }} @endif
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-brand-500/30 transition-all hover:scale-105 active:scale-95">
                            Save Module Parameters
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="mt-8 bg-slate-50 dark:bg-darkpanel/50 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 border-dashed">
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-2 uppercase tracking-tight">Security Notice</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Sensitive fields like API secrets and private keys are encrypted at rest in the database. Ensure your `.env` encryption key is backed up. Modules can only access their own parameters.
            </p>
        </div>
    </div>

@endsection
