<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus, Filterable, Auditable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use SoftDeletes, HasSlug, Filterable, Auditable, \App\Traits\HasFallbackImage;

    /** Normalize DB path to a key relative to storage/app/public (disk `public`). */
    private function normalizedPublicPath(?string $path): string
    {
        if (! filled($path)) {
            return '';
        }
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        return $path;
    }

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
            $rel = $this->normalizedPublicPath($path);
            if ($rel !== '') {
                try {
                    return Storage::disk('public')->url($rel);
                } catch (\Throwable) {
                }
            }
        }

        return $this->getFallbackImage($path, $this->store_name, '300x300');
    }

    /** Cover/banner URL for listings, or null. */
    public function getDisplayBannerAttribute(): ?string
    {
        $path = $this->banner;
        if (! filled($path)) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        $rel = $this->normalizedPublicPath($path);
        if ($rel === '') {
            return null;
        }
        try {
            return Storage::disk('public')->url($rel);
        } catch (\Throwable) {
            return null;
        }
    }

    /** Address + city/region line for storefront (non-empty lines only). */
    public function getAddressLinesAttribute(): array
    {
        $locLine = collect([$this->city, $this->state, $this->zip_code, $this->country])
            ->filter(fn ($v) => filled($v))
            ->implode(', ');

        return array_values(array_filter([
            $this->address,
            $locLine !== '' ? $locLine : null,
        ], fn ($v) => filled($v)));
    }

    protected $guarded = ['id'];
    protected $casts   = ['settings' => 'array', 'approved_at' => 'datetime'];
    protected $searchable = ['store_name', 'email', 'city', 'description'];

    public function filterCountry($query, string $value): void
    {
        $query->where('country', $value);
    }

    public function filterState($query, string $value): void
    {
        $query->where('state', $value);
    }

    public function filterCity($query, string $value): void
    {
        $like = '%'.addcslashes($value, '%_\\').'%';
        $query->where('city', 'LIKE', $like);
    }

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
