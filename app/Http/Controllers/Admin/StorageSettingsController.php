<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MediaDisks;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StorageSettingsController extends Controller
{
    public function edit()
    {
        $disk = Setting::get('product_upload_disk', 'public', MediaDisks::GROUP);

        return view('admin.storage.edit', [
            'currentDisk' => in_array($disk, MediaDisks::productDiskOptions(), true) ? $disk : 'public',
            'options' => MediaDisks::productDiskOptions(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_upload_disk' => ['required', Rule::in(MediaDisks::productDiskOptions())],
        ]);

        Setting::set(
            'product_upload_disk',
            $request->input('product_upload_disk'),
            MediaDisks::GROUP,
            'text',
            false
        );

        return back()->with('success', 'Product media storage updated. Set matching credentials in your .env file.');
    }
}
