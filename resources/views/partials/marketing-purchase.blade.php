@isset($order)
@php
    $g = 'marketing';
    $ga4 = trim((string) (\App\Models\Setting::get('ga4_measurement_id', '', $g) ?? ''));
    $aw = trim((string) (\App\Models\Setting::get('google_ads_id', '', $g) ?? ''));
    $fbPixel = preg_replace('/\D/', '', (string) (\App\Models\Setting::get('facebook_pixel_id', '', $g) ?? ''));
    $tt = trim((string) (\App\Models\Setting::get('tiktok_pixel_id', '', $g) ?? ''));
    $items = $order->items->map(fn ($i) => [
        'item_id' => (string) ($i->sku ?: $i->product_id ?: $i->id),
        'item_name' => $i->product_name,
        'quantity' => (int) $i->quantity,
        'price' => (float) $i->price,
    ])->values()->all();
@endphp
@if($ga4 || $aw || $fbPixel || $tt)
<script>
(function () {
    var tx = @json($order->order_number);
    var value = {{ json_encode((float) $order->total) }};
    var currency = @json($order->currency);
    var items = @json($items);
    @if($ga4 || $aw)
    if (typeof gtag === 'function') {
        gtag('event', 'purchase', {
            transaction_id: tx,
            value: value,
            currency: currency,
            items: items.map(function (it, idx) {
                return { item_id: it.item_id, item_name: it.item_name, quantity: it.quantity, price: it.price, index: idx };
            })
        });
    }
    @endif
    @if($fbPixel)
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            value: value,
            currency: currency,
            contents: items.map(function (it) {
                return { id: it.item_id, quantity: it.quantity, item_price: it.price };
            }),
            content_type: 'product'
        });
    }
    @endif
    @if($tt)
    if (typeof ttq !== 'undefined' && ttq.track) {
        ttq.track('CompletePayment', {
            contents: items.map(function (it) {
                return { content_id: it.item_id, content_type: 'product', content_name: it.item_name, quantity: it.quantity, price: it.price };
            }),
            value: String(value),
            currency: currency
        });
    }
    @endif
})();
</script>
@endif
@endisset
