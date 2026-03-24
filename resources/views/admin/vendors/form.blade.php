@extends('layouts.admin')

@section('title', $vendor ? 'Edit vendor' : 'Add vendor')

@section('content')

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">{{ $vendor ? 'Edit vendor' : 'Add vendor' }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $vendor ? 'Update store and owner details.' : 'Register a new seller and login account.' }}</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}" class="text-slate-500 hover:text-brand-600 font-bold transition flex items-center bg-white dark:bg-darkpanel px-4 py-2 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Cancel
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
            <ul class="list-disc list-inside space-y-1 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vendors.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10 pb-20">
        @csrf
        
        <!-- Left: Account & Store Details -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- User Account Details -->
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm font-bold">01</span>
                    Partner Credentials
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Account Owner Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name of vendor owner" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Email Address (Login)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@merchant.com" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Temporary Password</label>
                        <input type="text" name="password" value="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white font-mono" required>
                    </div>
                    <div class="space-y-2 text-slate-400 flex flex-col justify-end">
                        <p class="text-[10px] italic leading-tight pb-3">Vendors can reset their passwords upon first login via the portal.</p>
                    </div>
                </div>
            </div>

            <!-- Store Details -->
            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mr-3 text-sm font-bold">02</span>
                    Storefront Presence
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Public Store Name</label>
                        <input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="e.g. Mondal Electronics" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Store Description & Mission</label>
                        <textarea name="description" rows="4" placeholder="Briefly describe what this store specializes in..." class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white resize-none">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Physical/HQ Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Street, Dhaka" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Contact Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+880 1XXX-XXXXXX" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Policy & Performance -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-900/30">
                <h3 class="text-lg font-bold font-heading mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Policy Setup
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Platform Commission (%)</label>
                        <div class="relative">
                            <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', config('shop.default_commission', 10)) }}" class="w-full bg-slate-800 border border-slate-700 rounded-2xl px-5 py-4 text-white font-mono text-2xl font-bold focus:ring-2 focus:ring-brand-500">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold">%</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">This rate applies to every sale made by this vendor.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-800">
                        <button type="submit" class="w-full py-4 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl font-bold shadow-lg shadow-brand-500/30 transition-all hover:scale-[1.02] transform active:scale-95">
                            Complete Registration
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Onboarding Checklist</h4>
                <ul class="space-y-4">
                    <li class="flex items-start text-xs text-slate-500">
                        <svg class="w-4 h-4 mr-2 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Unique email for login
                    </li>
                    <li class="flex items-start text-xs text-slate-500">
                        <svg class="w-4 h-4 mr-2 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Assigned 'Vendor' role automatically
                    </li>
                    <li class="flex items-start text-xs text-slate-500">
                        <svg class="w-4 h-4 mr-2 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Immediate 'Approved' status
                    </li>
                </ul>
            </div>
        </div>
    </form>

@endsection
