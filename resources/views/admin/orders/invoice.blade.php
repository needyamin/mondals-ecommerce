<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice — {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; margin: 0; padding: 24px; color: #111; font-size: 14px; line-height: 1.5; }
        .actions { margin-bottom: 20px; }
        .actions button { padding: 10px 18px; font-weight: 700; border: none; border-radius: 8px; background: #4f46e5; color: #fff; cursor: pointer; }
        .actions button:hover { background: #4338ca; }
        header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; padding-bottom: 16px; border-bottom: 2px solid #e5e7eb; }
        h1 { margin: 0; font-size: 22px; }
        .meta { text-align: right; color: #6b7280; font-size: 13px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
        .box h2 { margin: 0 0 8px; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; background: #f9fafb; }
        td.num, th.num { text-align: right; }
        .totals { max-width: 320px; margin-left: auto; margin-top: 16px; }
        .totals .row { display: flex; justify-content: space-between; padding: 6px 0; }
        .totals .grand { font-size: 18px; font-weight: 800; padding-top: 12px; margin-top: 8px; border-top: 2px solid #111; }
        @media print {
            .actions { display: none; }
            body { padding: 16px; }
        }
    </style>
</head>
<body>
    @php
        $site = \App\Models\Setting::get('site_name', config('app.name'));
        $cur = $order->currency ?? 'USD';
    @endphp
    <div class="actions">
        <button type="button" onclick="window.print()">Print</button>
    </div>
    <header>
        <div>
            <h1>{{ $site }}</h1>
            <p style="margin:8px 0 0;color:#6b7280;">Order invoice</p>
        </div>
        <div class="meta">
            <div><strong>{{ $order->order_number }}</strong></div>
            <div>{{ $order->created_at->format('M j, Y g:i A') }}</div>
            <div>Status: {{ ucfirst($order->status) }}</div>
            <div>Payment: {{ ucfirst($order->payment_status) }}</div>
        </div>
    </header>
    <div class="grid">
        <div class="box">
            <h2>Bill to</h2>
            @if($order->user)
                <div><strong>{{ $order->user->name }}</strong></div>
                <div>{{ $order->user->email }}</div>
            @else
                <div>Guest</div>
            @endif
        </div>
        <div class="box">
            <h2>Ship to</h2>
            @if($order->shipping_address_line_1)
                <div><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong></div>
                <div>{{ $order->shipping_address_line_1 }}</div>
                @if($order->shipping_address_line_2)<div>{{ $order->shipping_address_line_2 }}</div>@endif
                <div>{{ implode(', ', array_filter([$order->shipping_city, $order->shipping_state, $order->shipping_zip_code])) }}</div>
                <div>{{ $order->shipping_country }}</div>
                <div>Tel: {{ $order->shipping_phone }}</div>
            @else
                <div>—</div>
            @endif
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="num">Price</th>
                <th class="num">Qty</th>
                <th class="num">Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    {{ $item->product_name }}
                    @if($item->options)
                        <div style="font-size:12px;color:#6b7280;">
                            @foreach((array)$item->options as $k => $v){{ $k }}: {{ $v }}@if(!$loop->last), @endif @endforeach
                        </div>
                    @endif
                </td>
                <td class="num">{{ $cur }} {{ number_format($item->price, 2) }}</td>
                <td class="num">{{ $item->quantity }}</td>
                <td class="num">{{ $cur }} {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totals">
        <div class="row">
            <span>Subtotal</span>
            <span>{{ $cur }} {{ number_format($order->subtotal, 2) }}</span>
        </div>
        @if(($order->tax_amount ?? 0) > 0)
        <div class="row">
            <span>Tax</span>
            <span>{{ $cur }} {{ number_format($order->tax_amount, 2) }}</span>
        </div>
        @endif
        <div class="row">
            <span>Shipping</span>
            <span>{{ $cur }} {{ number_format($order->shipping_amount, 2) }}</span>
        </div>
        @if(($order->discount_amount ?? 0) > 0)
        <div class="row" style="color:#059669;">
            <span>Discount</span>
            <span>− {{ $cur }} {{ number_format($order->discount_amount, 2) }}</span>
        </div>
        @endif
        <div class="row grand">
            <span>Total</span>
            <span>{{ $cur }} {{ number_format($order->total, 2) }}</span>
        </div>
    </div>
</body>
</html>
