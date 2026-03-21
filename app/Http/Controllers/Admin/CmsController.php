<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Page, Banner, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    // ── Pages ──

    public function pages(Request $request)
    {
        $pages = Page::when($request->search, fn($q, $s) => $q->where('title', 'LIKE', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();
        return view('admin.cms.pages', compact('pages'));
    }

    public function createPage() { return view('admin.cms.page-form', ['page' => null]); }

    public function storePage(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'is_active' => 'boolean', 'meta_title' => 'nullable|string|max:255', 'meta_description' => 'nullable|string|max:500']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        Page::create($data);
        return redirect()->route('admin.cms.pages')->with('success', 'Page created.');
    }

    public function editPage(int $id) { return view('admin.cms.page-form', ['page' => Page::findOrFail($id)]); }

    public function updatePage(Request $request, int $id)
    {
        $page = Page::findOrFail($id);
        $data = $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'is_active' => 'boolean', 'meta_title' => 'nullable|string|max:255', 'meta_description' => 'nullable|string|max:500']);
        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $page->update($data);
        return redirect()->route('admin.cms.pages')->with('success', 'Page updated.');
    }

    public function destroyPage(int $id)
    {
        Page::findOrFail($id)->delete();
        return redirect()->route('admin.cms.pages')->with('success', 'Page deleted.');
    }

    // ── Banners ──

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->paginate(15);
        return view('admin.cms.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string|max:500', 'image' => 'required|string|max:255', 'link' => 'nullable|string|max:255', 'position' => 'required|string|max:50', 'is_active' => 'boolean', 'sort_order' => 'integer', 'starts_at' => 'nullable|date', 'expires_at' => 'nullable|date']);
        $data['is_active'] = $request->boolean('is_active');
        Banner::create($data);
        return back()->with('success', 'Banner created.');
    }

    public function destroyBanner(int $id)
    {
        Banner::findOrFail($id)->delete();
        return back()->with('success', 'Banner deleted.');
    }

    // ── Settings ──

    public function settings(Request $request)
    {
        $group = $request->input('group', 'general');
        $settings = Setting::where('group', $group)->get();
        $groups = Setting::select('group')->distinct()->pluck('group');
        return view('admin.cms.settings', compact('settings', 'groups', 'group'));
    }

    public function updateSettings(Request $request)
    {
        $settingsData = $request->input('settings', []);
        
        foreach ($settingsData as $key => $value) {
            // Find setting to identify group and type
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                // Use the static set() method which handles cache invalidation and mutators
                Setting::set($key, $value, $setting->group, $setting->type, $setting->is_public ?? false);
            }
        }
        
        return back()->with('success', 'System parameters updated and cache synchronized.');
    }
}
