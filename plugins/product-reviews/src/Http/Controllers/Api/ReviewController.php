<?php

namespace Plugins\ProductReviews\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Plugins\ProductReviews\Models\Review;

class ReviewController extends Controller
{
    use ApiResponse;

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'nullable|exists:orders,id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'required|string|max:255',
            'comment'    => 'required|string|max:1000',
        ]);

        $hasPurchased = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', auth()->id())->whereIn('status', ['delivered', 'completed']);
        })->where('product_id', $validated['product_id'])->exists();

        $review = auth()->user()->reviews()->create(array_merge($validated, [
            'status'      => $hasPurchased ? 'approved' : 'pending',
            'approved_at' => $hasPurchased ? now() : null,
        ]));

        return $this->created(
            $review,
            $hasPurchased ? 'Review published successfully' : 'Review submitted for moderation'
        );
    }

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
