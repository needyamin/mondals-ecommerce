<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasFallbackImage
{
    /**
     * Get image URL with a random colorful fallback.
     *
     * @param string|null $path The relative storage path (e.g. 'products/img.jpg')
     * @param string|null $label The text to show on placeholder (falls back to Name/Id)
     * @param string $size e.g. 400x400
     * @return string
     */
    public function getFallbackImage(?string $path, ?string $label = null, string $size = '400x400', string $type = 'image'): string
    {
        // 1. If we have a path, check if it exists in storage
        if (!empty($path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return asset($path);
        }

        // 2. Generate fallback based on type
        $seed = $this->id ?? ($this->name ?? ($this->store_name ?? ($this->title ?? 'default')));
        $label = $label ?? ($this->name ?? ($this->store_name ?? ($this->title ?? 'Item')));
        
        if ($type === 'avatar') {
            // Profile pictures (using UI Avatars for a professional look)
            $sizeVal = explode('x', $size)[0];
            return "https://ui-avatars.com/api/?name=" . urlencode($label) . "&background=random&color=fff&size=" . $sizeVal;
        }

        // Products / Brands / Categories (using Placehold.co with deterministic colors)
        $colors = ['2563eb', 'dc2626', '16a34a', 'ca8a04', '9333ea', '0891b2', 'db2777', '4f46e5', 'ea580c', '65a30d'];
        $bgColor = $colors[abs(crc32((string)$seed)) % count($colors)];
        $text = urlencode(strtoupper(substr($label, 0, 15)));
        
        return "https://placehold.co/{$size}/{$bgColor}/FFFFFF/png?text={$text}";
    }

}
