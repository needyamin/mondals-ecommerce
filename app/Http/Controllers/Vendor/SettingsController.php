<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /**
     * Show vendor store settings.
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;
        return view('vendor.settings', compact('vendor'));
    }

    /**
     * Update vendor store settings.
     */
    public function update(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $request->validate([
            'store_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'city'       => 'nullable|string|max:100',
            'address'    => 'nullable|string|max:500',
            'logo'       => 'nullable|image|max:1024',
            'banner'     => 'nullable|image|max:2048',
            'bank_name'  => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['store_name', 'email', 'phone', 'city', 'address', 'state', 'zip_code', 'country']);
        
        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($vendor->logo) Storage::disk('public')->delete($vendor->logo);
            $data['logo'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        // Handle Banner Upload
        if ($request->hasFile('banner')) {
            if ($vendor->banner) Storage::disk('public')->delete($vendor->banner);
            $data['banner'] = $request->file('banner')->store('vendors/banners', 'public');
        }

        // Handle Bank Details (JSON Settings Column)
        $settings = $vendor->settings ?? [];
        $settings['banking'] = [
            'bank_name'      => $request->bank_name,
            'account_name'   => $request->account_name,
            'account_number' => $request->account_number,
        ];
        $data['settings'] = $settings;

        $vendor->update($data);

        return back()->with('success', 'Store settings updated successfully.');
    }
}
