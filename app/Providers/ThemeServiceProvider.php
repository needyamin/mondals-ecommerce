<?php

namespace App\Providers;

use App\Services\ThemeManager;
use Illuminate\Support\Facades\{Blade, View, File};
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeManager::class, function () {
            return new ThemeManager();
        });
    }

    public function boot(): void
    {
        /** @var ThemeManager $themeManager */
        $themeManager = $this->app->make(ThemeManager::class);
        $activeTheme = $themeManager->getActive();
        $viewsPath = $themeManager->getViewsPath();

        if (File::isDirectory($viewsPath)) {
            // Keep namespace for explicitly theme:: rendering
            View::prependNamespace('theme', $viewsPath);
            
            // Apply to the ACTIVE View Factory's internal finder
            View::prependLocation($viewsPath);
            View::flushFinderCache();
        }

        View::share('currentTheme', $activeTheme);
        View::share('themeCustomization', $themeManager->getCustomization());

        Blade::directive('themeAsset', function ($expression) {
            return "<?php echo app(\App\Services\ThemeManager::class)->asset({$expression}); ?>";
        });

        $publicThemePath = public_path("themes/{$activeTheme}");
        $themeAssetsPath = $themeManager->getAssetsPath();

        if (File::isDirectory($themeAssetsPath) && !File::isDirectory($publicThemePath)) {
            File::ensureDirectoryExists(public_path('themes'));
            if (PHP_OS_FAMILY === 'Windows') {
                File::copyDirectory($themeAssetsPath, $publicThemePath);
            } else {
                File::link($themeAssetsPath, $publicThemePath);
            }
        }
    }
}
