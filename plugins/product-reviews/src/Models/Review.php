<?php

namespace Plugins\ProductReviews\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use Filterable, SoftDeletes;

    protected $table = 'reviews';

    protected $guarded = ['id'];

    protected $casts = ['approved_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function approve(): static
    {
        return tap($this)->update(['status' => 'approved', 'approved_at' => now()]);
    }

    public function reject(): static
    {
        return tap($this)->update(['status' => 'rejected', 'approved_at' => null]);
    }
}
