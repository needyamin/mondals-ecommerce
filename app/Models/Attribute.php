<?php

namespace App\Models;

use App\Traits\{HasSlug, HasStatus};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasSlug, HasStatus;

    protected $guarded = ['id'];

    public function values(): HasMany { return $this->hasMany(AttributeValue::class); }
}
