<?php

if (!function_exists('themeValue')) {
    /**
     * Get a customization value for the active theme.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function themeValue(string $key, $default = null)
    {
        $themeManager = app(\App\Services\ThemeManager::class);
        $customization = $themeManager->getCustomization();
        
        return $customization[$key] ?? $default;
    }
}
if (!function_exists('getImageUrl')) {
    /**
     * Get the URL for an image, or a "No Image" placeholder if it's missing.
     *
     * @param string|null $path
     * @param string $placeholder
     * @return string
     */
    function getImageUrl($path, string $placeholder = 'https://via.placeholder.com/600x600?text=No+Image+Found')
    {
        if (empty($path)) {
            return $placeholder;
        }

        // Return placeholder if path is not a string (e.g. array/object)
        if (!is_string($path)) {
            return $placeholder;
        }

        // Check if it's already a full URL
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Check if the file exists on the public disk
        try {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        } catch (\Exception $e) {
            // Log error if needed, but return placeholder for safety
        }

        return $placeholder;
    }
}
