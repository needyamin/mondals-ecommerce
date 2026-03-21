<?php

namespace App\Providers;

use App\Services\ThemeManager;
use Illuminate\Support\Facades\{Blade, View, File};
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register the ThemeManager as a singleton.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeManager::class, function () {
            return new ThemeManager();
        });
    }

    /**
     * Boot: register theme views, share data with all views.
     */
    public function boot(): void
    {
        /** @var ThemeManager $themeManager */
        $themeManager = $this->app->make(ThemeManager::class);
        $activeTheme = $themeManager->getActive();
        $viewsPath = $themeManager->getViewsPath();

        // 1. Prepend theme views path so it takes priority over default views
        if (File::isDirectory($viewsPath)) {
            View::prependNamespace('theme', $viewsPath);
            // Also make it the first fallback for non-namespaced views
            View::addLocation($viewsPath);
        }

        // 2. Share theme data globally with all views
        View::share('currentTheme', $activeTheme);
        View::share('themeCustomization', $themeManager->getCustomization());

        // 3. Register a Blade directive for theme assets
        Blade::directive('themeAsset', function ($expression) {
            return "<?php echo app(\App\Services\ThemeManager::class)->asset({$expression}); ?>";
        });

        // 4. Symlink theme assets to public/themes/{name} if not exists
        $publicThemePath = public_path("themes/{$activeTheme}");
        $themeAssetsPath = $themeManager->getAssetsPath();

        if (File::isDirectory($themeAssetsPath) && !File::isDirectory($publicThemePath)) {
            // Create parent dir if needed
            File::ensureDirectoryExists(public_path('themes'));
            // On Windows, copy instead of symlink to avoid permission issues
            if (PHP_OS_FAMILY === 'Windows') {
                File::copyDirectory($themeAssetsPath, $publicThemePath);
            } else {
                File::link($themeAssetsPath, $publicThemePath);
            }
        }
    }
}
