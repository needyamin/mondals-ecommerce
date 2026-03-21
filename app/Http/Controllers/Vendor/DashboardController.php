<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Product, VendorEarning, VendorPayout};
use App\Services\CommissionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Vendor dashboard overview.
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;
        $earnings = $this->commissionService->getEarningsSummary($vendor);

        $stats = array_merge($earnings, [
            'total_products'    => Product::byVendor($vendor->id)->count(),
            'active_products'   => Product::byVendor($vendor->id)->active()->count(),
            'pending_orders'    => OrderItem::where('vendor_id', $vendor->id)
                ->whereHas('order', fn($q) => $q->whereIn('status', ['pending', 'confirmed', 'processing']))
                ->distinct('order_id')->count('order_id'),
        ]);

        $recentOrders = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendor->id))
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('vendor.dashboard', compact('stats', 'recentOrders', 'vendor'));
    }
}
