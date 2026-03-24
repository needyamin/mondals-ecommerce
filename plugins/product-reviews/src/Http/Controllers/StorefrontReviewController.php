<?php

namespace Plugins\ProductReviews\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Plugins\ProductReviews\Models\Review;

class StorefrontReviewController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->where('status', 'approved')->firstOrFail();

        if (Review::where('user_id', auth()->id())->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'You have already submitted a review for this product.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
            'title'   => 'nullable|string|max:255',
        ]);

        $hasPurchased = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', auth()->id())->whereIn('status', ['delivered', 'completed']);
        })->where('product_id', $product->id)->exists();

        $title = $validated['title'] ?? Str::limit(trim($validated['comment']), 80);

        auth()->user()->reviews()->create([
            'product_id'  => $product->id,
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'],
            'title'       => $title,
            'status'      => $hasPurchased ? 'approved' : 'pending',
            'approved_at' => $hasPurchased ? now() : null,
        ]);

        return back()->with(
            'success',
            $hasPurchased
                ? 'Your review has been published.'
                : 'Your review was submitted and will appear after approval.'
        );
    }
}
