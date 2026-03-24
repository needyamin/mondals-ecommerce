<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Product, ProductImage, Vendor, Brand, Category, Attribute};
use App\Support\MediaDisks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

    public function update(Request $request, int $id)
    {
        $item = Product::findOrFail($id);

        $request->validate($this->allValidationRules($item));

        $item->update([
            'name'              => $request->input('name'),
            'slug'              => Str::slug($request->input('name')),
            'sku'               => $request->input('sku'),
            'vendor_id'         => $request->input('vendor_id'),
            'brand_id'          => $request->input('brand_id'),
            'category_id'       => $request->input('category_id'),
            'short_description' => $request->input('short_description'),
            'description'       => $request->input('description'),
            'price'             => $request->input('price'),
            'compare_price'     => $request->input('compare_price'),
            'cost_price'        => $request->input('cost_price'),
            'quantity'          => $request->input('quantity'),
            'low_stock_threshold' => $request->input('low_stock_threshold', 5),
            'status'            => $request->input('status'),
            'visibility'        => $request->input('visibility', 'public'),
            'is_active'         => $request->boolean('is_active'),
            'is_featured'       => $request->boolean('is_featured'),
            'is_digital'        => $request->boolean('is_digital'),
            'is_taxable'        => $request->boolean('is_taxable'),
            'track_quantity'    => $request->boolean('track_quantity'),
            'allow_backorder'   => $request->boolean('allow_backorder'),
            'weight'            => $request->input('weight'),
            'length'            => $request->input('length'),
            'width'             => $request->input('width'),
            'height'            => $request->input('height'),
            'meta_title'        => $request->input('meta_title'),
            'meta_description'  => $request->input('meta_description'),
            'meta_keywords'     => $request->input('meta_keywords'),
        ]);

        $errors = $this->handleImages($item, $request);

        $msg = 'Product updated successfully.';
        if ($errors) {
            $msg .= ' Image issues: ' . implode('; ', $errors);
        }

        return redirect()->route('admin.products.edit', $item->id)->with('success', $msg);
    }

    /** @return string[] error messages */
    private function handleImages(Product $item, Request $request): array
    {
        $errors = [];
        $disk = MediaDisks::productDisk() ?: 'public';

        foreach ($request->input('remove_images', []) as $imgId) {
            $img = $item->images()->find($imgId);
            if ($img) {
                try {
                    if (filled($img->image)) {
                        Storage::disk($disk)->delete($img->image);
                    }
                } catch (\Throwable) {}
                $img->delete();
            }
        }

        if ($request->filled('image_order')) {
            foreach ($request->input('image_order') as $i => $imgId) {
                $item->images()->where('id', $imgId)->update(['sort_order' => (int) $i]);
            }
        }

        $files = $request->file('images');
        if ($files) {
            if (!is_array($files)) {
                $files = [$files];
            }

            $destDir = storage_path('app/public/products');
            File::ensureDirectoryExists($destDir);

            $order = (int) $item->images()->max('sort_order');
            $noPrimary = !$item->images()->where('is_primary', true)->exists();

            foreach ($files as $idx => $file) {
                if (!$file instanceof \Illuminate\Http\UploadedFile || !$file->isValid()) {
                    $errors[] = "File #{$idx} invalid or missing";
                    continue;
                }

                try {
                    $ext = $file->guessExtension() ?: 'jpg';
                    $name = Str::random(40) . '.' . $ext;
                    $file->move($destDir, $name);
                    $path = 'products/' . $name;
                } catch (\Throwable $e) {
                    $errors[] = "File #{$idx}: " . $e->getMessage();
                    continue;
                }

                $order++;
                $isPrimary = $noPrimary && $idx === 0;
                ProductImage::create([
                    'product_id' => $item->id,
                    'image'      => $path,
                    'sort_order' => $order,
                    'is_primary' => $isPrimary,
                ]);
                if ($isPrimary) $noPrimary = false;
            }
        }

        if ($request->filled('set_primary')) {
            $item->images()->update(['is_primary' => false]);
            $item->images()->where('id', $request->input('set_primary'))->update(['is_primary' => true]);
        }

        if ($item->images()->count() && !$item->images()->where('is_primary', true)->exists()) {
            $item->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return $errors;
    }

    private function allValidationRules(?Product $item = null): array
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
            'cost_price'        => 'nullable|numeric|min:0',
            'quantity'          => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'status'            => 'required|in:draft,pending,approved,rejected',
            'visibility'        => 'nullable|in:public,hidden,catalog_only,search_only',
            'weight'            => 'nullable|numeric|min:0',
            'length'            => 'nullable|numeric|min:0',
            'width'             => 'nullable|numeric|min:0',
            'height'            => 'nullable|numeric|min:0',
            'meta_title'        => 'nullable|string|max:70',
            'meta_description'  => 'nullable|string|max:160',
            'meta_keywords'     => 'nullable|string|max:255',
        ];
    }

    protected function validationRules(?Model $item = null): array
    {
        return $this->allValidationRules($item instanceof Product ? $item : null);
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
        $data['is_digital'] = $request->boolean('is_digital');
        $data['is_taxable'] = $request->boolean('is_taxable');
        $data['track_quantity'] = $request->boolean('track_quantity');
        $data['allow_backorder'] = $request->boolean('allow_backorder');
        $data['visibility'] = $request->input('visibility', 'public');
        $data['low_stock_threshold'] = $request->input('low_stock_threshold', 5);
        if (!$item) {
            $data['published_at'] = $data['status'] === 'approved' ? now() : null;
        }
        return $data;
    }

    protected function afterSave(Model $item, Request $request): void
    {
        $this->handleImages($item, $request);
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
            'SKU' => 'sku', 'Name' => 'name', 'Category' => 'category.name',
            'Brand' => 'brand.name', 'Price' => 'price', 'Compare Price' => 'compare_price',
            'Quantity' => 'quantity', 'Status' => 'status', 'Vendor' => 'vendor.store_name',
            'Created At' => 'created_at',
        ]);
    }
}
