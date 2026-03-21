<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseCrudController
{
    protected string $model = Category::class;
    protected string $viewPrefix = 'admin.categories';
    protected string $routePrefix = 'admin.categories';
    protected array $with = ['parent', 'children'];
    protected array $searchable = ['name', 'slug'];

    protected function validationRules(?Model $item = null): array
    {
        $unique = $item ? ",{$item->id}" : '';
        return [
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'icon'        => 'nullable|string|max:100',
        ];
    }

    protected function formData(?Model $item = null): array
    {
        $parentQuery = Category::active()->root();
        if ($item) $parentQuery->where('id', '!=', $item->id);
        return ['parents' => $parentQuery->pluck('name', 'id')->prepend('— None (Root) —', '')];
    }

    protected function beforeSave(array $data, Request $request, ?Model $item = null): array
    {
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        return $data;
    }
}
