<?php

namespace App\Providers;

use App\Models\Plugin;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('plugin.manager', \App\Services\PluginManager::class);

        // Register dynamic autoloader for the Plugins namespace
        spl_autoload_register(function ($class) {
            if (str_starts_with($class, 'Plugins\\')) {
                $relativeClass = substr($class, 8);
                $parts = explode('\\', $relativeClass);
                $studlySlug = array_shift($parts);
                $slug = \Illuminate\Support\Str::kebab($studlySlug); // e.g., BkashPayment -> bkash-payment
                
                $remainingPath = str_replace('\\', DIRECTORY_SEPARATOR, implode('\\', $parts)) . '.php';

                $pathSrc = base_path("plugins/{$slug}/src/{$remainingPath}");
                $pathRoot = base_path("plugins/{$slug}/{$remainingPath}");

                if (file_exists($pathSrc)) {
                    require_once $pathSrc;
                } elseif (file_exists($pathRoot)) {
                    require_once $pathRoot;
                }
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Custom blade directive to trigger plugin hooks from views
        // Usage: @pluginHook('after_sidebar', $data)
        Blade::directive('pluginHook', function ($expression) {
            return "<?php echo app('plugin.manager')->triggerHook({$expression}); ?>";
        });

        // Do not query DB if we are running standard console commands where DB might not be ready
        try {
            if (!Schema::hasTable('plugins')) {
                return;
            }

            $activePlugins = Plugin::active()->get();

            foreach ($activePlugins as $plugin) {
                $providerClass = app('plugin.manager')->getPluginProviderClass($plugin);
                
                if (class_exists($providerClass)) {
                    $this->app->register($providerClass);
                    // Since we are already in boot, we might need to manually boot the registered provider
                    // Laravel handles boot if registered during boot, but just in case:
                }
            }
        } catch (\Exception $e) {
            Log::debug('PluginServiceProvider boot ignored due to DB: ' . $e->getMessage());
        }
    }
}
