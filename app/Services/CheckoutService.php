<?php

namespace App\Services;

use App\Models\{Cart, Order, OrderItem, ShippingMethod, TaxRate, Address, Coupon};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Calculate all checkout totals securely on the backend before placing an order.
     */
    public function calculateTotals(Cart $cart, ShippingMethod $shippingMethod = null): array
    {
        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        $subtotal = $cart->subtotal;
        $discountAmount = 0;
        
        // 1. Calculate discount
        if ($cart->coupon) {
            $discountAmount = $cart->coupon->calculateDiscount($subtotal);
            if ($cart->coupon->type === 'free_shipping') {
                $discountAmount = 0; // Handled separately in shipping cost
            }
        }

        $taxableAmount = taxable_after_discount($subtotal, $discountAmount);

        // 2. Calculate tax (non-compound rates on same base)
        $taxRates = TaxRate::active()->ordered()->get();
        $ratePercents = $taxRates->map(fn ($r) => (float) $r->rate)->all();
        $taxAmount = tax_amount_from_rate_stack($taxableAmount, $ratePercents);

        // 3. Calculate shipping
        $shippingCost = 0;
        $shippingMethodName = 'No Shipping Method';
        
        if ($shippingMethod) {
            if (!$shippingMethod->isApplicable($subtotal)) {
                 throw ValidationException::withMessages(['shipping' => "Shipping method {$shippingMethod->name} is not applicable for this order amount."]);
            }
            
            $shippingMethodName = $shippingMethod->name;
            
            if ($shippingMethod->type === 'flat_rate') {
                 $shippingCost = $shippingMethod->cost;
            } elseif ($shippingMethod->type === 'free' || ($cart->coupon && $cart->coupon->type === 'free_shipping')) {
                 $shippingCost = 0;
            }
        }

        // 4. Final Total
        $total = checkout_grand_total($taxableAmount, $taxAmount, $shippingCost);

        return [
            'subtotal'             => $subtotal,
            'discount_amount'      => $discountAmount,
            'tax_amount'           => $taxAmount,
            'shipping_amount'      => $shippingCost,
            'shipping_method_name' => $shippingMethodName,
            'total'                => $total,
            'currency'             => $cart->currency ?? 'BDT',
        ];
    }

    /**
     * Place the order transactionally.
     */
    public function placeOrder(
        Cart $cart, 
        Address $shippingAddress, 
        Address $billingAddress, 
        ShippingMethod $shippingMethod, 
        string $paymentMethod,
        ?string $notes = null
    ): Order {
        $totals = $this->calculateTotals($cart, $shippingMethod);
        $user = auth()->user();

        return DB::transaction(function () use ($cart, $shippingAddress, $billingAddress, $shippingMethod, $paymentMethod, $notes, $totals, $user) {
            
            // 1. Create Order
            $order = Order::create([
                'user_id'                 => $user->id,
                'coupon_id'               => $cart->coupon_id,
                'status'                  => 'pending',
                'payment_status'          => in_array($paymentMethod, ['cod']) ? 'pending' : 'pending', // Awaiting webhook/payment success
                'payment_method'          => $paymentMethod,
                
                'currency'                => $totals['currency'],
                'subtotal'                => $totals['subtotal'],
                'discount_amount'         => $totals['discount_amount'],
                'tax_amount'              => $totals['tax_amount'],
                'shipping_amount'         => $totals['shipping_amount'],
                'total'                   => $totals['total'],
                'shipping_method_name'    => $totals['shipping_method_name'],

                // 2. Snapshot Addresses
                'shipping_first_name'     => $shippingAddress->first_name,
                'shipping_last_name'      => $shippingAddress->last_name,
                'shipping_phone'          => $shippingAddress->phone,
                'shipping_address_line_1' => $shippingAddress->address_line_1,
                'shipping_address_line_2' => $shippingAddress->address_line_2,
                'shipping_city'           => $shippingAddress->city,
                'shipping_state'          => $shippingAddress->state,
                'shipping_zip_code'       => $shippingAddress->zip_code,
                'shipping_country'        => $shippingAddress->country,

                'billing_first_name'      => $billingAddress->first_name,
                'billing_last_name'       => $billingAddress->last_name,
                'billing_phone'           => $billingAddress->phone,
                'billing_address_line_1'  => $billingAddress->address_line_1,
                'billing_address_line_2'  => $billingAddress->address_line_2,
                'billing_city'            => $billingAddress->city,
                'billing_state'           => $billingAddress->state,
                'billing_zip_code'        => $billingAddress->zip_code,
                'billing_country'         => $billingAddress->country,
                
                'notes'                   => $notes,
            ]);

            // 3. Create Order Items & Deduct Stock
            foreach ($cart->items as $item) {
                $product = $item->product;
                $variant = $item->productVariant;
                
                $itemTotal = $item->subtotal;
                $itemTax = estimated_order_item_tax($itemTotal);
                
                OrderItem::create([
                    'order_id'           => $order->id,
                    'vendor_id'          => $product->vendor_id,
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name'       => $product->name,
                    'variant_name'       => $variant?->name,
                    'sku'                => $variant ? $variant->sku : $product->sku,
                    'price'              => $item->price,
                    'quantity'           => $item->quantity,
                    'subtotal'           => $itemTotal,
                    'discount_amount'    => 0, // Per-item discount could be added later
                    'tax_amount'         => $itemTax,
                    'total'              => line_total_with_tax((float) $itemTotal, $itemTax),
                ]);

                // Deduct Inventory Stock
                if ($variant) {
                    $variant->decrement('quantity', $item->quantity);
                }
                if ($product->track_quantity) {
                    $product->decrement('quantity', $item->quantity);
                }
                
                // Increment sales count
                $product->increment('sales_count', $item->quantity);
            }

            // 4. Initial Status History
            $order->statusHistory()->create([
                'new_status' => 'pending',
                'comment'    => 'Order placed successfully.',
                'user_id'    => $user->id,
            ]);

            // 5. Update Coupon Usage
            if ($cart->coupon) {
                $cart->coupon->increment('times_used');
            }

            // 6. Clear Cart
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);

            return $order;
        });
    }
}
