<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['options' => 'array'];

    public function cart(): BelongsTo           { return $this->belongsTo(Cart::class); }
    public function product(): BelongsTo        { return $this->belongsTo(Product::class); }
    public function productVariant(): BelongsTo { return $this->belongsTo(ProductVariant::class); }

    public function getSubtotalAttribute(): float
    {
        return line_total((float) $this->price, (int) $this->quantity);
    }
}
