@extends('layouts.vendor')

@section('title', 'My Inventory')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-10">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading">Manage Products</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2">View and update your store's catalog.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('vendor.products.create') }}" class="bg-vendor-600 hover:bg-vendor-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-vendor-500/30 transition flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 mb-8">
        <form action="{{ route('vendor.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-900 dark:text-white">
                </div>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-900 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-slate-900 dark:bg-slate-700 text-white py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition">Filter</button>
            </div>
        </form>
    </div>

    <!-- Product Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                        <th class="px-6 py-4">Product Info</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 flex-shrink-0 mr-4 overflow-hidden border border-slate-100 dark:border-slate-700">
                                        @if($product->primary_image)
                                            <img src="{{ $product->display_image }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white mb-0.5">{{ $product->name }}</p>
                                        <p class="text-slate-400 text-xs font-mono tracking-tighter">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 dark:text-white">৳{{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price > $product->price)
                                        <span class="text-xs text-slate-400 line-through">৳{{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="{{ $product->quantity > 5 ? 'text-emerald-500' : 'text-rose-500' }} font-bold text-base mr-1">{{ $product->quantity }}</span>
                                    <span class="text-xs text-slate-400 uppercase font-medium">Available</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                        'pending'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 border-amber-200 dark:border-amber-800/50',
                                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400 border-rose-200 dark:border-rose-800/50',
                                    ];
                                    $colorClass = $statusColors[$product->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $colorClass }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs font-medium">
                                {{ $product->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('vendor.products.edit', $product->id) }}" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-vendor-600 dark:hover:text-vendor-400 rounded-lg hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <p class="text-lg font-bold text-slate-400">No products found</p>
                                    <p class="text-sm">Start by adding your first product to your store.</p>
                                    <a href="{{ route('vendor.products.create') }}" class="mt-4 text-vendor-600 font-bold hover:underline">Add Product &plus;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
