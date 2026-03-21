<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{VendorEarning, VendorPayout};
use Illuminate\Http\Request;

class EarningController extends Controller
{
    /**
     * Earnings overview and history.
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $earnings = VendorEarning::where('vendor_id', $vendor->id)
            ->with(['order:id,order_number,created_at', 'orderItem:id,product_name,quantity,subtotal'])
            ->when($request->input('is_paid'), fn($q, $v) => $q->where('is_paid', filter_var($v, FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total'  => VendorEarning::where('vendor_id', $vendor->id)->sum('vendor_earning'),
            'unpaid' => VendorEarning::where('vendor_id', $vendor->id)->unpaid()->sum('vendor_earning'),
            'paid'   => VendorEarning::where('vendor_id', $vendor->id)->where('is_paid', true)->sum('vendor_earning'),
        ];

        return view('vendor.earnings.index', compact('earnings', 'summary'));
    }

    /**
     * Payout history.
     */
    public function payouts()
    {
        $vendor = auth()->user()->vendor;

        $payouts = VendorPayout::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        return view('vendor.earnings.payouts', compact('payouts'));
    }
}
