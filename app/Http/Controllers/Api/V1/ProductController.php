<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get paginated product catalog.
     * Supports ?search=, ?category_id=, ?brand_id=, ?price_min=, ?price_max=, ?sort=
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getCatalog($request->all(), $request->input('per_page', 15));
        return $this->paginated($products, 'Products retrieved successfully');
    }

    /**
     * Get featured products.
     */
    public function featured(Request $request): JsonResponse
    {
        $products = $this->productService->getFeatured($request->input('limit', 8));
        return $this->success($products, 'Featured products retrieved');
    }

    /**
     * Get product details by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $product = $this->productService->getBySlug($slug);
        return $this->success($product, 'Product details retrieved');
    }
}
