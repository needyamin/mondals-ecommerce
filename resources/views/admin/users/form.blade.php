@extends('layouts.admin')

@section('title', $user ? 'Edit user' : 'Add user')

@section('content')

    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $user ? 'Edit user' : 'Add user' }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">{{ $user ? 'Update profile, role, and status.' : 'Create an account and assign a role.' }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-brand-600 font-bold text-sm bg-white dark:bg-darkpanel px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
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

    <form action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10 pb-20">
        @csrf
        @if($user) @method('PUT') @endif
        
        <!-- Left: Core Dossier -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white dark:bg-darkpanel p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-500/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-brand-500 mr-3 animate-pulse"></span>
                    Identity Parameters
                </h3>
                
                <div class="space-y-8 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Legal Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter full name..." class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold" required>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Official Email Access</label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="user@domain.com" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner transition-all text-slate-900 dark:text-white font-bold" required>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">System Security Policy ({{ $user ? 'Reset' : 'Initial' }} Password)</label>
                        <div class="relative">
                            <input type="text" name="password" placeholder="{{ $user ? 'Leave blank to retain current key' : 'MondalsSecure99!' }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-brand-500 shadow-inner font-mono tracking-tighter text-slate-900 dark:text-white" {{ $user ? '' : 'required' }}>
                        </div>
                        <p class="text-[10px] text-slate-400 italic font-medium">{{ $user ? 'Security keys are encrypted and cannot be viewed.' : 'User will be prompted to reset upon first authentication cycle.' }}</p>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <label class="relative flex items-center p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-900 transition-all group border-2 border-transparent has-[:checked]:border-brand-500">
                            <input type="checkbox" name="marketing_opt_in" value="1" {{ old('marketing_opt_in', $user->marketing_opt_in ?? false) ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-none bg-slate-200 dark:bg-slate-800 text-brand-500 focus:ring-brand-500 shadow-inner">
                            <span class="ml-4 flex flex-col">
                                <span class="text-[11px] font-black uppercase tracking-[0.1em] text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">Marketing Communication Opt-In</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Enroll subject in promotional network protocols.</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Authorization Control -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-slate-900/30 border border-white/5">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-6.831 2.158 11.952 11.952 0 00-3.111 2.484 11.951 11.951 0 00-2.059 10.95c.513 2.01 1.512 3.811 2.768 5.4 1.256 1.589 2.809 3.125 4.631 4.632A11.95 11.95 0 0012 21.056a11.95 11.95 0 006.591-2.432 11.95 11.95 0 004.631-4.632 11.951 11.951 0 002.768-5.4 11.951 11.951 0 00-2.059-10.95 11.952 11.952 0 00-3.111-2.484z"></path></svg>
                    Role Clearance
                </h3>
                
                <div class="space-y-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Clearance Level</label>
                        <div class="grid grid-cols-1 gap-3">
                            @php 
                                $currentRole = $user ? ($user->roles->first()->name ?? 'customer') : 'customer';
                            @endphp
                            <label class="relative flex items-center p-4 bg-slate-800 rounded-2xl cursor-pointer hover:bg-slate-700 transition group border-2 border-transparent has-[:checked]:border-brand-500">
                                <input type="radio" name="role" value="customer" {{ $currentRole === 'customer' ? 'checked' : '' }} class="hidden">
                                <span class="w-4 h-4 rounded-full border-2 border-slate-600 flex items-center justify-center p-1 group-has-[:checked]:bg-brand-500 group-has-[:checked]:border-brand-500"></span>
                                <span class="ml-3 text-sm font-black uppercase tracking-widest">Customer</span>
                            </label>
                            <label class="relative flex items-center p-4 bg-slate-800 rounded-2xl cursor-pointer hover:bg-slate-700 transition group border-2 border-transparent has-[:checked]:border-brand-500">
                                <input type="radio" name="role" value="vendor" {{ $currentRole === 'vendor' ? 'checked' : '' }} class="hidden">
                                <span class="w-4 h-4 rounded-full border-2 border-slate-600 flex items-center justify-center p-1 group-has-[:checked]:bg-brand-500 group-has-[:checked]:border-brand-500"></span>
                                <span class="ml-3 text-sm font-black uppercase tracking-widest">Merchant/Vendor</span>
                            </label>
                            <label class="relative flex items-center p-4 bg-slate-800 rounded-2xl cursor-pointer hover:bg-slate-700 transition group border-2 border-transparent has-[:checked]:border-brand-500">
                                <input type="radio" name="role" value="admin" {{ $currentRole === 'admin' ? 'checked' : '' }} class="hidden">
                                <span class="w-4 h-4 rounded-full border-2 border-slate-600 flex items-center justify-center p-1 group-has-[:checked]:bg-brand-500 group-has-[:checked]:border-brand-500"></span>
                                <span class="ml-3 text-sm font-black uppercase tracking-widest text-indigo-400">System Admin</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4 pt-8 border-t border-white/5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Account State</label>
                        <select name="status" class="w-full bg-slate-800 border-none rounded-2xl px-5 py-4 text-white font-bold text-sm focus:ring-2 focus:ring-brand-500 shadow-inner">
                            <option value="active" {{ ($user->status ?? '') === 'active' ? 'selected' : '' }}>Operational (Active)</option>
                            <option value="inactive" {{ ($user->status ?? '') === 'inactive' ? 'selected' : '' }}>Dormant (Inactive)</option>
                            <option value="banned" {{ ($user->status ?? '') === 'banned' ? 'selected' : '' }}>Restricted (Banned)</option>
                        </select>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl font-black shadow-2xl shadow-brand-500/30 transition-all hover:scale-[1.03] active:scale-95 text-xs uppercase tracking-[0.2em]">
                            {{ $user ? 'Commit Modifications' : 'Initialize Dossier' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
