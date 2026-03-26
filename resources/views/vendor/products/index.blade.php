@extends('layouts.vendor')

@section('title', 'My Inventory')

@section('content')
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-10 flex flex-col md:flex-row justify-between items-center group transition duration-300">
        <div class="z-10 text-center md:text-left">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Manage Products</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg font-light leading-relaxed">View and update your store's catalog.</p>
        </div>
        <div class="mt-6 md:mt-0 flex space-x-3">
            <a href="{{ route('vendor.products.create') }}" class="px-6 py-2.5 rounded-xl bg-vendor-600 hover:bg-vendor-700 text-white font-bold shadow-lg shadow-vendor-500/30 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Product
            </a>
        </div>
    </div>

    @php($chipQ = array_filter(request()->only(['search', 'sort'])))

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('vendor.products.index', $chipQ) }}" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-darkpanel text-center text-xs font-bold text-slate-700 dark:text-slate-300 {{ !request('status') ? 'ring-2 ring-vendor-500' : '' }}">All</a>
        @foreach(['draft' => 'Draft', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $st => $label)
            <a href="{{ route('vendor.products.index', array_merge($chipQ, ['status' => $st])) }}" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-darkpanel text-center text-xs font-bold text-slate-700 dark:text-slate-300 {{ request('status') === $st ? 'ring-2 ring-vendor-500 bg-vendor-50/50 dark:bg-vendor-900/20' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <!-- Filters & Stats Overview -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-10">

        <div class="xl:col-span-9 bg-white dark:bg-darkpanel rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <form action="{{ route('vendor.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Search (Name or SKU)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Signature leather bag…" class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-900 dark:text-white shadow-inner">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 mb-2 block">Sort By</label>
                    <select name="sort" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-vendor-500 text-slate-600 dark:text-slate-300 shadow-inner">
                        <option value="-created_at" {{ request('sort', '-created_at') === '-created_at' ? 'selected' : '' }}>Newest</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Oldest</option>
                        <option value="-price" {{ request('sort') === '-price' ? 'selected' : '' }}>Price high</option>
                        <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price low</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A–Z</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full h-[50px] bg-slate-900 dark:bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-800 transition flex items-center justify-center group">
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="xl:col-span-3 bg-vendor-600 dark:bg-vendor-900/40 rounded-3xl px-5 py-4 text-white shadow-xl shadow-vendor-600/20 flex items-center gap-3 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-lg pointer-events-none"></div>
            <span class="relative text-3xl font-extrabold font-heading tabular-nums leading-none">{{ $products->total() }}</span>
            <span class="relative text-[11px] font-bold uppercase tracking-widest text-vendor-100 leading-tight">Listed<br>Products</span>
        </div>

    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/10">
                        <th class="px-8 py-5">Product Info</th>
                        <th class="px-8 py-5">Price</th>
                        <th class="px-8 py-5">Stock</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5">Created</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-slate-200 dark:text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <p class="text-lg font-bold text-slate-400">No products found</p>
                                    <p class="text-sm mt-1">Start by adding your first product to your store.</p>
                                    <a href="{{ route('vendor.products.create') }}" class="mt-4 text-vendor-600 font-bold hover:underline">Add Product &plus;</a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @foreach($products as $product)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex-shrink-0 mr-4 overflow-hidden border border-slate-100 dark:border-slate-700">
                                        @if($product->primary_image)
                                            <img src="{{ $product->display_image }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono uppercase tracking-widest font-bold">{{ $product->sku }}</p>
                                        @if($product->category)
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $product->category->name }}</p>
                                        @endif
                                        @unless($product->is_active)
                                            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Inactive</span>
                                        @endunless
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">৳{{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price > $product->price)
                                        <span class="text-xs text-slate-400 line-through mt-0.5">৳{{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($product->track_quantity)
                                    <div class="flex items-center gap-1.5">
                                        @if($product->quantity > ($product->low_stock_threshold !== null ? (int) $product->low_stock_threshold : 5))
                                            <span class="text-emerald-500 font-bold text-base">{{ $product->quantity }}</span>
                                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">In stock</span>
                                        @else
                                            <span class="text-amber-500 font-bold text-base">{{ $product->quantity }}</span>
                                            <span class="text-[10px] text-amber-500 uppercase font-bold tracking-widest">Low</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs font-medium italic">Not tracked</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($product->status === 'draft')
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700">Draft</span>
                                @elseif($product->status === 'approved')
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50">Approved</span>
                                @elseif($product->status === 'pending')
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 border-amber-200 dark:border-amber-800/50">Pending</span>
                                @elseif($product->status === 'rejected')
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400 border-rose-200 dark:border-rose-800/50">Rejected</span>
                                @else
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border bg-slate-100 text-slate-600 border-slate-200">{{ ucfirst($product->status) }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $product->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $product->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('vendor.products.edit', $product->id) }}" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-vendor-600 dark:hover:text-vendor-400 rounded-lg hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:shadow-md transition-all border border-slate-100 dark:border-slate-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-8 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
