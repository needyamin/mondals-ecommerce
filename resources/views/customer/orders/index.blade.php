@extends('layouts.customer')
@section('title', 'My Orders')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-heading font-bold text-slate-900 dark:text-white">Order History</h2>
        <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Total orders: {{ $orders->total() }}</span>
    </div>

    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4">Payment</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total</th>
                        <th class="px-8 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-8 py-5 text-sm font-bold text-slate-900 dark:text-white">#{{ $order->order_number }}</td>
                            <td class="px-8 py-5 text-sm text-slate-500 dark:text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ 
                                    $order->payment_status === 'paid' ? 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400' : 
                                    'bg-rose-50 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400'
                                }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full {{ 
                                    $order->status === 'completed' ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 
                                    ($order->status === 'processing' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' :
                                    ($order->status === 'pending' ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' :
                                    'bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-400'))
                                }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-900 dark:text-white text-right">TK {{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="p-2 rounded-xl text-slate-400 hover:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-slate-500 dark:text-slate-400">No orders placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
        <div class="px-8 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
