@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ quantity: 1, activeTab: 'description' }">
    
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-slate-500 mb-8 font-medium">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-slate-300">/</span>
        <a href="{{ route('products') }}" class="hover:text-primary transition-colors">Shop</a>
        <span class="mx-2 text-slate-300">/</span>
        <a href="{{ route('products', ['category' => $product->categories->first()?->slug]) }}" class="hover:text-primary transition-colors">{{ $product->categories->first()?->name }}</a>
        <span class="mx-2 text-slate-300">/</span>
        <span class="text-slate-900 dark:text-slate-300 truncate">{{ $product->name }}</span>
    </nav>

    <div class="glass-panel p-6 md:p-12 rounded-3xl grid grid-cols-1 lg:grid-cols-2 gap-16 border-none">
        
        <!-- Product Images -->
        <div class="flex flex-col space-y-4">
            <div class="w-full aspect-square bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm flex items-center justify-center border border-slate-100 dark:border-slate-800">
                @if($product->primary_image)
                    <img src="{{ $product->display_image }}" alt="{{ $product->name }}" class="object-cover w-full h-full transform transition-transform duration-500 hover:scale-110">
                @else
                    <svg class="w-32 h-32 text-slate-200 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
            </div>
            
            @if($product->images->count() > 0)
            <div class="flex space-x-4 overflow-x-auto custom-scrollbar py-2">
                @foreach($product->images as $image)
                    <div class="w-24 h-24 flex-shrink-0 bg-white dark:bg-slate-900 rounded-xl overflow-hidden cursor-pointer border border-transparent hover:border-primary transition duration-300">
                        <img src="{{ $image->display_url }}" alt="" class="object-cover w-full h-full">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div>
                    @if($product->brand)
                        <span class="text-primary dark:text-indigo-400 font-bold tracking-wider uppercase text-sm mb-2 block">{{ $product->brand->name }}</span>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-slate-900 dark:text-white leading-tight mb-4">{{ $product->name }}</h1>
                </div>
                <!-- Wishlist btn -->
                <button class="w-12 h-12 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition duration-300 group shadow-sm">
                    <svg class="w-6 h-6 transform group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </button>
            </div>

            <div class="flex items-center space-x-4 mb-6">
                <!-- Stars -->
                <div class="flex items-center text-amber-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                    <span class="ml-2 font-medium text-slate-700 dark:text-slate-300">{{ $product->getAverageRatingAttribute() }}</span>
                </div>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <a href="#reviews" class="text-sm font-medium text-slate-500 hover:text-primary transition underline underline-offset-4 decoration-slate-200 decoration-2">{{ $product->reviews_count ?? $product->reviews->count() }} Reviews</a>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <span class="text-sm font-medium text-slate-500"><span class="text-green-500 dark:text-emerald-400">●</span> {{ $product->quantity }} in stock</span>
            </div>

            <!-- Price -->
            <div class="text-3xl font-extrabold text-slate-900 dark:text-white mb-6">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-purple-500 dark:from-indigo-400 dark:to-purple-300">৳{{ number_format($product->price, 2) }}</span>
                @if($product->compare_price > $product->price)
                    <span class="text-lg font-medium text-slate-400 line-through ml-3">৳{{ number_format($product->compare_price, 2) }}</span>
                    <span class="text-sm font-bold text-red-500 bg-red-100 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-md ml-3 align-middle">-{{ $product->getDiscountPercentAttribute() }}%</span>
                @endif
            </div>

            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed mb-8 font-light">
                {{ $product->short_description ?? Str::limit(strip_tags($product->description), 150) }}
            </p>

            <!-- Actions block -->
            <div class="mt-auto border-t border-slate-100 dark:border-slate-800 pt-8">
                <div class="flex flex-wrap gap-4 items-center">
                    
                    <!-- Quantity Input -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-full h-14 border border-slate-200 dark:border-slate-700 p-1 w-32 shadow-inner">
                        <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </button>
                        <input type="number" x-model="quantity" min="1" max="{{ $product->quantity }}" class="w-full text-center bg-transparent border-none focus:ring-0 text-slate-900 dark:text-white font-semibold text-lg" readonly>
                        <button type="button" @click="if(quantity < {{ $product->quantity }}) quantity++" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <!-- Add to Cart -->
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" :value="quantity">
                        <button type="submit" class="btn-primary w-full h-14 text-lg shadow-[0_0_30px_rgba(79,70,229,0.3)]">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Add to Cart
                        </button>
                    </form>
                </div>
                
                <div class="mt-6 flex items-center justify-center space-x-6 text-sm text-slate-500 font-medium">
                    <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Free Delivery Available</span>
                    <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Secured Payment</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="mt-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm pt-8 px-8 pb-12">
        <div class="flex border-b border-slate-200 dark:border-slate-800 space-x-8">
            <button @click="activeTab = 'description'" :class="{ 'border-primary text-primary dark:text-indigo-400': activeTab === 'description', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'description' }" class="pb-4 font-heading font-bold text-xl border-b-2 transition-colors focus:outline-none">
                Description
            </button>
            <button @click="activeTab = 'specifications'" :class="{ 'border-primary text-primary dark:text-indigo-400': activeTab === 'specifications', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'specifications' }" class="pb-4 font-heading font-bold text-xl border-b-2 transition-colors focus:outline-none">
                Specifications
            </button>
            <button @click="activeTab = 'reviews'" :class="{ 'border-primary text-primary dark:text-indigo-400': activeTab === 'reviews', 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300': activeTab !== 'reviews' }" class="pb-4 font-heading font-bold text-xl border-b-2 transition-colors focus:outline-none" id="reviews">
                Reviews
            </button>
        </div>
        
        <div class="pt-8">
            <!-- Description Panel -->
            <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="prose max-w-none text-slate-600 dark:text-slate-400 prose-headings:text-slate-900 dark:prose-headings:text-white prose-a:text-indigo-500 text-lg leading-relaxed font-light">
                {!! $product->description !!}
            </div>
            
            <!-- Specifications Panel -->
            <div x-cloak x-show="activeTab === 'specifications'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @if($product->weight)
                        <tr>
                            <td class="py-4 font-medium text-slate-900 dark:text-slate-300 w-1/3">Weight</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $product->weight }} kg</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-4 font-medium text-slate-900 dark:text-slate-300 w-1/3">SKU</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $product->sku }}</td>
                        </tr>
                        @if($product->brand)
                        <tr>
                            <td class="py-4 font-medium text-slate-900 dark:text-slate-300 w-1/3">Brand</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $product->brand->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-4 font-medium text-slate-900 dark:text-slate-300 w-1/3">Sold By</td>
                            <td class="py-4 text-primary dark:text-indigo-400 font-bold"><a href="{{ route('stores.index') }}">{{ $product->vendor->store_name }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Reviews Panel -->
            <div x-cloak x-show="activeTab === 'reviews'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @if($product->reviews->isEmpty())
                    <p class="text-slate-500 italic">No reviews yet. Be the first to review this product!</p>
                @else
                    <div class="space-y-8">
                        @foreach($product->reviews as $review)
                            <div class="flex space-x-4 border-b border-slate-100 dark:border-slate-800 pb-6">
                                <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 dark:text-slate-300 text-lg flex-shrink-0">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="font-bold text-slate-900 dark:text-white">{{ $review->user->name }}</span>
                                        <span class="text-xs text-slate-400">&bull; {{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-amber-500 mb-3">
                                        @for($i = 0; $i < $review->rating; $i++)
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endfor
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-300">{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @auth
                <div class="mt-10">
                    <h4 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-4">Write a Review</h4>
                    <!-- review form placeholder -->
                    <textarea class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl p-4 text-slate-900 dark:text-white focus:ring-primary focus:border-primary mb-4" rows="4" placeholder="Share your thoughts..."></textarea>
                    <button class="btn-primary">Submit Review</button>
                </div>
                @else
                <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 rounded-xl p-4">
                    <p>Please <a href="{{ route('login') }}" class="font-bold underline">log in</a> to write a review.</p>
                </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-24">
        <h2 class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white mb-10 flex items-center">
            You might also like
            <div class="flex-grow h-px bg-slate-200 dark:bg-slate-800 ml-6"></div>
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $relProduct)
                @include('partials.product-card', ['product' => $relProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
