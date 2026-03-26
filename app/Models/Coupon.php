<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use SoftDeletes, Filterable;

    protected $guarded = ['id'];
    protected $casts   = ['starts_at' => 'datetime', 'expires_at' => 'datetime'];

    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }

    public function scopeValid($q)
    {
        return $q->where('is_active', true)
            ->where(fn($q2) => $q2->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q2) => $q2->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->where(fn($q2) => $q2->whereNull('usage_limit')->orWhereColumn('times_used', '<', 'usage_limit'));
    }

    public function isValid(): bool
    {
        return $this->is_active
            && (!$this->starts_at || $this->starts_at->lte(now()))
            && (!$this->expires_at || $this->expires_at->gte(now()))
            && (!$this->usage_limit || $this->times_used < $this->usage_limit);
    }

    public function calculateDiscount(float $subtotal): float
    {
        return calculate_coupon_discount(
            $subtotal,
            (string) $this->type,
            (float) $this->value,
            $this->min_order_amount !== null ? (float) $this->min_order_amount : null,
            $this->max_discount_amount !== null ? (float) $this->max_discount_amount : null
        );
    }
}
