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

    /** @var list<string> */
    private const STATUSES = ['pending', 'processing', 'completed', 'failed', 'cancelled'];

    /**
     * List all payouts.
     */
    public function index(Request $request)
    {
        $query = VendorPayout::with('vendor');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('payout_number', 'LIKE', "%{$search}%")
                    ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                    ->orWhereHas('vendor', function ($vq) use ($search) {
                        $vq->where('store_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $st = $request->string('status')->trim()->toString();
            if (in_array($st, self::STATUSES, true)) {
                $query->where('status', $st);
            }
        }

        if ($request->filled('vendor_id')) {
            $vid = (int) $request->input('vendor_id');
            if ($vid > 0 && Vendor::whereKey($vid)->exists()) {
                $query->where('vendor_id', $vid);
            }
        }

        $payouts = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'       => VendorPayout::count(),
            'pending'     => VendorPayout::where('status', 'pending')->count(),
            'processing'  => VendorPayout::where('status', 'processing')->count(),
            'completed'   => VendorPayout::where('status', 'completed')->count(),
            'failed'      => VendorPayout::where('status', 'failed')->count(),
            'cancelled'   => VendorPayout::where('status', 'cancelled')->count(),
        ];

        $vendorsWithBalance = Vendor::whereHas('earnings', fn($q) => $q->unpaid())
            ->withSum(['earnings as unpaid_balance' => fn($q) => $q->unpaid()], 'vendor_earning')
            ->orderBy('store_name')
            ->get();

        $vendorIds = VendorPayout::query()->distinct()->pluck('vendor_id')->filter()->values();
        $vendorsForFilter = $vendorIds->isEmpty()
            ? collect()
            : Vendor::whereIn('id', $vendorIds)->orderBy('store_name')->get();

        return view('admin.payouts.index', compact(
            'payouts',
            'vendorsWithBalance',
            'stats',
            'vendorsForFilter'
        ));
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
