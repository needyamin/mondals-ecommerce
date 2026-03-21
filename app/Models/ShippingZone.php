<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $guarded = ['id'];

    public function regions(): HasMany { return $this->hasMany(ShippingZoneRegion::class); }
    public function methods(): HasMany { return $this->hasMany(ShippingMethod::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }
}
