<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PluginController extends Controller
{
    /**
     * Display a listing of plugins.
     */
    public function index()
    {
        // First discover any new plugins on disk
        app('plugin.manager')->discover();

        $plugins = Plugin::orderBy('name')->get();

        return view('admin.plugins.index', compact('plugins'));
    }

    /**
     * Upload and extract a plugin zip file.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:20480',
        ]);

        $zipPath = $request->file('plugin_zip')->path();
        if (!class_exists('\ZipArchive')) {
            return back()->with('error', 'The PHP ZipArchive extension is not enabled on this server.');
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $extractPath = base_path('plugins');
            $zip->extractTo($extractPath);
            $zip->close();
            
            app('plugin.manager')->discover();
            return back()->with('success', 'Plugin successfully uploaded and extracted to ecosystem!');
        }

        return back()->with('error', 'Failed to open the uploaded ZIP file.');
    }

    /**
     * Install a plugin (run migrations/publish assets).
     */
    public function install(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        if ($plugin->installed_at) {
            return back()->with('success', 'Plugin is already installed.');
        }

        $plugin->update(['installed_at' => now()]);
        
        // Try activating it right after install
        app('plugin.manager')->activate($plugin);

        return back()->with('success', 'Plugin installed and activated successfully.');
    }

    /**
     * Enable (Activate) a plugin.
     */
    public function enable(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        try {
            app('plugin.manager')->activate($plugin);
            return back()->with('success', 'Plugin activated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to activate plugin: ' . $e->getMessage());
            return back()->with('error', 'Failed to activate plugin. See logs.');
        }
    }

    /**
     * Disable (Deactivate) a plugin.
     */
    public function disable(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        try {
            app('plugin.manager')->deactivate($plugin);
            return back()->with('success', 'Plugin deactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to deactivate plugin: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate plugin. See logs.');
        }
    }

    /**
     * Uninstall a plugin (remove data, not files).
     */
    public function uninstall(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        try {
            app('plugin.manager')->uninstall($plugin);
            return back()->with('success', 'Plugin uninstalled successfully (files remain on disk).');
        } catch (\Exception $e) {
            Log::error('Failed to uninstall plugin: ' . $e->getMessage());
            return back()->with('error', 'Failed to uninstall plugin. See logs.');
        }
    }

    /**
     * Delete a plugin completely (WIPE from disk).
     */
    public function destroy(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        try {
            app('plugin.manager')->delete($plugin);
            return redirect()->route('admin.plugins.index')->with('success', "Module ‘{$plugin->name}’ has been purged from ecosystem disk.");
        } catch (\Exception $e) {
            Log::error('Failed to delete plugin: ' . $e->getMessage());
            return back()->with('error', 'Failed to safely delete plugin files: ' . $e->getMessage());
        }
    }

    /**
     * Show plugin specific settings page.
     */
    public function settings($slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        $viewName = "plugins::{$slug}.settings";
        
        if (view()->exists($viewName)) {
            return view($viewName, compact('plugin'));
        }

        // Fallback to a generic settings builder if plugin has settings data
        return view('admin.plugins.generic-settings', compact('plugin'));
    }

    /**
     * Save plugin specific settings.
     */
    public function saveSettings(Request $request, $slug)
    {
        $plugin = Plugin::where('slug', $slug)->firstOrFail();
        
        // Handle both flat inputs and nested 'settings' array from generic form
        $newSettings = $request->input('settings', $request->except(['_token']));
        
        // Auto-decode JSON strings for complex settings (like zones)
        if (is_array($newSettings)) {
            foreach ($newSettings as $k => $v) {
                if (is_string($v) && (str_starts_with(trim($v), '{') || str_starts_with(trim($v), '['))) {
                    $decoded = json_decode($v, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $newSettings[$k] = $decoded;
                    }
                }
            }
        }

        $currentSettings = (array)($plugin->settings ?? []);
        $updatedSettings = array_merge($currentSettings, $newSettings);
        
        $plugin->update(['settings' => $updatedSettings]);

        return back()->with('success', "Configuration parameters for ‘{$plugin->name}’ successfully persisted.");
    }
}
