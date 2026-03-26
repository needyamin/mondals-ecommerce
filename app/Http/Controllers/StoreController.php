<?php

namespace App\Http\Controllers;

use App\Models\{Plugin, Product, Vendor};
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Public vendor store page.
     */
    public function show(string $slug)
    {
        $vendorQuery = Vendor::where('slug', $slug)->approved();
        if (Plugin::isActiveSlug('product-reviews')) {
            $vendorQuery->with(['products.reviews']);
        }
        $vendor = $vendorQuery->firstOrFail();

        $products = Product::published()
            ->byVendor($vendor->id)
            ->with(['images' => fn ($q) => $q->primary(), 'brand'])
            ->sorted(request('sort'), '-created_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.store-detail', compact('vendor', 'products'));
    }

    /**
     * List all vendor stores.
     */
    public function index(Request $request)
    {
        $filters = array_filter(
            $request->only(['search']),
            fn ($v) => $v !== null && $v !== ''
        );

        $query = Vendor::approved()->withCount([
            'products as published_products_count' => fn ($q) => $q->published(),
        ]);

        if (Plugin::isActiveSlug('product-reviews')) {
            $query->with([
                'products' => fn ($q) => $q->published()->with('reviews:id,product_id,rating'),
            ]);
        }

        $query->filter($filters);

        match ($request->input('sort', 'newest')) {
            'name' => $query->orderBy('store_name'),
            'name_desc' => $query->orderByDesc('store_name'),
            'products' => $query->orderByDesc('published_products_count'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $vendors = $query->paginate(12)->withQueryString();

        return view('pages.stores', compact('vendors'));
    }
}
