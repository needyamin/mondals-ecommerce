<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Page extends Model
{
    use SoftDeletes, HasSlug, HasStatus, Filterable;

    protected $guarded  = ['id'];
    protected $searchable = ['title', 'content'];

    public function scopeOrdered($query) { return $query->orderBy('sort_order', 'asc'); }
}
