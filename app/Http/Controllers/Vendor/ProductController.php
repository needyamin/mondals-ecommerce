<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{Product, Brand, Category};
use App\Services\ProductGalleryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductGalleryService $gallery
    ) {}

    public function index(Request $request)
    {
        $vendor = $this->vendorOrFail();

        $filters = array_filter(
            $request->only(['search', 'status']),
            fn ($v) => $v !== null && $v !== ''
        );

        $products = Product::byVendor($vendor->id)
            ->with(['brand', 'category', 'images'])
            ->filter($filters)
            ->sorted($request->input('sort'), '-created_at')
            ->paginate(15)
            ->withQueryString();

        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        return view('vendor.products.form', [
            'product'    => null,
            'brands'     => Brand::active()->pluck('name', 'id'),
            'categories' => Category::active()->pluck('name', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $vendor = $this->vendorOrFail();

        $validated = $request->validate($this->productRules());

        $validated['vendor_id'] = $vendor->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'pending';
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = false;
        $validated['is_digital'] = $request->boolean('is_digital');
        $validated['is_taxable'] = $request->boolean('is_taxable');
        $validated['track_quantity'] = $request->boolean('track_quantity');
        $validated['allow_backorder'] = $request->boolean('allow_backorder');
        $validated['visibility'] = $request->input('visibility', 'public');
        $validated['low_stock_threshold'] = $request->input('low_stock_threshold', 5);
        $validated['published_at'] = null;

        $product = Product::create($validated);

        $imgErr = $this->gallery->syncGallery($product, $request, true);
        $msg = 'Product submitted for approval.';
        if ($imgErr !== []) {
            $msg .= ' '.implode(' ', $imgErr);
        }

        return redirect()->route('vendor.products.index')->with('success', $msg);
    }

    public function edit(int $id)
    {
        $vendor = $this->vendorOrFail();
        $product = Product::byVendor($vendor->id)->with('images')->findOrFail($id);

        return view('vendor.products.form', [
            'product'    => $product,
            'brands'     => Brand::active()->pluck('name', 'id'),
            'categories' => Category::active()->pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $vendor = $this->vendorOrFail();
        $product = Product::byVendor($vendor->id)->with('images')->findOrFail($id);

        $validated = $request->validate($this->productRules($id));

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'pending';
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_digital'] = $request->boolean('is_digital');
        $validated['is_taxable'] = $request->boolean('is_taxable');
        $validated['track_quantity'] = $request->boolean('track_quantity');
        $validated['allow_backorder'] = $request->boolean('allow_backorder');
        $validated['visibility'] = $request->input('visibility', 'public');
        $validated['low_stock_threshold'] = $request->input('low_stock_threshold', 5);

        $product->update($validated);

        $imgErr = $this->gallery->syncGallery($product, $request, false);
        $msg = 'Product updated successfully.';
        if ($imgErr !== []) {
            $msg .= ' '.implode(' ', $imgErr);
        }

        return redirect()->route('vendor.products.index')->with('success', $msg);
    }

    public function destroy(int $id)
    {
        $vendor = $this->vendorOrFail();
        Product::byVendor($vendor->id)->findOrFail($id)->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product deleted.');
    }

    private function vendorOrFail()
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Vendor profile not found.');

        return $vendor;
    }

    /** @return array<string, mixed> */
    private function productRules(?int $id = null): array
    {
        $skuUnique = $id
            ? "required|string|max:50|unique:products,sku,{$id}"
            : 'required|string|max:50|unique:products,sku';

        return [
            'name'                   => 'required|string|max:255',
            'sku'                    => $skuUnique,
            'brand_id'               => 'nullable|exists:brands,id',
            'category_id'            => 'required|exists:categories,id',
            'short_description'      => 'nullable|string|max:500',
            'description'            => 'nullable|string',
            'price'                  => 'required|numeric|min:0',
            'compare_price'          => 'nullable|numeric|min:0',
            'cost_price'             => 'nullable|numeric|min:0',
            'quantity'               => 'required|integer|min:0',
            'low_stock_threshold'    => 'nullable|integer|min:0',
            'weight'                 => 'nullable|numeric|min:0',
            'length'                 => 'nullable|numeric|min:0',
            'width'                  => 'nullable|numeric|min:0',
            'height'                 => 'nullable|numeric|min:0',
            'visibility'             => 'nullable|in:public,hidden,catalog_only,search_only',
            'meta_title'             => 'nullable|string|max:70',
            'meta_description'     => 'nullable|string|max:160',
            'meta_keywords'        => 'nullable|string|max:255',
            'images'               => 'nullable|array',
            'images.*'             => 'image|max:4096',
            'primary_image_index'  => 'nullable|integer|min:0',
            'remove_images'        => 'nullable|array',
            'remove_images.*'      => 'integer',
            'image_order'          => 'nullable|array',
            'image_order.*'        => 'integer',
            'set_primary'          => 'nullable|integer',
        ];
    }
}
