@extends('layouts.admin')

@section('title', 'Product Catalog')

@section('content')

    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Active Products</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage global inventory, statuses, and vendor assignments.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.export', request()->query()) }}" class="btn btn-outline bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/30 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Product
            </a>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-2 mb-6 flex flex-col md:flex-row items-center justify-between">
        <div class="flex-grow w-full md:w-auto relative group">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-transparent border-none focus:ring-0 pl-12 pr-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500" placeholder="Search by name, SKU, or tags...">
        </div>
        <div class="w-full md:w-auto flex items-center gap-2 p-2 border-t md:border-t-0 md:border-l border-slate-100 dark:border-slate-800">
            <select name="status" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-0 py-2.5 px-4 cursor-pointer">
                <option value="">All Statuses</option>
                <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Approved</option>
                <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                <option value="draft" {{ request('status')==='draft'?'selected':'' }}>Draft</option>
                <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>Rejected</option>
            </select>
            <button type="submit" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 p-2.5 rounded-xl transition-colors" title="Apply Filters">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </button>
        </div>
    </form>

    <!-- Data Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Product</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Inventory</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Price</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Vendor</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($items as $product)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    <img src="{{ $product->display_image }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white font-heading group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">{{ $product->name }}</p>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block font-mono">SKU: {{ $product->sku }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium {{ $product->quantity > 0 ? 'text-slate-700 dark:text-slate-300' : 'text-rose-500 font-bold' }}">
                                {{ $product->quantity > 0 ? $product->quantity . ' in stock' : 'Out of Stock' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-extrabold text-slate-900 dark:text-white">৳{{ number_format($product->price, 2) }}</p>
                            @if($product->compare_price > $product->price)
                                <p class="text-xs text-slate-400 line-through">৳{{ number_format($product->compare_price, 2) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-medium">
                            {{ $product->vendor->store_name ?? 'Internal' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border 
                                {{ $product->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : '' }}
                                {{ $product->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50 dark:text-amber-400' : '' }}
                                {{ $product->status === 'draft' ? 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400' : '' }}
                            ">
                                @if($product->status === 'approved') <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> @endif
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No products found</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">You haven't added any products to your catalog yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            {{ $items->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endsection
