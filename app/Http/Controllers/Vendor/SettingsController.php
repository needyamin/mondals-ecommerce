<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /**
     * Show vendor store settings.
     */
    public function index()
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Vendor profile not found.');

        return view('vendor.settings', compact('vendor'));
    }

    /**
     * Update vendor store settings.
     */
    public function update(Request $request)
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Vendor profile not found.');

        $request->validate([
            'store_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'state'      => 'nullable|string|max:100',
            'zip_code'   => 'nullable|string|max:20',
            'country'    => 'nullable|string|max:100',
            'address'    => 'nullable|string|max:500',
            'logo'       => 'nullable|image|max:1024',
            'banner'     => 'nullable|image|max:2048',
            'bank_name'  => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['store_name', 'email', 'phone', 'city', 'address', 'state', 'zip_code', 'country']);

        if ($vendor->store_name !== $request->store_name) {
            $base = Str::slug($request->store_name);
            $slug = $base;
            $n = 1;
            while (Vendor::where('slug', $slug)->where('id', '!=', $vendor->id)->exists()) {
                $slug = $base.'-'.$n++;
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if ($file->isValid()) {
                if (filled($vendor->logo) && ! is_remote_media_url($vendor->logo)) {
                    delete_storage_path($vendor->logo, 'public');
                }
                $data['logo'] = store_public_upload($file, upload_dir_vendor_logos());
            }
        }

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            if ($file->isValid()) {
                if (filled($vendor->banner) && ! is_remote_media_url($vendor->banner)) {
                    delete_storage_path($vendor->banner, 'public');
                }
                $data['banner'] = store_public_upload($file, upload_dir_vendor_banners());
            }
        }

        // Handle Bank Details (JSON Settings Column)
        $settings = $vendor->settings ?? [];
        $settings['banking'] = array_merge($settings['banking'] ?? [], [
            'bank_name'      => $request->bank_name,
            'account_name'   => $request->account_name,
            'account_number' => $request->account_number,
        ]);
        $data['settings'] = $settings;

        $vendor->update($data);

        return back()->with('success', 'Store settings updated successfully.');
    }
}
