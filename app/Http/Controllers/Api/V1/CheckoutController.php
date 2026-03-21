<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Address, ShippingMethod};
use App\Services\{CartService, CheckoutService};
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    use ApiResponse;

    protected CheckoutService $checkoutService;
    protected CartService $cartService;

    public function __construct(CheckoutService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    /**
     * Retrieve calculated totals before placing order.
     */
    public function calculate(Request $request): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart();
        $shippingMethod = $request->shipping_method_id ? ShippingMethod::findOrFail($request->shipping_method_id) : null;

        try {
            $totals = $this->checkoutService->calculateTotals($cart, $shippingMethod);
            return $this->success($totals, 'Totals calculated');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Place the order.
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id'  => 'required|exists:addresses,id',
            'shipping_method_id'  => 'required|exists:shipping_methods,id',
            'payment_method'      => 'required|in:cod,bkash,nagad,card',
            'notes'               => 'nullable|string|max:1000',
        ]);

        $cart = $this->cartService->getOrCreateCart();
        $shippingAddress = Address::where('user_id', auth()->id())->findOrFail($request->shipping_address_id);
        $billingAddress = Address::where('user_id', auth()->id())->findOrFail($request->billing_address_id);
        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);

        try {
            $order = $this->checkoutService->placeOrder(
                $cart,
                $shippingAddress,
                $billingAddress,
                $shippingMethod,
                $request->payment_method,
                $request->notes
            );
            return $this->success($order->load('items'), 'Order placed successfully.', 201);
        } catch (\Exception $e) {
            // General exception fallback including validation exceptions raised deeply
            return $this->error($e->getMessage(), 422);
        }
    }
}
