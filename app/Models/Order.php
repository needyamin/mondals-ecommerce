<?php

namespace App\Models;

use App\Traits\{Filterable, Auditable};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes, Filterable, Auditable;

    protected $guarded  = ['id'];
    protected $searchable = ['order_number'];
    protected $casts = [
        'paid_at' => 'datetime', 'shipped_at' => 'datetime', 'delivered_at' => 'datetime',
        'completed_at' => 'datetime', 'cancelled_at' => 'datetime',
    ];

    // ── Boot ──
    protected static function booted(): void
    {
        static::creating(fn($o) => $o->order_number = $o->order_number ?: 'ORD-' . strtoupper(Str::random(8)));
    }

    // ── Relationships ──
    public function user(): BelongsTo        { return $this->belongsTo(User::class); }
    public function coupon(): BelongsTo      { return $this->belongsTo(Coupon::class); }
    public function items(): HasMany         { return $this->hasMany(OrderItem::class); }
    public function statusHistory(): HasMany { return $this->hasMany(OrderStatusHistory::class); }
    public function refunds(): HasMany       { return $this->hasMany(Refund::class); }
    public function invoices(): HasMany      { return $this->hasMany(Invoice::class); }

    // ── Scopes ──
    public function scopeByStatus($q, $s)     { return $q->where('status', $s); }
    public function scopeByPayment($q, $s)    { return $q->where('payment_status', $s); }
    public function scopeForUser($q, $id)     { return $q->where('user_id', $id); }
    public function scopeRecent($q, $days = 30) { return $q->where('created_at', '>=', now()->subDays($days)); }

    // ── Helpers ──
    public function updateStatus(string $status, ?string $comment = null): static
    {
        $old = $this->status;
        $this->update(['status' => $status, "{$status}_at" => now()]);
        $this->statusHistory()->create(['old_status' => $old, 'new_status' => $status, 'comment' => $comment, 'user_id' => auth()->id()]);
        return $this;
    }

    public function isPaid(): bool       { return $this->payment_status === 'paid'; }
    public function isCancellable(): bool { return in_array($this->status, ['pending', 'confirmed']); }
    public function isRefundable(): bool  { return in_array($this->status, ['delivered', 'completed']); }
}
