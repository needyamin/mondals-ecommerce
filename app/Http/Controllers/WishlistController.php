<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);
            
        return view('customer.wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Product removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $status = 'added';
            $message = 'Product added to wishlist.';
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
