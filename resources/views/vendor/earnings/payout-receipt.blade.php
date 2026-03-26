@extends('layouts.vendor')

@section('title', 'Payout '.$payout->payout_number)

@section('content')
    <div class="max-w-lg mx-auto bg-white dark:bg-darkpanel rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm print:shadow-none">
        <h1 class="text-2xl font-black font-heading text-slate-900 dark:text-white mb-2">Payout receipt</h1>
        <p class="text-sm text-slate-500 font-mono mb-6">{{ $payout->payout_number }}</p>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Amount</dt><dd class="font-bold text-slate-900 dark:text-white">৳{{ number_format($payout->amount, 2) }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Net</dt><dd class="font-bold">৳{{ number_format($payout->net_amount, 2) }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Platform fee</dt><dd>৳{{ number_format($payout->commission_amount, 2) }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Status</dt><dd class="uppercase font-bold">{{ $payout->status }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Method</dt><dd>{{ $payout->payment_method ?? '—' }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2"><dt class="text-slate-500">Reference</dt><dd class="font-mono text-xs">{{ $payout->transaction_id ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Date</dt><dd>{{ ($payout->paid_at ?? $payout->created_at)->format('M d, Y H:i') }}</dd></div>
        </dl>

        @if($payout->notes)
            <p class="mt-6 text-xs text-slate-500 border-t border-slate-100 dark:border-slate-800 pt-4">{{ $payout->notes }}</p>
        @endif

        <div class="mt-8 flex gap-3 no-print">
            <button type="button" onclick="window.print()" class="flex-1 py-3 rounded-xl bg-slate-900 text-white text-sm font-bold">Print</button>
            <a href="{{ route('vendor.payouts.index') }}" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-700 text-center text-sm font-bold text-slate-700 dark:text-slate-300">Back</a>
        </div>
    </div>
    <style>@media print { .no-print { display: none !important; } }</style>
@endsection
