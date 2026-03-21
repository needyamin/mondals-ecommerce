<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;

    /**
     * Get user's wishlist contents.
     */
    public function index(): JsonResponse
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product.images' => fn($q) => $q->primary(), 'product.brand'])
            ->latest()
            ->paginate(15);

        return $this->paginated($wishlists, 'Wishlist retrieved successfully');
    }

    /**
     * Toggle product in wishlist.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return $this->success(null, 'Product removed from wishlist');
        }

        auth()->user()->wishlists()->create(['product_id' => $request->product_id]);
        return $this->created(null, 'Product added to wishlist');
    }
}
