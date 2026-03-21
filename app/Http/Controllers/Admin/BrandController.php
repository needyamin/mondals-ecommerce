<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends BaseCrudController
{
    protected string $model = Brand::class;
    protected string $viewPrefix = 'admin.brands';
    protected string $routePrefix = 'admin.brands';
    protected array $searchable = ['name'];

    protected function validationRules(?Model $item = null): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|string|max:255',
            'website'     => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected function beforeSave(array $data, Request $request, ?Model $item = null): array
    {
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        return $data;
    }
}
