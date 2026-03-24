<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MarketingTrackingController extends Controller
{
    public const GROUP = 'marketing';

    /** @return array<string, array{0:string,1:string}> */
    protected function keys(): array
    {
        return [
            'ga4_measurement_id'   => ['text', ''],
            'google_ads_id'        => ['text', ''],
            'facebook_pixel_id'    => ['text', ''],
            'tiktok_pixel_id'      => ['text', ''],
            'linkedin_partner_id'  => ['text', ''],
            'custom_head_html'     => ['textarea', ''],
            'custom_body_html'     => ['textarea', ''],
        ];
    }

    protected function ensureDefaults(): void
    {
        foreach ($this->keys() as $key => [$type, $default]) {
            if (! Setting::where('group', self::GROUP)->where('key', $key)->exists()) {
                Setting::set($key, $default, self::GROUP, $type, false);
            }
        }
    }

    public function edit()
    {
        $this->ensureDefaults();
        $values = [];
        foreach (array_keys($this->keys()) as $key) {
            $values[$key] = Setting::get($key, '', self::GROUP) ?? '';
            if (is_array($values[$key])) {
                $values[$key] = json_encode($values[$key]);
            }
        }

        return view('admin.marketing.edit', ['values' => $values]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'ga4_measurement_id'  => 'nullable|string|max:32',
            'google_ads_id'       => 'nullable|string|max:32',
            'facebook_pixel_id'   => 'nullable|string|max:32',
            'tiktok_pixel_id'     => 'nullable|string|max:64',
            'linkedin_partner_id' => 'nullable|string|max:32',
            'custom_head_html'    => 'nullable|string|max:65535',
            'custom_body_html'    => 'nullable|string|max:65535',
        ]);

        foreach (array_keys($this->keys()) as $key) {
            $val = $request->input($key, '');
            Setting::set($key, is_string($val) ? $val : '', self::GROUP, str_contains($key, 'custom_') ? 'textarea' : 'text', false);
        }

        return redirect()->route('admin.marketing.edit')->with('success', 'Marketing & tracking settings saved.');
    }
}
