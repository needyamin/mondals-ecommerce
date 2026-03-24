<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plugin;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::with(['brand'])->where('status', 'approved')->where('is_featured', true)->latest()->take(8)->get();
        $newArrivals = Product::with(['brand'])->where('status', 'approved')->latest()->take(8)->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->take(6)->get();

        return view('pages.home', compact('featuredProducts', 'newArrivals', 'categories'));
    }

    public function products(Request $request)
    {
        $query = Product::with(['brand', 'categories'])->where('status', 'approved');

        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category') && $request->category) {
            $categorySlug = $request->category;
            $query->whereHas('categories', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'bestsellers':
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
            default: // latest
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        
        return view('pages.products', compact('products'));
    }

    public function productDetail($slug)
    {
        $product = Product::with(['brand', 'categories', 'images', 'vendor'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $reviewsEnabled = Plugin::isActiveSlug('product-reviews');
        $userHasReviewed = false;

        if ($reviewsEnabled) {
            $product->load([
                'reviews' => fn ($q) => $q->where('status', 'approved')->with('user')->latest(),
            ]);
            $userHasReviewed = auth()->check()
                && \Plugins\ProductReviews\Models\Review::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
        }

        // Increment views
        $product->incrementViews();

        $relatedProducts = Product::whereHas('categories', function ($q) use ($product) {
            $q->whereIn('categories.id', $product->categories->pluck('id'));
        })->where('id', '!=', $product->id)->where('status', 'approved')->take(4)->get();

        return view('pages.product-detail', compact('product', 'relatedProducts', 'userHasReviewed', 'reviewsEnabled'));
    }
}
