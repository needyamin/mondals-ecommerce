<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class VendorPayout extends Model
{
    protected $guarded = ['id'];
    protected $casts   = [
        'paid_at' => 'datetime',
        'is_paid' => 'boolean'
    ];

    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function earnings(): HasMany { return $this->hasMany(VendorEarning::class); }

    protected static function booted()
    {
        static::creating(function ($payout) {
            $payout->payout_number = 'PAY-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        });
    }

    public function scopePending($query) { return $query->where('status', 'pending'); }

    public function process(): static
    {
        return tap($this)->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }
}
