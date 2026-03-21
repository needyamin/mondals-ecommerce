<?php

namespace App\Services;

use App\Models\{Cart, CartItem, Product, ProductVariant, Coupon};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Get or create active cart for the authenticated user.
     */
    public function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            ['currency' => 'BDT'] // Default currency for now
        );
    }

    /**
     * Add an item to the cart.
     */
    public function addItem(int $productId, int $qty, ?int $variantId = null): Cart
    {
        $cart = $this->getOrCreateCart();
        $product = Product::published()->findOrFail($productId);
        
        $price = $product->price;
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->findOrFail($variantId);
            $price = $variant->effective_price;
            if (!$variant->isInStock()) {
                throw ValidationException::withMessages(['variant' => 'This variant is out of stock.']);
            }
        } elseif (!$product->isInStock()) {
            throw ValidationException::withMessages(['product' => 'This product is out of stock.']);
        }

        DB::transaction(function () use ($cart, $product, $variant, $qty, $price) {
            $item = $cart->items()
                ->where('product_id', $product->id)
                ->where('product_variant_id', $variant?->id)
                ->first();

            if ($item) {
                // Check stock against total new qty
                $newQty = $item->quantity + $qty;
                $maxStock = $variant ? $variant->quantity : ($product->track_quantity ? $product->quantity : 999);
                if ($newQty > $maxStock) {
                    throw ValidationException::withMessages(['quantity' => 'Not enough stock available.']);
                }
                $item->update(['quantity' => $newQty]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'price' => $price,
                    'quantity' => $qty,
                ]);
            }
        });

        // Re-evaluate coupon applicability
        $this->validateCartCoupon($cart);

        return $cart->load('items.product.images', 'items.productVariant', 'coupon');
    }

    /**
     * Update cart item quantity.
     */
    public function updateItem(int $itemId, int $qty): Cart
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();

        if ($qty <= 0) {
            $item->delete();
        } else {
            // Check stock
            $maxStock = $item->productVariant 
                ? $item->productVariant->quantity 
                : ($item->product->track_quantity ? $item->product->quantity : 999);
            
            if ($qty > $maxStock) {
                throw ValidationException::withMessages(['quantity' => 'Not enough stock available.']);
            }
            $item->update(['quantity' => $qty]);
        }

        $this->validateCartCoupon($cart);
        return $cart->load('items');
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $itemId): Cart
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->where('id', $itemId)->delete();
        $this->validateCartCoupon($cart);
        
        return $cart->load('items');
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(string $code): Cart
    {
        $cart = $this->getOrCreateCart();
        $coupon = Coupon::valid()->where('code', strtoupper($code))->first();

        if (!$coupon) {
            throw ValidationException::withMessages(['coupon' => 'Invalid or expired coupon code.']);
        }

        if ($coupon->min_order_amount && $cart->subtotal < $coupon->min_order_amount) {
            throw ValidationException::withMessages(['coupon' => 'Minimum order amount not met for this coupon.']);
        }

        $cart->update(['coupon_id' => $coupon->id]);
        return $cart->load('coupon');
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): Cart
    {
        $cart = $this->getOrCreateCart();
        $cart->update(['coupon_id' => null]);
        return $cart;
    }

    /**
     * Validate the current cart coupon on cart change.
     */
    private function validateCartCoupon(Cart $cart): void
    {
        if ($cart->coupon_id) {
            $coupon = Coupon::find($cart->coupon_id);
            if (!$coupon || !$coupon->isValid() || ($coupon->min_order_amount && $cart->subtotal < $coupon->min_order_amount)) {
                $cart->update(['coupon_id' => null]);
            }
        }
    }

    /**
     * Get the total number of items in the cart (sum of quantities).
     */
    public function getCartCount(): int
    {
        if (auth()->check()) {
            return CartItem::whereHas('cart', function ($q) {
                $q->where('user_id', auth()->id());
            })->sum('quantity');
        }

        return collect(session('cart', []))->sum('quantity');
    }

    /**
     * Clear the cart.
     */
    public function clearCart(): void
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);
        }
    }
}
