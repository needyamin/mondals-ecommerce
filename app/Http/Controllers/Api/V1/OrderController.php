<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * List user's orders (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::forUser(auth()->id())
            ->filter($request->all()) // Supports ?status=...
            ->with(['items.product.images' => fn($q) => $q->primary()])
            ->sorted($request->input('sort'), '-created_at')
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return $this->paginated($orders, 'Orders retrieved successfully');
    }

    /**
     * Get specific order details.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::forUser(auth()->id())
            ->with([
                'items.product.images' => fn($q) => $q->primary(), 
                'items.vendor',
                'items.productVariant.variantValues.attribute',
                'items.productVariant.variantValues.attributeValue',
                'statusHistory'
            ])
            ->findOrFail($id);

        return $this->success($order, 'Order details retrieved');
    }

    /**
     * Cancel a pending order.
     */
    public function cancel(int $id): JsonResponse
    {
        $order = Order::forUser(auth()->id())->findOrFail($id);

        if (!$order->isCancellable()) {
            return $this->error("Order cannot be cancelled because it is currently {$order->status}.", 422);
        }

        // Restore inventory logic could go here, or handled by an observer/event listener on status change
        foreach ($order->items as $item) {
            if ($item->productVariant) {
                $item->productVariant->increment('quantity', $item->quantity);
            }
            if ($item->product && $item->product->track_quantity) {
                $item->product->increment('quantity', $item->quantity);
            }
            if ($item->product) {
                 $item->product->decrement('sales_count', $item->quantity);
            }
        }

        $order->updateStatus('cancelled', 'Cancelled by customer.');
        
        return $this->success($order->fresh(['statusHistory']), 'Order cancelled successfully');
    }
}
