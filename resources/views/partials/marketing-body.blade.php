@php
    $customBody = (string) (\App\Models\Setting::get('custom_body_html', '', 'marketing') ?? '');
    $fbPixel = preg_replace('/\D/', '', (string) (\App\Models\Setting::get('facebook_pixel_id', '', 'marketing') ?? ''));
@endphp
@if($fbPixel)
<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id={{ $fbPixel }}&ev=PageView&noscript=1" /></noscript>
@endif
@if($customBody !== '')
{!! $customBody !!}
@endif
