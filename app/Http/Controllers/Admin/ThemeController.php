<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ThemeManager;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * List all themes.
     */
    public function index()
    {
        $themes = $this->themeManager->getAll();
        $activeTheme = $this->themeManager->getActive();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Upload and extract a theme zip file.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480',
        ]);

        $zipPath = $request->file('theme_zip')->path();
        $zip = new \ZipArchive;
        
        if ($zip->open($zipPath) === TRUE) {
            $extractPath = resource_path('themes');
            
            if (!\Illuminate\Support\Facades\File::exists($extractPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($extractPath, 0755, true);
            }

            $zip->extractTo($extractPath);
            $zip->close();
            
            return back()->with('success', 'Theme successfully uploaded and extracted to engine!');
        }

        return back()->with('error', 'Failed to open the uploaded ZIP file.');
    }

    /**
     * Activate a theme.
     */
    public function activate(string $theme)
    {
        try {
            $this->themeManager->setActive($theme);
            return back()->with('success', "Theme '{$theme}' activated successfully.");
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Theme customization form.
     */
    public function customize()
    {
        $theme = $this->themeManager->getActive();
        $config = $this->themeManager->getConfig($theme);
        $customization = $this->themeManager->getCustomization();

        // Get customizable fields from theme.json
        $fields = $config['customizable'] ?? [];

        return view('admin.themes.customize', compact('theme', 'fields', 'customization'));
    }

    /**
     * Save customization.
     */
    public function saveCustomization(Request $request)
    {
        $this->themeManager->saveCustomization($request->input('custom', []));
        return back()->with('success', 'Theme customization saved.');
    }
}
