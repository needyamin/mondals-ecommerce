<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Product, Vendor, Brand, Category, Attribute};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Traits\ExportsToCsv;

class ProductController extends BaseCrudController
{
    use ExportsToCsv;
    protected string $model = Product::class;
    protected string $viewPrefix = 'admin.products';
    protected string $routePrefix = 'admin.products';
    protected array $with = ['vendor', 'brand', 'category', 'images'];
    protected array $searchable = ['name', 'sku'];

    protected function validationRules(?Model $item = null): array
    {
        $unique = $item ? ",{$item->id}" : '';
        return [
            'name'              => 'required|string|max:255',
            'sku'               => "required|string|max:50|unique:products,sku{$unique}",
            'vendor_id'         => 'required|exists:vendors,id',
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
            'status'            => 'required|in:draft,pending,approved,rejected',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
        ];
    }

    protected function formData(?Model $item = null): array
    {
        return [
            'vendors'    => Vendor::approved()->pluck('store_name', 'id'),
            'brands'     => Brand::active()->pluck('name', 'id'),
            'categories' => Category::active()->pluck('name', 'id'),
        ];
    }

    protected function beforeSave(array $data, Request $request, ?Model $item = null): array
    {
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        if (!$item) {
            $data['published_at'] = $data['status'] === 'approved' ? now() : null;
        }
        return $data;
    }

    protected function applyFilters($query, Request $request)
    {
        if ($status = $request->input('status')) $query->where('status', $status);
        if ($vendor = $request->input('vendor_id')) $query->where('vendor_id', $vendor);
        if ($brand = $request->input('brand_id')) $query->where('brand_id', $brand);
        return $query;
    }

    public function export(Request $request)
    {
        $query = Product::with(['vendor', 'brand', 'category']);
        
        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('name', 'LIKE', "%{$search}%")->orWhere('sku', 'LIKE', "%{$search}%"));
        }

        $query = $this->applyFilters($query, $request);

        return $this->exportCsv($query, 'products-catalog', [
            'SKU'           => 'sku',
            'Name'          => 'name',
            'Category'      => 'category.name',
            'Brand'         => 'brand.name',
            'Price'         => 'price',
            'Compare Price' => 'compare_price',
            'Quantity'      => 'quantity',
            'Status'        => 'status',
            'Vendor'        => 'vendor.store_name',
            'Created At'    => 'created_at',
        ]);
    }
}
