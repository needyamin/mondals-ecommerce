<?php

namespace Plugins\ProductReviews\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\ProductReviews\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $query  = Review::with(['user', 'product.vendor'])->latest();

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

        $aggregates = Review::query()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');
        $counts = [
            'pending'  => (int) ($aggregates['pending'] ?? 0),
            'approved' => (int) ($aggregates['approved'] ?? 0),
            'rejected' => (int) ($aggregates['rejected'] ?? 0),
            'all'      => Review::count(),
        ];

        return view('product-reviews::admin.index', compact('reviews', 'status', 'counts'));
    }

    public function approve(int $id)
    {
        Review::findOrFail($id)->approve();

        return back()->with('success', 'Review approved and is now visible on the product page.');
    }

    public function reject(int $id)
    {
        Review::findOrFail($id)->reject();

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(int $id)
    {
        Review::findOrFail($id)->delete();

        return back()->with('success', 'Review deleted.');
    }
}
