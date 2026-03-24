<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use Filterable;

    protected $guarded = ['id'];
    protected $casts   = [
        'settings'     => 'array',
        'dependencies' => 'array',
        'installed_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    // ── Relationships ──
    public function hooks() { return $this->hasMany(PluginHook::class); }

    public static function isActiveSlug(string $slug): bool
    {
        try {
            return static::query()->where('slug', $slug)->where('status', 'active')->exists();
        } catch (\Throwable) {
            return false;
        }
    }

    // ── Scopes ──
    public function scopeActive($query)  { return $query->where('status', 'active'); }
    public function scopeEnabled($query) { return $query->where('is_enabled', true); }

    // ── Helpers ──
    public function activate(): static
    {
        return tap($this)->update(['status' => 'active', 'activated_at' => now(), 'is_enabled' => true]);
    }

    public function deactivate(): static
    {
        return tap($this)->update(['status' => 'inactive', 'is_enabled' => false]);
    }
}
