<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable, Auditable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use SoftDeletes, HasSlug, Filterable, Auditable, \App\Traits\HasFallbackImage;

    /**
     * Logo for listings (full URL, storage path, or placeholder).
     */
    public function getDisplayImageAttribute(): string
    {
        $path = $this->logo;
        if (filled($path)) {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            try {
                if (Storage::disk('public')->exists($path)) {
                    return Storage::disk('public')->url($path);
                }
            } catch (\Throwable) {
            }
        }

        return $this->getFallbackImage($path, $this->store_name, '300x300');
    }

    /** Cover/banner URL for admin list, or null. */
    public function getDisplayBannerAttribute(): ?string
    {
        $path = $this->banner;
        if (!filled($path)) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        } catch (\Throwable) {
        }

        return null;
    }


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
