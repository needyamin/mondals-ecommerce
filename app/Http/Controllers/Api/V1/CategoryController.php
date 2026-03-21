<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get full nested category tree.
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getTree();
        return $this->success($categories, 'Categories tree retrieved');
    }

    /**
     * Get featured categories.
     */
    public function featured(Request $request): JsonResponse
    {
        $categories = $this->categoryService->getFeatured($request->input('limit', 6));
        return $this->success($categories, 'Featured categories retrieved');
    }
}
