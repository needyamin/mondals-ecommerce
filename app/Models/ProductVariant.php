<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class ProductVariant extends Model
{
    protected $guarded = ['id'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function variantValues(): HasMany { return $this->hasMany(ProductVariantValue::class); }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    public function isInStock(): bool { return $this->quantity > 0; }
}
