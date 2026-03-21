<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * Get a nested tree of active categories.
     */
    public function getTree(): Collection
    {
        return \Illuminate\Support\Facades\Cache::remember('categories.tree', 3600, function () {
            return Category::withDepth()
                ->active()
                ->root()
                ->sorted('sort_order', 'asc')
                ->get();
        });
    }

    /**
     * Get active featured categories.
     */
    public function getFeatured(int $limit = 6): Collection
    {
        return \Illuminate\Support\Facades\Cache::remember("categories.featured.{$limit}", 3600, function () use ($limit) {
            return Category::active()
                ->featured()
                ->sorted('sort_order', 'asc')
                ->limit($limit)
                ->get();
        });
    }
}
