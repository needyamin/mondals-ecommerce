<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantValue extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function variant(): BelongsTo       { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function attribute(): BelongsTo     { return $this->belongsTo(Attribute::class); }
    public function attributeValue(): BelongsTo { return $this->belongsTo(AttributeValue::class); }
}
