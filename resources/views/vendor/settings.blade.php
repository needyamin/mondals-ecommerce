@extends('layouts.vendor')

@section('title', 'System Settings')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group transition duration-300">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-vendor-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Configure Your Storefront</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">Adjust your business profile, logistics and settlement protocols.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
             <div class="px-6 py-2.5 rounded-xl border border-vendor-100 dark:border-vendor-900 bg-vendor-50 dark:bg-vendor-900/40 text-vendor-600 dark:text-vendor-400 font-bold shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Secure Terminal
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 rounded-2xl text-emerald-600 dark:text-emerald-400 font-bold text-sm text-center animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-12 gap-10">
        @csrf
        
        <!-- Profile & Identity -->
        <div class="xl:col-span-8 space-y-10 pb-20">
            
            <div class="bg-white dark:bg-darkpanel rounded-[35px] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-8 border-b border-slate-50 dark:border-slate-800 pb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm font-bold">01</span>
                    Store Identity & Branding
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">Legal Store Name</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $vendor->store_name) }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                    </div>

                    <div class="space-y-2">
                         <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">Contact Email</label>
                         <input type="email" name="email" value="{{ old('email', $vendor->email) }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white" required>
                    </div>

                    <div class="space-y-2">
                         <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">Help-line Phone</label>
                         <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                    </div>

                    <div class="md:col-span-2 space-y-4 pt-4">
                         <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Assets & Visuals</p>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                                 <div class="w-20 h-20 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 mb-4 overflow-hidden flex items-center justify-center shadow-lg">
                                     @if($vendor->logo)
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" class="w-full h-full object-cover">
                                     @else
                                        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                     @endif
                                 </div>
                                 <input type="file" name="logo" class="hidden" id="logo-input" accept="image/*">
                                 <label for="logo-input" class="text-[10px] font-bold uppercase tracking-widest text-vendor-600 dark:text-vendor-400 cursor-pointer hover:underline underline-offset-4">Change Store Logo</label>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                                 <div class="w-full h-20 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 mb-4 overflow-hidden flex items-center justify-center shadow-lg">
                                     @if($vendor->banner)
                                        <img src="{{ asset('storage/' . $vendor->banner) }}" class="w-full h-full object-cover">
                                     @else
                                        <div class="flex items-center space-x-2 text-slate-200">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-bold uppercase tracking-widest">No Banner</span>
                                        </div>
                                     @endif
                                 </div>
                                 <input type="file" name="banner" class="hidden" id="banner-input" accept="image/*">
                                 <label for="banner-input" class="text-[10px] font-bold uppercase tracking-widest text-vendor-600 dark:text-vendor-400 cursor-pointer hover:underline underline-offset-4">Change Banner</label>
                            </div>
                         </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-darkpanel rounded-[35px] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-8 border-b border-slate-50 dark:border-slate-800 pb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 text-sm font-bold">02</span>
                    Logistics & Infrastructure
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                     <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">Full Warehouse Address</label>
                        <textarea name="address" rows="3" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner resize-none text-slate-900 dark:text-white">{{ old('address', $vendor->address) }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">City</label>
                        <input type="text" name="city" value="{{ old('city', $vendor->city) }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block">ZIP / Postal Code</label>
                        <input type="text" name="zip_code" value="{{ old('zip_code', $vendor->zip_code) }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-slate-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[35px] p-8 text-white shadow-2xl shadow-slate-900/30">
                <h3 class="text-xl font-bold font-heading mb-8 border-b border-white/10 pb-4 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-vendor-500 text-white flex items-center justify-center mr-3 text-sm font-bold shadow-lg shadow-vendor-500/30 font-mono">03</span>
                    Financial Settlement Protocols
                </h3>
                
                <p class="text-xs text-slate-500 font-bold uppercase tracking-[0.3em] mb-6">Banking Node Configuration</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 block">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $vendor->settings['banking']['bank_name'] ?? '') }}" placeholder="e.g. City Bank PLC" class="w-full px-6 py-4 bg-slate-800 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-white font-medium">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 block">Account Number</label>
                        <input type="text" name="account_number" value="{{ old('account_number', $vendor->settings['banking']['account_number'] ?? '') }}" placeholder="XXXX-XXXX-XXXX-XXXX" class="w-full px-6 py-4 bg-slate-800 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-white font-mono tracking-widest">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1 block">Account Title / Name</label>
                        <input type="text" name="account_name" value="{{ old('account_name', $vendor->settings['banking']['account_name'] ?? '') }}" placeholder="Enter full legal name of account holder" class="w-full px-6 py-4 bg-slate-800 border-none rounded-2xl text-base focus:ring-2 focus:ring-vendor-500 shadow-inner text-white font-medium">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Summary -->
        <div class="xl:col-span-4 h-sticky top-24">
            <div class="bg-white dark:bg-darkpanel rounded-[35px] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <div class="text-center mb-8">
                     <div class="w-24 h-24 rounded-[35%] bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 flex items-center justify-center mx-auto mb-4 overflow-hidden group shadow-xl transition-all hover:scale-105 duration-500">
                         @if($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" class="w-full h-full object-cover">
                         @else
                            <span class="text-3xl font-black text-slate-200 uppercase">{{ substr($vendor->store_name, 0, 1) }}</span>
                         @endif
                     </div>
                     <h4 class="text-xl font-black font-heading text-slate-900 dark:text-white leading-tight uppercase tracking-tight">{{ $vendor->store_name }}</h4>
                     <p class="text-xs font-bold text-vendor-500 uppercase tracking-[0.2em] mt-2">Verified Merchant Node</p>
                </div>

                <div class="space-y-4 mb-10 pt-6 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 dark:border-slate-800">
                         <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Protocol Status</span>
                         <span class="text-[10px] font-black uppercase text-emerald-500 tracking-tighter bg-emerald-50 dark:bg-emerald-900/40 px-3 py-1 rounded-full border border-emerald-100 dark:border-emerald-800">{{ $vendor->status }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 dark:border-slate-800">
                         <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Settlement Rate</span>
                         <span class="text-sm font-black text-slate-900 dark:text-white font-heading">Net {{ 100 - $vendor->commission_rate }}% Yield</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-gradient-to-r from-vendor-600 to-indigo-600 text-white rounded-2xl text-base font-bold shadow-2xl shadow-vendor-600/30 hover:shadow-vendor-600/50 hover:scale-[1.02] transition-all transform flex items-center justify-center font-heading uppercase tracking-widest group">
                    <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Commit Protocol Changes
                </button>
                
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6 text-center leading-relaxed italic">System will verify checksum before committing to the digital ledger.</p>
            </div>
            
            <div class="mt-8 p-8 bg-slate-50 dark:bg-slate-800/40 rounded-[35px] border border-slate-100 dark:border-slate-800">
                <h5 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-4">Security Notice</h5>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Changing your bank account details will trigger a 48-hour settlement hold to prevent unauthorized drainage of store funds.</p>
            </div>
        </div>

    </form>
@endsection
