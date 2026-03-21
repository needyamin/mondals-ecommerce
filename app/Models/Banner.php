<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Banner extends Model
{
    use SoftDeletes, HasStatus;

    protected $guarded = ['id'];
    protected $casts   = ['starts_at' => 'datetime', 'expires_at' => 'datetime'];

    public function scopeInPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->orderBy('sort_order', 'asc');
    }
}
