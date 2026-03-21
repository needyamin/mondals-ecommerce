<?php

namespace App\Services;

use App\Models\{Vendor, VendorPayout, VendorEarning};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayoutService
{
    /**
     * Create a payout batch for a vendor (admin action).
     */
    public function createPayout(Vendor $vendor, string $paymentMethod, ?string $reference = null): VendorPayout
    {
        $unpaidEarnings = VendorEarning::where('vendor_id', $vendor->id)->unpaid()->get();

        if ($unpaidEarnings->isEmpty()) {
            throw ValidationException::withMessages(['payout' => 'No unpaid earnings for this vendor.']);
        }

        return DB::transaction(function () use ($vendor, $unpaidEarnings, $paymentMethod, $reference) {
            $totalAmount      = $unpaidEarnings->sum('order_item_total');
            $commissionAmount = $unpaidEarnings->sum('commission_amount');
            $vendorEarning    = $unpaidEarnings->sum('vendor_earning');

            $payout = VendorPayout::create([
                'vendor_id'         => $vendor->id,
                'amount'            => $totalAmount,
                'commission_amount' => $commissionAmount,
                'net_amount'        => $vendorEarning,
                'status'            => 'pending',
                'payment_method'    => $paymentMethod,
                'transaction_id'    => $reference, // Mapping reference from UI to transaction_id
                'notes'             => 'Bulk disbursement for ' . $unpaidEarnings->count() . ' items.',
            ]);

            // Link earnings to this payout
            foreach ($unpaidEarnings as $earning) {
                $earning->update([
                    'vendor_payout_id' => $payout->id,
                    'is_paid'          => true,
                ]);
            }

            return $payout;
        });
    }

    /**
     * Process (mark as completed) a pending payout.
     */
    public function processPayout(VendorPayout $payout): VendorPayout
    {
        if ($payout->status !== 'pending') {
            throw ValidationException::withMessages(['payout' => 'Payout is already processed.']);
        }

        $payout->update([
            'status'  => 'completed',
            'paid_at' => now(),
        ]);

        return $payout;
    }
}
