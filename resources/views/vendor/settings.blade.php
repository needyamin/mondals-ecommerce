@extends('layouts.vendor')

@section('title', 'Store settings')

@section('content')
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('vendor.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Dashboard</a>
        <a href="{{ route('vendor.products.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Products</a>
        <a href="{{ route('vendor.orders.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Orders</a>
        <a href="{{ route('vendor.earnings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Income</a>
        <span class="px-4 py-2 rounded-xl text-xs font-bold bg-vendor-600 text-white shadow-sm shadow-vendor-500/25">Settings</span>
    </div>

    <div class="mb-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-white dark:bg-darkpanel p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-vendor-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Store settings</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-xl">Update how your shop appears to customers, where you ship from, and your payout bank details.</p>
        </div>
        <div class="relative z-10 flex flex-wrap gap-3">
            @if($vendor->status === 'approved' && $vendor->slug)
                <a href="{{ route('stores.show', $vendor->slug) }}" target="_blank" rel="noopener" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-700">
                    <svg class="w-4 h-4 mr-2 text-vendor-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    View storefront
                </a>
            @endif
            <a href="{{ route('vendor.payouts.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-vendor-200 dark:border-vendor-800 bg-vendor-50 dark:bg-vendor-900/30 text-vendor-700 dark:text-vendor-300 text-sm font-bold hover:bg-vendor-100 dark:hover:bg-vendor-900/50 transition">
                Payout history
            </a>
        </div>
    </div>

    <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-12 gap-8 lg:gap-10">
        @csrf

        <div class="xl:col-span-8 space-y-8">
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-100 dark:bg-vendor-900/40 text-vendor-700 dark:text-vendor-300 text-sm font-bold">1</span>
                    Brand & contact
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Store name</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $vendor->store_name) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-transparent rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 focus:border-vendor-500/30 transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-transparent rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-transparent rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 transition">
                    </div>
                    <div class="md:col-span-2 space-y-3">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide ml-1">Logo & banner</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/30 hover:border-vendor-300 dark:hover:border-vendor-700 cursor-pointer transition group">
                                <div data-preview-box class="w-24 h-24 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 mb-3 overflow-hidden flex items-center justify-center shadow-sm group-hover:shadow-md transition">
                                    @if($vendor->logo)
                                        <img src="{{ asset('storage/'.$vendor->logo) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <span class="text-sm font-bold text-vendor-600 dark:text-vendor-400">Upload logo</span>
                                <span class="text-[11px] text-slate-400 mt-1">PNG or JPG, max 1&nbsp;MB</span>
                                <input type="file" name="logo" class="sr-only" accept="image/*">
                            </label>
                            <label class="flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/30 hover:border-vendor-300 dark:hover:border-vendor-700 cursor-pointer transition group">
                                <div data-preview-box class="w-full h-24 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 mb-3 overflow-hidden flex items-center justify-center shadow-sm group-hover:shadow-md transition">
                                    @if($vendor->banner)
                                        <img src="{{ asset('storage/'.$vendor->banner) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold text-slate-400">Store banner</span>
                                    @endif
                                </div>
                                <span class="text-sm font-bold text-vendor-600 dark:text-vendor-400">Upload banner</span>
                                <span class="text-[11px] text-slate-400 mt-1">Max 2&nbsp;MB</span>
                                <input type="file" name="banner" class="sr-only" accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
                <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-sm font-bold">2</span>
                    Address
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Street address</label>
                        <textarea name="address" rows="3" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-transparent rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 resize-none">{{ old('address', $vendor->address) }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $vendor->city) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 border border-transparent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">State / region</label>
                        <input type="text" name="state" value="{{ old('state', $vendor->state) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 border border-transparent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Postal code</label>
                        <input type="text" name="zip_code" value="{{ old('zip_code', $vendor->zip_code) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 border border-transparent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', $vendor->country) }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500 border border-transparent">
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-vendor-200/80 dark:border-vendor-800/60 bg-gradient-to-br from-vendor-50/90 via-white to-teal-50/40 dark:from-vendor-950/30 dark:via-darkpanel dark:to-slate-900/80 p-8 shadow-sm">
                <h2 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-2 flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-vendor-600 text-white text-sm font-bold shadow-lg shadow-vendor-500/25">3</span>
                    Payout bank details
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 ml-12 max-w-2xl">Used for withdrawals. Keep this accurate — payouts are sent to this account.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Bank name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', data_get($vendor->settings, 'banking.bank_name', '')) }}" class="w-full px-5 py-3.5 bg-white/90 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-700 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Account number</label>
                        <input type="text" name="account_number" value="{{ old('account_number', data_get($vendor->settings, 'banking.account_number', '')) }}" class="w-full px-5 py-3.5 bg-white/90 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-700 rounded-2xl font-mono text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500" autocomplete="off">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide ml-1">Account holder name</label>
                        <input type="text" name="account_name" value="{{ old('account_name', data_get($vendor->settings, 'banking.account_name', '')) }}" class="w-full px-5 py-3.5 bg-white/90 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-700 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-vendor-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 space-y-6 xl:sticky xl:top-24 self-start">
            <div class="bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-vendor-100 to-teal-100 dark:from-vendor-900/50 dark:to-slate-800 border-2 border-white dark:border-slate-700 shadow-md flex items-center justify-center overflow-hidden mb-4">
                        @if($vendor->logo)
                            <img src="{{ asset('storage/'.$vendor->logo) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-black text-vendor-600 dark:text-vendor-400">{{ strtoupper(substr($vendor->store_name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">{{ $vendor->store_name }}</h3>
                    @php
                        $st = $vendor->status;
                        $stClass = match ($st) {
                            'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-800',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 ring-amber-200 dark:ring-amber-800',
                            'suspended' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 ring-rose-200 dark:ring-rose-800',
                            'rejected' => 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 ring-slate-300',
                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        };
                    @endphp
                    <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ring-1 {{ $stClass }}">{{ $st }}</span>
                </div>

                <dl class="space-y-3 text-sm border-t border-slate-100 dark:border-slate-800 pt-6">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500 dark:text-slate-400">Your share after platform fee</dt>
                        <dd class="font-bold text-slate-900 dark:text-white tabular-nums">{{ number_format(max(0, 100 - (float) $vendor->commission_rate), 1) }}%</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500 dark:text-slate-400">Platform commission</dt>
                        <dd class="font-bold text-slate-600 dark:text-slate-300 tabular-nums">{{ number_format((float) $vendor->commission_rate, 1) }}%</dd>
                    </div>
                </dl>

                <button type="submit" class="mt-8 w-full py-4 rounded-2xl bg-gradient-to-r from-vendor-600 to-teal-600 text-white text-sm font-bold shadow-lg shadow-vendor-600/25 hover:shadow-vendor-600/40 hover:brightness-105 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save changes
                </button>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 text-center mt-4 leading-relaxed">Changes to bank details may be reviewed before the next payout.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-5">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tip</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Use the same business name on your bank account as on your store where possible — it speeds up payout verification.</p>
            </div>
        </div>
    </form>
    <script>
        document.querySelectorAll('input[type=file][name=logo], input[type=file][name=banner]').forEach(function (inp) {
            inp.addEventListener('change', function () {
                var f = this.files && this.files[0];
                var box = this.closest('label') && this.closest('label').querySelector('[data-preview-box]');
                if (!box || !f || f.type.indexOf('image/') !== 0) return;
                box.innerHTML = '<img src="' + URL.createObjectURL(f) + '" alt="" class="w-full h-full object-cover">';
            });
        });
    </script>
@endsection
