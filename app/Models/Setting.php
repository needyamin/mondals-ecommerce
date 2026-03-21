<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['is_public' => 'boolean'];

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($value) ? $value + 0 : $value, // Casts to int/float appropriately
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'boolean' => $value ? '1' : '0',
            'json'    => is_array($value) ? json_encode($value) : $value,
            default   => $value,
        };
    }

    public static function get(string $key, $default = null, string $group = 'general')
    {
        return \Illuminate\Support\Facades\Cache::rememberForever("setting.{$group}.{$key}", function () use ($group, $key, $default) {
            $setting = static::where('group', $group)->where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text', bool $isPublic = false): void
    {
        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'type' => $type, 'is_public' => $isPublic]
        );
        \Illuminate\Support\Facades\Cache::forget("setting.{$group}.{$key}");
    }
}
