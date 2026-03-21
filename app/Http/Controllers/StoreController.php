<?php

namespace App\Http\Controllers;

use App\Models\{Vendor, Product};
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
            ->withCount('products')
            ->with(['products.reviews'])
            ->latest()
            ->paginate(12);

        return view('pages.stores', compact('vendors'));
    }
}
