<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $this->vendorOrFail();

        $orders = Order::whereHas('items', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with([
                'user',
                'items' => fn ($q) => $q->where('vendor_id', $vendor->id)
                    ->with(['product' => fn ($p) => $p->with('images')]),
            ])
            ->when($request->filled('status'), fn ($q) => $q->byStatus($request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = trim((string) $request->input('search'));
                $q->where(function ($qq) use ($s) {
                    $qq->where('order_number', 'LIKE', '%'.$s.'%');
                    if (ctype_digit($s)) {
                        $qq->orWhere('id', (int) $s);
                    }
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $vendor = $this->vendorOrFail();

        $order = Order::whereHas('items', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with([
                'user',
                'items' => fn ($q) => $q->with([
                    'product' => fn ($p) => $p->with('images'),
                    'productVariant',
                ]),
                'statusHistory' => fn ($q) => $q->with('user:id,name')->orderBy('created_at'),
            ])
            ->findOrFail($id);

        $vendorItems = $order->items->where('vendor_id', $vendor->id)->values();

        $onlyThisVendor = $order->items->isNotEmpty()
            && $order->items->every(fn ($i) => (int) $i->vendor_id === (int) $vendor->id);

        $vendorSubtotal = (float) $vendorItems->sum('subtotal');
        $commissionRate = (float) ($vendor->commission_rate ?? 10);
        $commission = commission_amount($vendorSubtotal, $commissionRate);
        $vendorEarning = vendor_net_after_commission($vendorSubtotal, $commissionRate);

        $canMarkProcessing = $onlyThisVendor && in_array($order->status, ['pending', 'confirmed'], true);
        $canMarkShipped = $onlyThisVendor && $order->status === 'processing';

        return view('vendor.orders.show', compact(
            'order',
            'vendor',
            'vendorItems',
            'vendorSubtotal',
            'commission',
            'commissionRate',
            'vendorEarning',
            'onlyThisVendor',
            'canMarkProcessing',
            'canMarkShipped'
        ));
    }

    public function advance(Request $request, int $id)
    {
        $vendor = $this->vendorOrFail();

        $validated = $request->validate([
            'step' => 'required|in:processing,shipped',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::whereHas('items', fn ($q) => $q->where('vendor_id', $vendor->id))
            ->with('items')
            ->findOrFail($id);

        if ($order->items->contains(fn ($i) => (int) $i->vendor_id !== (int) $vendor->id)) {
            return back()->withErrors(['step' => 'This order includes products from other sellers. The store team updates order status.']);
        }

        $step = $validated['step'];

        $allowed = [
            'processing' => ['pending', 'confirmed'],
            'shipped' => ['processing'],
        ];

        if (! in_array($order->status, $allowed[$step], true)) {
            return back()->withErrors(['step' => 'This action is not available for the current order status.']);
        }

        $old = $order->status;
        $updates = ['status' => $step];

        if ($step === 'shipped') {
            $updates['shipped_at'] = $order->shipped_at ?? now();
        }

        $order->update($updates);

        $note = trim((string) ($validated['note'] ?? ''));
        $comment = 'Fulfillment update by vendor.'.($note !== '' ? "\n".$note : '');

        $order->statusHistory()->create([
            'old_status' => $old,
            'new_status' => $step,
            'comment' => $comment,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Order status updated to '.ucfirst($step).'.');
    }

    private function vendorOrFail()
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Vendor profile not found.');

        return $vendor;
    }
}
