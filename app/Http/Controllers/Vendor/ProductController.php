<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{Product, Brand, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * List vendor's own products.
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $products = Product::byVendor($vendor->id)
            ->with('brand', 'category')
            ->filter($request->all())
            ->sorted($request->input('sort'), '-created_at')
            ->paginate(15)
            ->withQueryString();

        return view('vendor.products.index', compact('products'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('vendor.products.form', [
            'product'    => null,
            'brands'     => Brand::active()->pluck('name', 'id'),
            'categories' => Category::active()->pluck('name', 'id'),
        ]);
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|max:50|unique:products,sku',
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
        ]);

        $validated['vendor_id']    = $vendor->id;
        $validated['slug']         = Str::slug($validated['name']);
        $validated['status']       = 'pending'; // Requires admin approval
        $validated['is_active']    = true;
        $validated['published_at'] = null; // Set when approved

        Product::create($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product submitted for approval.');
    }

    /**
     * Edit form.
     */
    public function edit(int $id)
    {
        $vendor = auth()->user()->vendor;
        $product = Product::byVendor($vendor->id)->findOrFail($id);

        return view('vendor.products.form', [
            'product'    => $product,
            'brands'     => Brand::active()->pluck('name', 'id'),
            'categories' => Category::active()->pluck('name', 'id'),
        ]);
    }

    /**
     * Update product.
     */
    public function update(Request $request, int $id)
    {
        $vendor = auth()->user()->vendor;
        $product = Product::byVendor($vendor->id)->findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => "required|string|max:50|unique:products,sku,{$id}",
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(int $id)
    {
        $vendor = auth()->user()->vendor;
        Product::byVendor($vendor->id)->findOrFail($id)->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product deleted.');
    }
}
