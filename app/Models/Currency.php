<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasStatus;

    protected $guarded = ['id'];
    protected $casts   = ['is_default' => 'boolean'];

    public function format(float $amount): string
    {
        // Convert base amount using exchange rate
        $converted = $amount * $this->exchange_rate;

        // Format number
        $formatted = number_format($converted, $this->decimal_places, $this->decimal_separator, $this->thousand_separator);

        // Position symbol
        return $this->position === 'before'
            ? "{$this->symbol}{$formatted}"
            : "{$formatted} {$this->symbol}";
    }

    public static function defaultCurrency(): self
    {
        return static::where('is_default', true)->first() ?? static::first();
    }
}
