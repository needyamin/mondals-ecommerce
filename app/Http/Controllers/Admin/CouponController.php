<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CouponController extends BaseCrudController
{
    protected string $model = Coupon::class;
    protected string $viewPrefix = 'admin.coupons';
    protected string $routePrefix = 'admin.coupons';
    protected array $searchable = ['code', 'name'];
    protected array $with = ['vendor'];

    protected function validationRules(?Model $item = null): array
    {
        $unique = $item ? ",{$item->id}" : '';
        return [
            'code'                 => "required|string|max:50|unique:coupons,code{$unique}",
            'name'                 => 'required|string|max:255',
            'vendor_id'            => 'nullable|exists:vendors,id',
            'type'                 => 'required|in:fixed,percentage,free_shipping',
            'value'                => 'required|numeric|min:0',
            'min_order_amount'     => 'nullable|numeric|min:0',
            'max_discount_amount'  => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'is_active'            => 'boolean',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    protected function formData(?Model $item = null): array
    {
        return [
            'vendors' => Vendor::approved()->pluck('store_name', 'id')->prepend('— Platform Wide —', ''),
        ];
    }

    protected function beforeSave(array $data, Request $request, ?Model $item = null): array
    {
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
