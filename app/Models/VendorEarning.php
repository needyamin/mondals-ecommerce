<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorEarning extends Model
{
    protected $guarded = ['id'];

    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function order(): BelongsTo  { return $this->belongsTo(Order::class); }
    public function orderItem(): BelongsTo{ return $this->belongsTo(OrderItem::class); }
    public function payout(): BelongsTo { return $this->belongsTo(VendorPayout::class, 'vendor_payout_id'); }

    public function scopeUnpaid($query) { return $query->where('is_paid', false); }
}
