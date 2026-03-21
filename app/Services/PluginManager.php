<?php

namespace App\Services;

use App\Models\Plugin;
use App\Models\PluginHook;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PluginManager
{
    protected $pluginsPath;
    protected $runtimeHooks = [];

    public function __construct()
    {
        $this->pluginsPath = base_path('plugins');
    }

    /**
     * Discover plugins in the plugins directory and register them in the DB
     */
    public function discover(): void
    {
        if (!File::exists($this->pluginsPath)) {
            File::makeDirectory($this->pluginsPath);
        }

        $directories = File::directories($this->pluginsPath);

        // Get currently installed plugins
        $installedSlugs = Plugin::pluck('id', 'slug')->toArray();
        $discoveredSlugs = [];

        foreach ($directories as $dir) {
            $jsonFile = $dir . '/plugin.json';
            
            if (File::exists($jsonFile)) {
                $info = json_decode(File::get($jsonFile), true);
                if (!$info || !isset($info['slug'])) continue;

                $discoveredSlugs[] = $info['slug'];
                $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $dir);
                $path = str_replace('\\', '/', $path); // Normalize for Windows

                $plugin = Plugin::where('slug', $info['slug'])->first();
                $data = [
                    'name' => $info['name'],
                    'version' => $info['version'] ?? '1.0.0',
                    'description' => $info['description'] ?? null,
                    'author' => $info['author'] ?? null,
                    'author_url' => $info['author_url'] ?? null,
                    'path' => $path,
                    'dependencies' => $info['dependencies'] ?? null,
                    'provider' => $info['provider'] ?? null,
                    'type' => $info['type'] ?? null,
                ];

                if ($plugin) {
                    $plugin->update($data);
                } else {
                    $data['slug'] = $info['slug'];
                    $data['settings'] = $info['default_settings'] ?? null;
                    $plugin = Plugin::create($data);
                }

                // Auto-sync database hooks if presence in plugin.json
                if (isset($info['hooks']) && is_array($info['hooks'])) {
                    foreach ($info['hooks'] as $hookData) {
                        \App\Models\PluginHook::updateOrCreate(
                            [
                                'plugin_id' => $plugin->id,
                                'hook_name' => $hookData['event'] ?? $hookData['name'],
                                'handler_class' => $hookData['handler']
                            ],
                            [
                                'handler_method' => $hookData['method'] ?? 'handle',
                                'priority' => $hookData['priority'] ?? 10,
                                'is_active' => true
                            ]
                        );
                    }
                }
            }
        }

        // Optional: mark plugins as 'error' or 'deleted' if they are missing from disk
        Plugin::whereNotIn('slug', $discoveredSlugs)->update(['status' => 'error']);
    }

    /**
     * Activate a plugin
     */
    public function activate(Plugin $plugin): bool
    {
        if ($plugin->status === 'active') {
            return true;
        }

        // Check if plugin ServiceProvider exists
        $providerClass = $this->getPluginProviderClass($plugin);
        if ($providerClass && class_exists($providerClass)) {
            // Run activation hooks if needed
            if (method_exists($providerClass, 'activate')) {
                (new $providerClass(app()))->activate();
            }
        }

        $plugin->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        return true;
    }

    /**
     * Deactivate a plugin
     */
    public function deactivate(Plugin $plugin): bool
    {
        if ($plugin->status === 'inactive') {
            return true;
        }

        $providerClass = $this->getPluginProviderClass($plugin);
        if ($providerClass && class_exists($providerClass)) {
            if (method_exists($providerClass, 'deactivate')) {
                (new $providerClass(app()))->deactivate();
            }
        }

        $plugin->update([
            'status' => 'inactive',
            'activated_at' => null,
        ]);

        return true;
    }

    /**
     * Delete a plugin entirely
     */
    public function uninstall(Plugin $plugin): bool
    {
        $this->deactivate($plugin);

        $providerClass = $this->getPluginProviderClass($plugin);
        if ($providerClass && class_exists($providerClass)) {
            if (method_exists($providerClass, 'uninstall')) {
                (new $providerClass(app()))->uninstall();
            }
        }

        $plugin->delete();

        // Note: we do not delete the files from disk to prevent accidental data loss. 
        // The user can manually delete them or we can add a flag to do so.

        return true;
    }

    /**
     * Get the Service Provider class for a plugin
     */
    public function getPluginProviderClass(Plugin $plugin): ?string
    {
        if ($plugin->provider) {
            return $plugin->provider;
        }

        $namespace = "Plugins\\" . Str::studly($plugin->slug) . "\\";
        return $namespace . "PluginServiceProvider";
    }

    /**
     * Register a runtime hook (not persisted in DB)
     */
    public function on(string $hookName, callable $callback, int $priority = 10): void
    {
        $this->runtimeHooks[$hookName][] = [
            'callback' => $callback,
            'priority' => $priority
        ];
    }

    /**
     * Trigger a hook and execute all listening plugin handlers
     */
    public function triggerHook(string $hookName, mixed ...$args): mixed
    {
        // For filter-style hooks, we can pass the data through each listener
        $value = count($args) > 0 ? $args[0] : null;

        // 1. Collect DB-based hooks
        $dbHooks = \App\Models\PluginHook::where('hook_name', $hookName)
            ->where('is_active', true)
            ->whereHas('plugin', function($q) {
                $q->where('status', 'active');
            })
            ->get()
            ->map(fn($h) => [
                'type' => 'db',
                'priority' => $h->priority,
                'handler' => [$h->handler_class, $h->handler_method]
            ])
            ->toArray();

        // 2. Collect Runtime-based hooks
        $runHooks = collect($this->runtimeHooks[$hookName] ?? [])
            ->map(fn($h) => [
                'type' => 'runtime',
                'priority' => $h['priority'],
                'handler' => $h['callback']
            ])
            ->toArray();

        // 3. Merge and Sort
        $allHooks = collect([...$dbHooks, ...$runHooks])->sortBy('priority');

        foreach ($allHooks as $hook) {
            try {
                if ($hook['type'] === 'db') {
                    $class = $hook['handler'][0];
                    $method = $hook['handler'][1];
                    
                    if (class_exists($class)) {
                        $instance = new $class;
                        if (method_exists($instance, $method)) {
                            // Automatically match the first parameter's name to support custom handler signatures
                            $reflection = new \ReflectionMethod($instance, $method);
                            $parameters = $reflection->getParameters();
                            $callArgs = [];
                            
                            if (count($parameters) > 0) {
                                $firstParam = $parameters[0]->getName();
                                // Inject standard hook context. If the plugin's parameter expects an object,
                                // the container may try to resolve it. In this ecosystem, hooks expect array payload.
                                $callArgs[$firstParam] = ['value' => $value, 'args' => $args];
                            }
                            
                            $result = app()->call([$instance, $method], $callArgs);
                            if ($result !== null) $value = $result;
                        }
                    }
                } else {
                    $result = call_user_func($hook['handler'], $value, ...$args);
                    if ($result !== null) $value = $result;
                }
            } catch (\Exception $e) {
                Log::error("Plugin Hook Error ({$hookName}): " . $e->getMessage());
            }
        }

        return $value;
    }

    /**
     * Delete a plugin completely from Disk
     */
    public function delete(Plugin $plugin): bool
    {
        $path = base_path($plugin->path);
        
        if (File::isDirectory($path)) {
            // Safety check: ensure it starts with standard plugins path
            if (str_contains($path, $this->pluginsPath)) {
                File::deleteDirectory($path);
            }
        }
        
        // Clean up DB
        $plugin->delete();
        
        return true;
    }
}
