<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};

class Category extends Model
{
    use SoftDeletes, HasSlug, HasStatus, Filterable, \App\Traits\HasFallbackImage;

    /**
     * Get the display image URL with fallback.
     */
    public function getDisplayImageAttribute(): string
    {
        return $this->getFallbackImage($this->image, $this->name, '400x400');
    }


    protected $guarded  = ['id'];
    protected $searchable = ['name'];

    // ── Relationships ──
    public function parent(): BelongsTo            { return $this->belongsTo(self::class, 'parent_id'); }
    public function children(): HasMany            { return $this->hasMany(self::class, 'parent_id'); }
    public function products(): BelongsToMany      { return $this->belongsToMany(Product::class); }

    // ── Scopes ──
    public function scopeRoot($q)      { return $q->whereNull('parent_id'); }
    public function scopeWithDepth($q) { return $q->with('children.children'); }

    // ── Helpers ──
    public function isRoot(): bool     { return is_null($this->parent_id); }
    public function hasChildren(): bool { return $this->children()->exists(); }

    public function ancestors(): array
    {
        $ancestors = [];
        $current = $this->parent;
        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }
        return $ancestors;
    }
}
