<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * Main product catalog catalog query with robust filtering, sorting and eager loading.
     */
    public function getCatalog(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::published()
            ->with(['brand', 'categories', 'images' => fn($q) => $q->primary()])
            ->filter($filters);

        // Custom specific sorts
        $sort = $filters['sort'] ?? null;
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'popular') {
            $query->orderBy('sales_count', 'desc')->orderBy('views_count', 'desc');
        } else {
            // Default Filterable trait sort (e.g. -created_at for newest)
            $query->sorted($sort, '-created_at');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Fetch a single product by slug with full details (variants, attributes, reviews).
     */
    public function getBySlug(string $slug): Product
    {
        $product = Product::published()
            ->with([
                'vendor', 'brand', 'categories', 'images',
                'attributes.values',
                'variants.variantValues.attribute',
                'variants.variantValues.attributeValue',
                'reviews' => fn($q) => $q->approved()->with('user')->latest()->limit(5)
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->incrementViews();

        return $product;
    }

    /**
     * Get home page featured products.
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Product::published()
            ->featured()
            ->with(['images' => fn($q) => $q->primary()])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
