<?php

namespace App\Services;

use App\Models\{Order, OrderItem, Vendor, VendorEarning};
use Illuminate\Support\Facades\DB;

class CommissionService
{
    /**
     * Calculate and record earnings for all vendors on a completed order.
     */
    public function processOrderCommissions(Order $order): void
    {
        if ($order->payment_status !== 'paid') return;

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if (!$item->vendor_id) continue;
                
                // Skip if already recorded
                if (VendorEarning::where('order_item_id', $item->id)->exists()) continue;

                $vendor = Vendor::find($item->vendor_id);
                if (!$vendor) continue;

                $commissionRate = $vendor->commission_rate ?? 10;
                $platformCommission = round($item->subtotal * ($commissionRate / 100), 2);
                $vendorEarning = $item->subtotal - $platformCommission;

                VendorEarning::create([
                    'vendor_id'             => $vendor->id,
                    'order_id'              => $order->id,
                    'order_item_id'         => $item->id,
                    'order_item_total'      => $item->subtotal,
                    'commission_rate'       => $commissionRate,
                    'commission_amount'     => $platformCommission,
                    'vendor_earning'        => $vendorEarning,
                    'is_paid'               => false,
                ]);
            }
        });
    }

    /**
     * Get earnings summary for a vendor.
     */
    public function getEarningsSummary(Vendor $vendor): array
    {
        $earnings = $vendor->earnings();

        return [
            'total_earned'     => round($earnings->sum('vendor_earning'), 2),
            'total_commission' => round($earnings->sum('commission_amount'), 2),
            'total_unpaid'     => round($earnings->unpaid()->sum('vendor_earning'), 2),
            'total_paid'       => round($earnings->where('is_paid', true)->sum('vendor_earning'), 2),
            'total_orders'     => $earnings->distinct('order_id')->count('order_id'),
        ];
    }
}
