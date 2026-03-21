<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\OrderItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    /**
     * Submit a product review.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'nullable|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'required|string|max:255',
            'comment'    => 'required|string|max:1000',
        ]);

        // Check if user actually purchased this product (Optional rule)
        $hasPurchased = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', auth()->id())->whereIn('status', ['delivered', 'completed']);
        })->where('product_id', $validated['product_id'])->exists();

        $review = auth()->user()->reviews()->create(array_merge($validated, [
            // Auto approve if purchased, else pending moderation
            'status' => $hasPurchased ? 'approved' : 'pending',
            'approved_at' => $hasPurchased ? now() : null,
        ]));

        return $this->created(
            $review, 
            $hasPurchased ? 'Review published successfully' : 'Review submitted for moderation'
        );
    }

    /**
     * Get paginated reviews for a specific product (Public endpoint logic)
     */
    public function index(Request $request, int $productId): JsonResponse
    {
        $reviews = Review::where('product_id', $productId)
            ->approved()
            ->with(['user:id,name,avatar'])
            ->latest()
            ->paginate($request->input('per_page', 10));

        return $this->paginated($reviews, 'Product reviews retrieved');
    }
}
