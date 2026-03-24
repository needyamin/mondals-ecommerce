@extends('layouts.admin')

@section('title', 'Marketing & tracking')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Marketing &amp; tracking</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1">Pixels, Google tags, and custom snippets load on the public storefront (not admin or vendor panel).</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.marketing.update') }}" method="POST" class="max-w-3xl space-y-8">
    @csrf
    @method('PUT')

    <div class="bg-white dark:bg-darkpanel rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Google</h3>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">GA4 measurement ID</label>
            <input type="text" name="ga4_measurement_id" value="{{ old('ga4_measurement_id', $values['ga4_measurement_id'] ?? '') }}" placeholder="G-XXXXXXXXXX"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-white font-mono text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Google Ads ID (gtag, e.g. AW-XXXXXXXXX)</label>
            <input type="text" name="google_ads_id" value="{{ old('google_ads_id', $values['google_ads_id'] ?? '') }}" placeholder="AW-XXXXXXXXX"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-white font-mono text-sm">
        </div>
    </div>

    <div class="bg-white dark:bg-darkpanel rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Pixels</h3>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Meta (Facebook) pixel ID</label>
            <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $values['facebook_pixel_id'] ?? '') }}" placeholder="Numeric pixel ID"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-white font-mono text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">TikTok pixel ID</label>
            <input type="text" name="tiktok_pixel_id" value="{{ old('tiktok_pixel_id', $values['tiktok_pixel_id'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-white font-mono text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">LinkedIn partner ID</label>
            <input type="text" name="linkedin_partner_id" value="{{ old('linkedin_partner_id', $values['linkedin_partner_id'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-white font-mono text-sm">
        </div>
    </div>

    <div class="bg-white dark:bg-darkpanel rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Custom snippets</h3>
        <p class="text-xs text-slate-500">Injected as-is (trusted admins only). Use for GTM container, Pinterest, Twitter, etc.</p>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Before &lt;/head&gt;</label>
            <textarea name="custom_head_html" rows="6" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm font-mono text-slate-900 dark:text-white">{{ old('custom_head_html', $values['custom_head_html'] ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Before &lt;/body&gt;</label>
            <textarea name="custom_body_html" rows="6" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm font-mono text-slate-900 dark:text-white">{{ old('custom_body_html', $values['custom_body_html'] ?? '') }}</textarea>
        </div>
    </div>

    <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-lg shadow-brand-500/25">Save</button>
</form>
@endsection
