<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use \App\Traits\HasFallbackImage;

    protected $guarded = ['id'];

    /**
     * Get the display image URL with fallback.
     */
    public function getDisplayUrlAttribute(): string
    {
        return $this->getFallbackImage($this->image, 'Product Image', '600x600', 'image', 'products');
    }


    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    public function scopePrimary($q) { return $q->where('is_primary', true); }
}
