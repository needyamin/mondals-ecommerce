<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use SoftDeletes, HasSlug, HasStatus, Filterable, \App\Traits\HasFallbackImage;

    /**
     * Get the display image URL with fallback.
     */
    public function getDisplayImageAttribute(): string
    {
        return $this->getFallbackImage($this->logo, $this->name, '300x150');
    }


    protected $guarded  = ['id'];
    protected $searchable = ['name'];

    public function products(): HasMany { return $this->hasMany(Product::class); }
}
