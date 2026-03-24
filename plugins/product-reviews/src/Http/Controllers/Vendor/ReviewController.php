<?php

namespace Plugins\ProductReviews\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\ProductReviews\Models\Review;

class ReviewController extends Controller
{
    protected function vendorProductReview(Review $review): void
    {
        $vendorId = auth()->user()->vendor?->id;
        abort_unless($vendorId && (int) $review->product?->vendor_id === (int) $vendorId, 404);
    }

    public function index(Request $request)
    {
        $vendorId = auth()->user()->vendor->id;
        $status   = $request->get('status', 'pending');

        $query = Review::with(['user', 'product'])
            ->whereHas('product', fn ($q) => $q->where('vendor_id', $vendorId))
            ->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        } elseif ($status !== 'all') {
            $query->where('status', 'pending');
        }

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('product', fn ($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        $vendorReviewBase = Review::whereHas('product', fn ($q) => $q->where('vendor_id', $vendorId));
        $aggregates       = (clone $vendorReviewBase)
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');
        $counts = [
            'pending'  => (int) ($aggregates['pending'] ?? 0),
            'approved' => (int) ($aggregates['approved'] ?? 0),
            'rejected' => (int) ($aggregates['rejected'] ?? 0),
            'all'      => (clone $vendorReviewBase)->count(),
        ];
        $pendingCount = $counts['pending'];

        return view('product-reviews::vendor.index', compact('reviews', 'status', 'pendingCount', 'counts'));
    }

    public function approve(Review $review)
    {
        $this->vendorProductReview($review);
        $review->approve();

        return back()->with('success', 'Review approved.');
    }

    public function reject(Review $review)
    {
        $this->vendorProductReview($review);
        $review->reject();

        return back()->with('success', 'Review rejected.');
    }
}
