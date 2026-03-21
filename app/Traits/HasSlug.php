<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $source = $model->slugSource ?? 'name';
                $model->slug = Str::slug($model->{$source});
            }
        });
    }

    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
