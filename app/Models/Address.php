<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->address_line_1, $this->address_line_2, $this->city, $this->state, $this->zip_code, $this->country])
            ->filter()->implode(', ');
    }

    public function getFullNameAttribute(): string { return "{$this->first_name} {$this->last_name}"; }
}
