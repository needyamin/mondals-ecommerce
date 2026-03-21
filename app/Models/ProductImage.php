<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $guarded = ['id'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }

    public function scopePrimary($q) { return $q->where('is_primary', true); }
}
