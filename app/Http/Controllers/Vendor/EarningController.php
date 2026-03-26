<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\{VendorEarning, VendorPayout};
use Illuminate\Http\Request;

class EarningController extends Controller
{
    public function index(Request $request)
    {
        $vendor = $this->vendorOrFail();

        $earnings = VendorEarning::where('vendor_id', $vendor->id)
            ->with(['order', 'orderItem'])
            ->when($request->filled('is_paid'), fn ($q) => $q->where('is_paid', $request->boolean('is_paid')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total'  => round_money((float) VendorEarning::where('vendor_id', $vendor->id)->sum('vendor_earning')),
            'unpaid' => round_money((float) VendorEarning::where('vendor_id', $vendor->id)->unpaid()->sum('vendor_earning')),
            'paid'   => round_money((float) VendorEarning::where('vendor_id', $vendor->id)->where('is_paid', true)->sum('vendor_earning')),
        ];

        return view('vendor.earnings.index', compact('earnings', 'summary'));
    }

    public function payouts()
    {
        $vendor = $this->vendorOrFail();

        $payouts = VendorPayout::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $lifetimeDisbursed = round_money((float) VendorPayout::where('vendor_id', $vendor->id)
            ->where('status', 'completed')
            ->sum('amount'));

        return view('vendor.earnings.payouts', compact('payouts', 'vendor', 'lifetimeDisbursed'));
    }

    public function payoutReceipt(int $id)
    {
        $vendor = $this->vendorOrFail();

        $payout = VendorPayout::where('vendor_id', $vendor->id)->findOrFail($id);

        return view('vendor.earnings.payout-receipt', compact('payout', 'vendor'));
    }

    private function vendorOrFail()
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Vendor profile not found.');

        return $vendor;
    }
}
