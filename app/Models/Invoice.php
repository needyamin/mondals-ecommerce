<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['issued_at' => 'datetime', 'due_at' => 'datetime'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
