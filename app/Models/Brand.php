<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use SoftDeletes, HasSlug, HasStatus, Filterable;

    protected $guarded  = ['id'];
    protected $searchable = ['name'];

    public function products(): HasMany { return $this->hasMany(Product::class); }
}
