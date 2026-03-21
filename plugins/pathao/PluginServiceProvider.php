<?php

namespace Plugins\Pathao;

use Illuminate\Support\ServiceProvider;
use App\Models\Plugin;
use App\Models\PluginHook;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Called when the plugin is activated by admin.
     */
    public function activate(): void
    {
        $plugin = Plugin::where('slug', 'pathao')->first();
        if (!$plugin) return;

        PluginHook::updateOrCreate(
            ['plugin_id' => $plugin->id, 'hook_name' => 'register_shipping_methods'],
            [
                'handler_class' => \Plugins\Pathao\Handlers\ShippingMethodHandler::class,
                'handler_method' => 'register',
                'priority' => 10,
                'is_active' => true
            ]
        );
    }

    /**
     * Called when the plugin is deactivated by admin.
     */
    public function deactivate(): void
    {
        $plugin = Plugin::where('slug', 'pathao')->first();
        if (!$plugin) return;

        PluginHook::where('plugin_id', $plugin->id)->update(['is_active' => false]);
    }

    /**
     * Called when the plugin is uninstalled by admin.
     */
    public function uninstall(): void
    {
        $plugin = Plugin::where('slug', 'pathao')->first();
        if (!$plugin) return;

        PluginHook::where('plugin_id', $plugin->id)->delete();
    }

    public function boot(): void
    {
        // Boot functionality
    }

    public function register(): void
    {
        // Register functionality
    }
}
