<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Show the cart page with real data.
     */
    public function index()
    {
        $cart = null;
        $items = collect();
        $subtotal = 0;

        if (auth()->check()) {
            $cart = $this->cartService->getOrCreateCart()->load('items.product.images', 'items.productVariant', 'coupon');
            $items = $cart->items;
            $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
        } else {
            // Session-based cart for guests
            $sessionCart = session('cart', []);
            foreach ($sessionCart as &$entry) {
                $product = \App\Models\Product::find($entry['product_id']);
                if ($product) {
                    $entry['product'] = $product;
                    $entry['line_total'] = $product->price * $entry['quantity'];
                    $subtotal += $entry['line_total'];
                }
            }
            $items = collect($sessionCart);
        }

        return view('pages.cart', compact('items', 'subtotal', 'cart'));
    }

    /**
     * Add item to cart (web form POST).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'    => 'nullable|integer|min:1',
            'variant_id'  => 'nullable|exists:product_variants,id',
        ]);

        $qty = $request->input('quantity', 1);

        if (auth()->check()) {
            try {
                $this->cartService->addItem($request->product_id, $qty, $request->variant_id);
                return back()->with('success', 'Item added to cart!');
            } catch (ValidationException $e) {
                return back()->with('error', $e->getMessage());
            }
        } else {
            // Session-based guest cart
            $cart = session('cart', []);
            $key = $request->product_id . '-' . ($request->variant_id ?? 0);
            
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $qty;
            } else {
                $cart[$key] = [
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity'   => $qty,
                ];
            }
            
            session(['cart' => $cart]);
            return back()->with('success', 'Item added to cart!');
        }
    }

    /**
     * Update cart item quantity (web form POST).
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id'  => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        if (auth()->check()) {
            try {
                $this->cartService->updateItem($request->item_id, $request->quantity);
                return back()->with('success', 'Cart updated.');
            } catch (ValidationException $e) {
                return back()->with('error', $e->getMessage());
            }
        } else {
            $cart = session('cart', []);
            $key = $request->item_id;
            if ($request->quantity <= 0) {
                unset($cart[$key]);
            } elseif (isset($cart[$key])) {
                $cart[$key]['quantity'] = $request->quantity;
            }
            session(['cart' => $cart]);
            return back()->with('success', 'Cart updated.');
        }
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request)
    {
        $request->validate(['item_id' => 'required']);

        if (auth()->check()) {
            $this->cartService->removeItem($request->item_id);
            return back()->with('success', 'Item removed from cart.');
        } else {
            $cart = session('cart', []);
            unset($cart[$request->item_id]);
            session(['cart' => $cart]);
            return back()->with('success', 'Item removed from cart.');
        }
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        if (auth()->check()) {
            $this->cartService->clearCart();
        } else {
            session()->forget('cart');
        }
        return back()->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);

        try {
            $this->cartService->applyCoupon($request->code);
            return back()->with('success', 'Coupon applied.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();

        return back()->with('success', 'Coupon removed.');
    }
}
