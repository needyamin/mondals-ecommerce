<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponse;

    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get user's cart.
     */
    public function index(): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart()->load('items.product.images', 'items.productVariant', 'coupon');
        return $this->success($cart, 'Cart retrieved');
    }

    /**
     * Add item to cart.
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        try {
            $cart = $this->cartService->addItem($request->product_id, $request->quantity, $request->variant_id);
            return $this->success($cart, 'Item added to cart');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Update item quantity.
     */
    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        try {
            $cart = $this->cartService->updateItem($itemId, $request->quantity);
            return $this->success($cart, 'Cart updated');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Remove item.
     */
    public function removeItem(int $itemId): JsonResponse
    {
        $cart = $this->cartService->removeItem($itemId);
        return $this->success($cart, 'Item removed from cart');
    }

    /**
     * Apply coupon.
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        try {
            $cart = $this->cartService->applyCoupon($request->code);
            return $this->success($cart, 'Coupon applied');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Remove coupon.
     */
    public function removeCoupon(): JsonResponse
    {
        $cart = $this->cartService->removeCoupon();
        return $this->success($cart, 'Coupon removed');
    }

    /**
     * Clear cart.
     */
    public function clearCart(): JsonResponse
    {
        $this->cartService->clearCart();
        return $this->success(null, 'Cart cleared');
    }
}
