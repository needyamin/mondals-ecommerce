<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List orders containing this vendor's products.
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $orders = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendor->id))
            ->with(['user', 'items' => fn($q) => $q->where('vendor_id', $vendor->id)->with('product')])
            ->when($request->input('status'), fn($q, $s) => $q->byStatus($s))
            ->when($request->input('search'), fn($q, $s) => $q->where('order_number', 'LIKE', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show vendor-scoped order detail.
     */
    public function show(int $id)
    {
        $vendor = auth()->user()->vendor;

        $order = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendor->id))
            ->with([
                'user',
                'items' => fn($q) => $q->where('vendor_id', $vendor->id)->with('product', 'productVariant'),
                'statusHistory'
            ])
            ->findOrFail($id);

        // Calculate vendor-specific totals
        $vendorItems = $order->items;
        $vendorSubtotal = $vendorItems->sum('subtotal');
        $commissionRate = $vendor->commission_rate ?? 10;
        $commission = round($vendorSubtotal * ($commissionRate / 100), 2);
        $vendorEarning = $vendorSubtotal - $commission;

        return view('vendor.orders.show', compact('order', 'vendorItems', 'vendorSubtotal', 'commission', 'vendorEarning'));
    }
}
