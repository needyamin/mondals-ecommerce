<?php

/**
 * Central money & business math helpers (BDT / storefront totals, commission, coupons).
 */

if (! function_exists('round_money')) {
    function round_money(float $amount, int $decimals = 2): float
    {
        return round($amount, $decimals);
    }
}

if (! function_exists('line_total')) {
    /** Unit price × quantity (cart line). */
    function line_total(float $unitPrice, int $quantity): float
    {
        return round_money($unitPrice * max(0, $quantity));
    }
}

if (! function_exists('cart_subtotal_from_items')) {
    /**
     * Sum line totals for cart-like items with price + quantity.
     *
     * @param  iterable<int, object|array<string, mixed>>  $items
     */
    function cart_subtotal_from_items(iterable $items): float
    {
        $sum = 0.0;
        foreach ($items as $item) {
            $price = is_array($item) ? (float) ($item['price'] ?? 0) : (float) ($item->price ?? 0);
            $qty = is_array($item) ? (int) ($item['quantity'] ?? 0) : (int) ($item->quantity ?? 0);
            $sum += line_total($price, $qty);
        }

        return round_money($sum);
    }
}

if (! function_exists('taxable_after_discount')) {
    function taxable_after_discount(float $subtotal, float $discountAmount): float
    {
        return max(0.0, round_money($subtotal - $discountAmount));
    }
}

if (! function_exists('tax_amount_from_percent')) {
    /** Single tax rate applied to a taxable amount. */
    function tax_amount_from_percent(float $taxableAmount, float $ratePercent): float
    {
        return round_money($taxableAmount * ($ratePercent / 100));
    }
}

if (! function_exists('tax_amount_from_rate_stack')) {
    /**
     * Sum tax from multiple percentage rates applied to the same base (non-compound).
     *
     * @param  list<float>  $ratePercents
     */
    function tax_amount_from_rate_stack(float $taxableAmount, array $ratePercents): float
    {
        $tax = 0.0;
        foreach ($ratePercents as $p) {
            $tax += $taxableAmount * ((float) $p / 100);
        }

        return round_money($tax);
    }
}

if (! function_exists('checkout_grand_total')) {
    /** Final order total: after-discount goods + tax + shipping. */
    function checkout_grand_total(float $taxableAmount, float $taxAmount, float $shippingAmount): float
    {
        return round_money($taxableAmount + $taxAmount + $shippingAmount);
    }
}

if (! function_exists('order_total_simple')) {
    /** Checkout without line tax: taxable amount + shipping (used by web checkout). */
    function order_total_simple(float $subtotal, float $discountAmount, float $shippingAmount): float
    {
        return round_money(taxable_after_discount($subtotal, $discountAmount) + $shippingAmount);
    }
}

if (! function_exists('calculate_coupon_discount')) {
    /**
     * Core coupon math (percentage / fixed). free_shipping → 0 here; handle shipping in caller.
     */
    function calculate_coupon_discount(
        float $subtotal,
        string $type,
        float $value,
        ?float $minOrderAmount = null,
        ?float $maxDiscountAmount = null
    ): float {
        if ($type === 'free_shipping') {
            return 0.0;
        }
        if ($minOrderAmount !== null && $subtotal < $minOrderAmount) {
            return 0.0;
        }

        $discount = match ($type) {
            'percentage' => $subtotal * ($value / 100),
            'fixed' => $value,
            default => 0.0,
        };

        if ($maxDiscountAmount !== null) {
            $discount = min($discount, $maxDiscountAmount);
        }

        return round_money(min($discount, $subtotal));
    }
}

if (! function_exists('commission_amount')) {
    function commission_amount(float $vendorSubtotal, float $commissionRatePercent): float
    {
        return round_money($vendorSubtotal * ($commissionRatePercent / 100));
    }
}

if (! function_exists('vendor_net_after_commission')) {
    function vendor_net_after_commission(float $vendorSubtotal, float $commissionRatePercent): float
    {
        return round_money($vendorSubtotal - commission_amount($vendorSubtotal, $commissionRatePercent));
    }
}

if (! function_exists('line_total_with_tax')) {
    function line_total_with_tax(float $lineSubtotal, float $lineTax): float
    {
        return round_money($lineSubtotal + $lineTax);
    }
}

if (! function_exists('estimated_order_item_tax')) {
    /** Placeholder flat % on line total until order-level tax is split per line. */
    function estimated_order_item_tax(float $lineSubtotal, float $taxPercent = 15.0): float
    {
        return tax_amount_from_percent($lineSubtotal, $taxPercent);
    }
}

if (! function_exists('savings_percent_from_compare')) {
    /** Discount % from compare-at vs sale price, or null if invalid. */
    function savings_percent_from_compare(float $comparePrice, float $salePrice): ?float
    {
        if ($comparePrice <= 0 || $comparePrice <= $salePrice) {
            return null;
        }

        return round((($comparePrice - $salePrice) / $comparePrice) * 100, 1);
    }
}
