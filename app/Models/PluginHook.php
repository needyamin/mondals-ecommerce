<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginHook extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['priority' => 'integer'];

    public function plugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class);
    }
}
