<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Vendor, VendorPayout};
use App\Services\PayoutService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    protected PayoutService $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    /**
     * List all payouts.
     */
    public function index(Request $request)
    {
        $payouts = VendorPayout::with('vendor')
            ->when($request->input('status'), fn($q, $s) => $q->where('status', $s))
            ->when($request->input('vendor_id'), fn($q, $v) => $q->where('vendor_id', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Get vendors that have unpaid earnings
        $vendorsWithBalance = Vendor::whereHas('earnings', fn($q) => $q->unpaid())
            ->withSum(['earnings as unpaid_balance' => fn($q) => $q->unpaid()], 'vendor_earning')
            ->get();

        return view('admin.payouts.index', compact('payouts', 'vendorsWithBalance'));
    }

    /**
     * Create a new payout for a vendor.
     */
    public function create(Request $request)
    {
        $request->validate([
            'vendor_id'      => 'required|exists:vendors,id',
            'payment_method' => 'required|string|max:50',
            'reference'      => 'nullable|string|max:255',
        ]);

        $vendor = Vendor::findOrFail($request->vendor_id);

        try {
            $payout = $this->payoutService->createPayout($vendor, $request->payment_method, $request->reference);
            return back()->with('success', "Payout of ৳{$payout->amount} created for {$vendor->store_name}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark payout as completed.
     */
    public function process(int $id)
    {
        $payout = VendorPayout::findOrFail($id);

        try {
            $this->payoutService->processPayout($payout);
            return back()->with('success', 'Payout processed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
