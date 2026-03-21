<?php

namespace App\Traits;

trait HasStatus
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function activate(): static
    {
        return tap($this)->update(['is_active' => true]);
    }

    public function deactivate(): static
    {
        return tap($this)->update(['is_active' => false]);
    }
}
