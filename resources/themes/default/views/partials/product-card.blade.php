<div class="group relative overflow-hidden rounded-2xl glass-panel flex flex-col h-full bg-white dark:bg-slate-800 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/10">
    <!-- Image -->
    <a href="{{ route('product.detail', $product->slug) }}" class="relative block aspect-[4/5] overflow-hidden bg-slate-100 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-700/50">
        @if($product->primary_image)
            <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110">
        @else
            <!-- Placeholder -->
            <div class="absolute inset-0 flex items-center justify-center text-slate-300 dark:text-slate-700">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        <!-- Badges -->
        <div class="absolute top-3 left-3 flex flex-col space-y-2">
            @if($product->getDiscountPercentAttribute())
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-md backdrop-blur-md bg-opacity-90">
                    -{{ $product->getDiscountPercentAttribute() }}%
                </span>
            @endif
            @if($product->is_featured)
                <span class="bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded shadow-md backdrop-blur-md bg-opacity-90">
                    Hot
                </span>
            @endif
        </div>

        <!-- Quick actions -->
        <div class="absolute inset-0 bg-black/20 dark:bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-4">
            <button class="bg-white text-slate-900 w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-colors transform translate-y-4 group-hover:translate-y-0 duration-300" title="Quick View">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
            <button class="bg-white text-slate-900 w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:bg-red-500 hover:text-white transition-colors transform translate-y-8 group-hover:translate-y-0 duration-300 delay-75" title="Add to Wishlist">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </button>
        </div>
    </a>

    <!-- Content -->
    <div class="p-5 flex flex-col flex-grow">
        <div class="flex items-center justify-between mb-2">
            <a href="{{ route('products', ['category' => $product->categories->first()?->slug]) }}" class="text-xs font-semibold uppercase tracking-wider text-indigo-500 dark:text-indigo-400 hover:underline">
                {{ $product->categories->first()?->name ?? 'Uncategorized' }}
            </a>
            
            <!-- Rating -->
            <div class="flex items-center text-amber-400 text-xs">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="ml-1 text-slate-500 dark:text-slate-400 font-medium">{{ $product->getAverageRatingAttribute() }}</span>
            </div>
        </div>

        <h3 class="font-heading font-semibold text-lg text-slate-900 dark:text-white leading-tight mb-2 flex-grow hover:text-primary dark:hover:text-indigo-400 transition-colors">
            <a href="{{ route('product.detail', $product->slug) }}">
                {{ \Illuminate\Support\Str::limit($product->name, 45) }}
            </a>
        </h3>

        <!-- Price -->
        <div class="flex items-end justify-between mt-4 border-t border-slate-100 dark:border-slate-700/50 pt-4">
            <div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300">
                    ৳{{ number_format($product->price, 2) }}
                </span>
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-sm text-slate-400 dark:text-slate-500 line-through ml-2">৳{{ number_format($product->compare_price, 2) }}</span>
                @endif
            </div>

            <!-- Add to Cart (Working form) -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="bg-slate-100 dark:bg-slate-700 hover:bg-primary hover:text-white dark:hover:bg-indigo-500 dark:text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-slate-900" title="Add to Cart">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>
