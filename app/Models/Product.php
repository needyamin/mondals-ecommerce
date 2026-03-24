<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable, Auditable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};

class Product extends Model
{
    use SoftDeletes, HasSlug, HasStatus, Filterable, Auditable, \App\Traits\HasFallbackImage;

    /**
     * Get the display image URL with fallback.
     * Use this in Blade: {{ $product->display_image }}
     */
    public function getDisplayImageAttribute(): string
    {
        $path = $this->getPrimaryImageAttribute();
        return $this->getFallbackImage($path, $this->name, '400x400', 'image', 'products');
    }


    protected $guarded  = ['id'];
    protected $searchable = ['name', 'short_description', 'sku'];
    protected $casts = ['published_at' => 'datetime'];

    // ── Relationships ──
    public function vendor(): BelongsTo             { return $this->belongsTo(Vendor::class); }
    public function brand(): BelongsTo              { return $this->belongsTo(Brand::class); }
    public function category(): BelongsTo           { return $this->belongsTo(Category::class); }
    public function categories(): BelongsToMany     { return $this->belongsToMany(Category::class); }
    public function attributes(): BelongsToMany     { return $this->belongsToMany(Attribute::class); }
    public function images(): HasMany               { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function variants(): HasMany             { return $this->hasMany(ProductVariant::class); }
    public function reviews(): HasMany              { return $this->hasMany(Review::class); }
    public function wishlists(): HasMany            { return $this->hasMany(Wishlist::class); }

    // ── Scopes ──
    public function scopePublished($q)    { return $q->active()->where('status', 'approved')->whereNotNull('published_at'); }
    public function scopeByVendor($q, $id) { return $q->where('vendor_id', $id); }
    public function scopeByBrand($q, $id)  { return $q->where('brand_id', $id); }
    public function scopeInPriceRange($q, $min, $max) { return $q->whereBetween('price', [$min, $max]); }

    // ── Custom Filters ──
    public function filterCategory($q, $v) { return $q->whereHas('categories', fn($q2) => $q2->where('categories.id', $v)); }
    public function filterBrand($q, $v)    { return $q->where('brand_id', $v); }
    public function filterVendor($q, $v)   { return $q->where('vendor_id', $v); }

    // ── Accessors ──
    public function getDiscountPercentAttribute(): ?float
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) return null;
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 1);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->where('status', 'approved')->avg('rating') ?? 0, 1);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        return $this->images()->where('is_primary', true)->value('image') ?? $this->thumbnail;
    }

    // ── Helpers ──
    public function isInStock(): bool     { return !$this->track_quantity || $this->quantity > 0; }
    public function isLowStock(): bool    { return $this->track_quantity && $this->quantity <= $this->low_stock_threshold; }
    public function decrementStock(int $qty): void { if ($this->track_quantity) $this->decrement('quantity', $qty); }
    public function incrementStock(int $qty): void { if ($this->track_quantity) $this->increment('quantity', $qty); }
    public function incrementViews(): void { $this->increment('views_count'); }
}
