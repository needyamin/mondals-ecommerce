<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\{File, View, Cache};

class ThemeManager
{
    protected string $themesPath;
    protected ?string $activeTheme = null;

    public function __construct()
    {
        $this->themesPath = resource_path('themes');
    }

    /**
     * Get the active theme name.
     */
    public function getActive(): string
    {
        if ($this->activeTheme) return $this->activeTheme;

        $this->activeTheme = Cache::remember('active_theme', 3600, function () {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return 'default';
            }
            return Setting::get('active_theme', 'default', 'theme') ?? 'default';
        });

        return $this->activeTheme;
    }

    /**
     * Set the active theme.
     */
    public function setActive(string $theme): void
    {
        if (!$this->exists($theme)) {
            throw new \InvalidArgumentException("Theme '{$theme}' does not exist.");
        }

        Setting::set('active_theme', $theme, 'theme', 'text', true);
        Cache::forget('active_theme');
        $this->activeTheme = $theme;
    }

    /**
     * Check if a theme directory exists.
     */
    public function exists(string $theme): bool
    {
        return File::isDirectory($this->getThemePath($theme));
    }

    /**
     * Get a list of all installed themes with their config.
     */
    public function getAll(): array
    {
        $themes = [];

        if (!File::isDirectory($this->themesPath)) return $themes;

        foreach (File::directories($this->themesPath) as $dir) {
            $name = basename($dir);
            $configFile = $dir . '/theme.json';

            $config = File::exists($configFile)
                ? json_decode(File::get($configFile), true)
                : ['name' => ucfirst($name)];

            $themes[] = array_merge($config, [
                'slug'      => $name,
                'path'      => $dir,
                'is_active' => $name === $this->getActive(),
                'has_screenshot' => File::exists($dir . '/screenshot.png'),
            ]);
        }

        return $themes;
    }

    /**
     * Get theme-specific config.
     */
    public function getConfig(string $theme): array
    {
        $configFile = $this->getThemePath($theme) . '/theme.json';
        return File::exists($configFile) ? json_decode(File::get($configFile), true) : [];
    }

    /**
     * Get customization values for the active theme.
     */
    public function getCustomization(): array
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            return [];
        }
        $theme = $this->getActive();
        $stored = Setting::get("theme_{$theme}_customization", null, 'theme');
        return is_array($stored) ? $stored : [];
    }

    /**
     * Save customization values for the active theme.
     */
    public function saveCustomization(array $values): void
    {
        $theme = $this->getActive();
        Setting::set("theme_{$theme}_customization", $values, 'theme', 'json', true);
        Cache::forget("theme_{$theme}_customization");
    }

    /**
     * Get the full path to a theme.
     */
    public function getThemePath(string $theme): string
    {
        return $this->themesPath . DIRECTORY_SEPARATOR . $theme;
    }

    /**
     * Get the views path for the active theme.
     */
    public function getViewsPath(?string $theme = null): string
    {
        return $this->getThemePath($theme ?? $this->getActive()) . '/views';
    }

    /**
     * Get the assets path for the active theme.
     */
    public function getAssetsPath(?string $theme = null): string
    {
        return $this->getThemePath($theme ?? $this->getActive()) . '/assets';
    }

    /**
     * Generate an asset URL for the active theme.
     */
    public function asset(string $path): string
    {
        $theme = $this->getActive();
        return asset("themes/{$theme}/{$path}");
    }
}
