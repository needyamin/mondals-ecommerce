<?php

namespace App\Http\Controllers;

use App\Models\{Plugin, Product, Vendor};
use App\Services\ProductService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Public vendor store page.
     */
    public function show(string $slug)
    {
        $vendor = Vendor::where('slug', $slug)->approved()->firstOrFail();
        
        $products = Product::published()
            ->byVendor($vendor->id)
            ->with(['images' => fn($q) => $q->primary(), 'brand'])
            ->sorted(request('sort'), '-created_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.store-detail', compact('vendor', 'products'));
    }

    /**
     * List all vendor stores.
     */
    public function index()
    {
        $vendors = Vendor::approved()
            ->withCount('products');
        if (Plugin::isActiveSlug('product-reviews')) {
            $vendors->with(['products.reviews']);
        }
        $vendors = $vendors->latest()->paginate(12);

        return view('pages.stores', compact('vendors'));
    }
}
