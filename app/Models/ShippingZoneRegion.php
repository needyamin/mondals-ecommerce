<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingZoneRegion extends Model
{
    protected $guarded = ['id'];

    public function zone(): BelongsTo { return $this->belongsTo(ShippingZone::class, 'shipping_zone_id'); }
}
