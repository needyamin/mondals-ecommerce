<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable, Auditable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Vendor extends Model
{
    use SoftDeletes, HasSlug, Filterable, Auditable;

    protected $guarded = ['id'];
    protected $casts   = ['settings' => 'array', 'approved_at' => 'datetime'];
    protected $searchable = ['store_name', 'email', 'city'];

    // ── Relationships ──
    public function user(): BelongsTo     { return $this->belongsTo(User::class); }
    public function products(): HasMany   { return $this->hasMany(Product::class); }
    public function orders(): HasMany     { return $this->hasMany(OrderItem::class); }
    public function payouts(): HasMany    { return $this->hasMany(VendorPayout::class); }
    public function earnings(): HasMany   { return $this->hasMany(VendorEarning::class); }
    public function coupons(): HasMany    { return $this->hasMany(Coupon::class); }

    // ── Scopes ──
    public function scopeApproved($q)  { return $q->where('status', 'approved'); }
    public function scopePending($q)   { return $q->where('status', 'pending'); }
    public function scopeSuspended($q) { return $q->where('status', 'suspended'); }

    // ── Helpers ──
    public function approve(): static   { return tap($this)->update(['status' => 'approved', 'approved_at' => now()]); }
    public function reject($r = null): static { return tap($this)->update(['status' => 'rejected', 'rejection_reason' => $r]); }
    public function suspend(): static   { return tap($this)->update(['status' => 'suspended']); }
    public function unsuspend(): static { return tap($this)->update(['status' => 'approved']); }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function totalEarnings(): float { return $this->earnings()->sum('vendor_earning'); }
    public function unpaidEarnings(): float { return $this->earnings()->where('is_paid', false)->sum('vendor_earning'); }
}
