@extends('layouts.admin')

@section('title', 'Platform Settings')

@section('content')

    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">System Configuration</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Configure global variables based on the active setting group.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Settings Nav Sidebar -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-28 bg-white dark:bg-darkpanel p-3 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <nav class="space-y-1">
                    @forelse($groups as $grp)
                        <a href="{{ route('admin.settings', ['group' => $grp]) }}" class="w-full flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-200
                            {{ $group === $grp ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/20 dark:text-brand-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                            
                            @if($grp === 'general')
                                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            @elseif($grp === 'email')
                                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @else
                                <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            @endif
                            {{ ucfirst($grp) }} Settings
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 p-4">Select or seed a settings group format first.</p>
                    @endforelse
                </nav>
            </div>
        </div>

        <!-- Forms Container -->
        <div class="flex-grow space-y-8 pb-10">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
                @csrf
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-brand-500/10 rounded-full blur-[40px] pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="border-b border-slate-100 dark:border-slate-800 pb-5 mb-8 relative z-10">
                    <h3 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ ucfirst($group) }} Configuration</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Adjust the exact parameter values loaded into your platform cache layer actively.</p>
                </div>

                <div class="space-y-8 relative z-10">
                    @forelse($settings as $setting)
                        <div class="bg-slate-50/50 dark:bg-slate-800/20 p-5 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                            <label class="block text-sm font-bold text-slate-900 dark:text-white mb-2 font-heading tracking-wide">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            
                            @if($setting->type === 'boolean')
                                <select name="settings[{{ $setting->key }}]" class="w-full lg:w-1/2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors shadow-sm">
                                    <option value="1" {{ $setting->value == '1' || $setting->value == 'true' ? 'selected' : '' }}>Enabled / Yes</option>
                                    <option value="0" {{ $setting->value == '0' || $setting->value == 'false' ? 'selected' : '' }}>Disabled / No</option>
                                </select>
                            @elseif($setting->type === 'text')
                                <textarea name="settings[{{ $setting->key }}]" rows="3" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors shadow-sm">{{ $setting->value }}</textarea>
                            @else
                                <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors shadow-sm">
                            @endif
                            
                            @if($setting->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $setting->description }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">No system settings mapped to this category yet.</p>
                        </div>
                    @endforelse

                    @if($settings->count() > 0)
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-1 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Record Configuration
                        </button>
                    </div>
                    @endif
                </div>
            </form>

        </div>
    </div>
@endsection
