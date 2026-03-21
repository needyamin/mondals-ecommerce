<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['settings' => 'array'];

    public function zone(): BelongsTo { return $this->belongsTo(ShippingZone::class, 'shipping_zone_id'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }

    public function isApplicable(float $subtotal): bool
    {
        return !$this->min_order_amount || $subtotal >= $this->min_order_amount;
    }
}
