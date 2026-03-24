<?php

namespace App\Http\Controllers;

use App\Models\{Order, Setting, ShippingMethod};
use App\Services\{CartService, CheckoutService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;
    protected CartService $cartService;

    public function __construct(CheckoutService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    /**
     * Show the checkout form.
     */
    public function index()
    {
        $cart = $this->cartService->getOrCreateCart()->load('items.product.images', 'items.productVariant', 'coupon');
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Add items before checking out.');
        }

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $discountAmount = 0.0;
        $freeShippingCoupon = false;
        if ($cart->coupon && $cart->coupon->isValid()) {
            if ($cart->coupon->type === 'free_shipping') {
                $freeShippingCoupon = true;
            } else {
                $discountAmount = $cart->coupon->calculateDiscount((float) $subtotal);
            }
        }
        $addresses = auth()->user()->addresses ?? collect();
        
        // 1. Load active shipping methods and merge with plugin-hooked methods
        $allMethods = ShippingMethod::where('is_active', true)->get()->map(function($sm) {
            return [
                'id'             => 'db_' . $sm->id,
                'name'           => $sm->name,
                'cost'           => (float) $sm->cost,
                'estimated_days' => $sm->description ?? 'Standard delivery',
                'is_core'        => true
            ];
        })->toArray();
        
        // 2. Discover plugin-based shipping methods via hooks
        $hookedMethods = [];
        try {
            $hookedMethods = app('plugin.manager')->triggerHook('register_shipping_methods', []) ?? [];
            if (!is_array($hookedMethods)) $hookedMethods = [];
        } catch (\Exception $e) {
            Log::debug('Shipping hook error: ' . $e->getMessage());
        }

        // Merge both (plugins take precedence if ID conflicts exist, though they shouldn't here)
        $availShipping = array_merge($allMethods, $hookedMethods);
        
        // 3. Build available payment methods from settings + plugins
        $paymentMethods = $this->getAvailablePaymentMethods();

        return view('pages.checkout', compact(
            'cart', 'subtotal', 'discountAmount', 'freeShippingCoupon', 'addresses',
            'availShipping', 'paymentMethods'
        ));
    }

    /**
     * Process order placement.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_first_name' => 'required|string|max:100',
            'shipping_last_name'  => 'required|string|max:100',
            'shipping_phone'      => 'required|string|max:20',
            'shipping_address'    => 'required|string|max:500',
            'shipping_city'       => 'required|string|max:100',
            'shipping_state'      => 'nullable|string|max:100',
            'shipping_zip'        => 'required|string|max:20',
            'shipping_country'    => 'required|string|max:100',
            'payment_method'      => 'required|string',
            'shipping_method'     => 'required|string',
            'notes'               => 'nullable|string|max:1000',
        ]);

        $cart = $this->cartService->getOrCreateCart()->load('items.product', 'coupon');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        try {
            $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);

            $shippingCost = $this->resolveShippingCost($request->shipping_method, $request->shipping_city);

            $discountAmount = 0.0;
            $appliedCoupon = $cart->coupon;
            if ($appliedCoupon && $appliedCoupon->isValid()) {
                if ($appliedCoupon->type === 'free_shipping') {
                    $shippingCost = 0;
                } else {
                    $discountAmount = $appliedCoupon->calculateDiscount((float) $subtotal);
                }
            } else {
                $appliedCoupon = null;
            }

            $total = round(max(0, $subtotal - $discountAmount) + $shippingCost, 2);

            // Build order
            $order = Order::create([
                'order_number'           => 'ORD-' . strtoupper(uniqid()),
                'user_id'                => auth()->id(),
                'coupon_id'              => $appliedCoupon?->id,
                'status'                 => 'pending',
                'payment_status'         => 'pending', // Enum doesn't allow 'cod'
                'payment_method'         => $request->payment_method,
                'shipping_method_name'   => $request->shipping_method,
                'subtotal'               => $subtotal,
                'discount_amount'        => $discountAmount,
                'tax_amount'             => 0,
                'shipping_amount'        => $shippingCost,
                'total'                  => $total,
                'currency'               => 'BDT',
                'shipping_first_name'    => $request->shipping_first_name,
                'shipping_last_name'     => $request->shipping_last_name,
                'shipping_phone'         => $request->shipping_phone,
                'shipping_address_line_1'=> $request->shipping_address,
                'shipping_city'          => $request->shipping_city,
                'shipping_state'         => $request->shipping_state,
                'shipping_zip_code'      => $request->shipping_zip,
                'shipping_country'       => $request->shipping_country,
                'billing_first_name'     => $request->shipping_first_name,
                'billing_last_name'      => $request->shipping_last_name,
                'billing_phone'          => $request->shipping_phone,
                'billing_address_line_1' => $request->shipping_address,
                'billing_city'           => $request->shipping_city,
                'billing_state'          => $request->shipping_state,
                'billing_zip_code'       => $request->shipping_zip,
                'billing_country'        => $request->shipping_country,
                'notes'                  => $request->notes,
            ]);

            Log::info('Order Created', ['order_id' => $order->id, 'total' => $order->total, 'method' => $request->payment_method]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                $order->items()->create([
                    'product_id'            => $cartItem->product_id,
                    'product_variant_id'    => $cartItem->product_variant_id,
                    'vendor_id'             => $cartItem->product->vendor_id ?? null,
                    'product_name'          => $cartItem->product->name,
                    'variant_name'          => $cartItem->productVariant?->name,
                    'sku'                   => $cartItem->product->sku,
                    'price'                 => $cartItem->price,
                    'quantity'              => $cartItem->quantity,
                    'subtotal'              => $cartItem->price * $cartItem->quantity,
                    'discount_amount'       => 0,
                    'tax_amount'            => 0,
                    'total'                 => $cartItem->price * $cartItem->quantity,
                    'options'               => $cartItem->productVariant?->attributes,
                ]);
            }

            $order->statusHistory()->create([
                'old_status' => null,
                'new_status' => 'pending',
                'comment'    => 'Order placed by customer.',
                'user_id'    => auth()->id(),
            ]);

            // Handle payment gateway redirect (NON-COD)
            if ($request->payment_method !== 'cod') {
                $gateway = $this->findGateway($request->payment_method);
                
                if (!$gateway) {
                    // No gateway found — delete order, keep cart, send back
                    $order->items()->delete();
                    $order->statusHistory()->delete();
                    $order->delete();
                    return back()->with('error', 'Payment method "' . $request->payment_method . '" is not available. Please select another.')->withInput();
                }

                $initResult = $gateway->initiatePayment($order);
                
                if ($initResult['success'] && !empty($initResult['redirect_url'])) {
                    $appliedCoupon?->increment('times_used');
                    $this->cartService->clearCart();
                    return redirect($initResult['redirect_url']);
                }
                
                // Payment init FAILED — delete the order, keep cart, return to checkout
                Log::error("Payment Init Failed for order {$order->order_number}: " . ($initResult['message'] ?? 'Unknown'));
                
                $order->items()->delete();
                $order->statusHistory()->delete();
                $order->delete();
                
                return back()->with('error', 'Payment failed: ' . ($initResult['message'] ?? 'Could not connect to payment provider. Please try again or use a different payment method.'))->withInput();
            }

            // COD — clear cart and go to confirmation
            $appliedCoupon?->increment('times_used');
            $this->cartService->clearCart();

            return redirect()->route('order.confirmation', $order->order_number)
                ->with('success', 'Your order has been placed successfully!');
                
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show order confirmation page.
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        return view('pages.order-confirmation', compact('order'));
    }

    /**
     * Resolve shipping cost from DB methods or plugin hooks.
     */
    protected function resolveShippingCost(string $methodId, ?string $city = null): float
    {
        if (str_starts_with($methodId, 'db_')) {
            $id = (int) substr($methodId, 3);
            if ($id > 0) {
                $sm = ShippingMethod::find($id);
                return $sm ? (float) $sm->cost : 0;
            }
        }

        if (is_numeric($methodId)) {
            $sm = ShippingMethod::find((int) $methodId);
            return $sm ? (float) $sm->cost : 0;
        }

        // 2. Try plugin-based shipping methods
        try {
            $hookedMethods = app('plugin.manager')->triggerHook('register_shipping_methods', []);
            if (is_array($hookedMethods) && isset($hookedMethods[$methodId])) {
                return (float) ($hookedMethods[$methodId]['cost'] ?? 0);
            }
        } catch (\Exception $e) {
            Log::error('Shipping hook error: ' . $e->getMessage());
        }

        return 0;
    }

    /**
     * Get available payment methods based on platform settings and active plugins.
     */
    protected function getAvailablePaymentMethods(): array
    {
        $methods = [];

        // COD - always available if enabled in settings
        if (Setting::get('cod_enabled', true, 'payment')) {
            $methods[] = [
                'id'          => 'cod',
                'name'        => 'Cash on Delivery',
                'description' => 'Pay when you receive your order.',
                'icon'        => 'cash',
            ];
        }

        // Get plugin-based payment gateways
        try {
            $gateways = app()->tagged('payment_gateways');
            foreach ($gateways as $gateway) {
                $id = $gateway->getIdentifier();
                
                // Check if enabled in platform settings
                $settingKey = $id . '_enabled';
                if (!Setting::get($settingKey, false, 'payment')) {
                    continue;
                }
                
                $methods[] = [
                    'id'          => $id,
                    'name'        => $gateway->getName(),
                    'description' => 'Pay securely with ' . $gateway->getName(),
                    'icon'        => $id,
                    'gateway'     => $gateway,
                ];
            }
        } catch (\Exception $e) {
            Log::debug('Payment gateway discovery: ' . $e->getMessage());
        }

        return $methods;
    }

    /**
     * Find a specific payment gateway by identifier.
     */
    protected function findGateway(string $identifier): ?object
    {
        try {
            $gateways = app()->tagged('payment_gateways');
            foreach ($gateways as $gateway) {
                if ($gateway->getIdentifier() === $identifier) {
                    return $gateway;
                }
            }
        } catch (\Exception $e) {
            Log::error('Gateway lookup error: ' . $e->getMessage());
        }
        return null;
    }
}
