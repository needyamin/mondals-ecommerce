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
