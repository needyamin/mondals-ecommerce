@php
    $g = 'marketing';
    $ga4 = trim((string) (\App\Models\Setting::get('ga4_measurement_id', '', $g) ?? ''));
    $aw = trim((string) (\App\Models\Setting::get('google_ads_id', '', $g) ?? ''));
    $fbPixel = preg_replace('/\D/', '', (string) (\App\Models\Setting::get('facebook_pixel_id', '', $g) ?? ''));
    $tt = trim((string) (\App\Models\Setting::get('tiktok_pixel_id', '', $g) ?? ''));
    $liRaw = trim((string) (\App\Models\Setting::get('linkedin_partner_id', '', $g) ?? ''));
    $li = preg_match('/^\d+$/', $liRaw) ? $liRaw : '';
    $customHead = (string) (\App\Models\Setting::get('custom_head_html', '', $g) ?? '');
    $gtagSrc = $ga4 ?: $aw;
@endphp
@if($gtagSrc)
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtagSrc }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
@if($ga4)gtag('config', @json($ga4));@endif
@if($aw)gtag('config', @json($aw));@endif
</script>
@endif
@if($fbPixel)
<!-- Meta Pixel -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', @json($fbPixel));
fbq('track', 'PageView');
</script>
@endif
@if($tt)
<!-- TikTok Pixel -->
<script>
!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};ttq.load(@json($tt));ttq.page();}(window,document,'ttq');
</script>
@endif
@if($li)
<!-- LinkedIn Insight -->
<script type="text/javascript">_linkedin_partner_id = @json($li);window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];window._linkedin_data_partner_ids.push(_linkedin_partner_id);</script>
<script type="text/javascript">(function(l){if(!l){window.lintrk=function(a,b){window.lintrk.q.push([a,b])};window.lintrk.q=[]}var s=document.getElementsByTagName("script")[0],n=document.createElement("script");n.type="text/javascript";n.async=true;n.src="https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(n,s);})(window.lintrk);</script>
@endif
@if($customHead !== '')
{!! $customHead !!}
@endif
