<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['processed_at' => 'datetime'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function process(): static { return tap($this)->update(['status' => 'processed', 'processed_at' => now()]); }
}
