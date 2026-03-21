<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use SoftDeletes, Filterable;

    protected $guarded = ['id'];
    protected $casts   = ['approved_at' => 'datetime'];

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function order(): BelongsTo   { return $this->belongsTo(Order::class); }

    public function scopeApproved($query) { return $query->where('status', 'approved'); }
    public function approve(): static     { return tap($this)->update(['status' => 'approved', 'approved_at' => now()]); }
    public function reject(): static      { return tap($this)->update(['status' => 'rejected']); }
}
