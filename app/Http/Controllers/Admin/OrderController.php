<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem};
use Illuminate\Http\Request;

use App\Traits\ExportsToCsv;

class OrderController extends Controller
{
    use ExportsToCsv;
    /**
     * List all orders with filters.
     */
    public function index(Request $request)
    {
        $query = $this->applyFilters(Order::with('user'), $request);
        $orders = $query->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    protected function applyFilters($query, Request $request)
    {
        if ($status = $request->input('status')) $query->byStatus($status);
        if ($payment = $request->input('payment_status')) $query->byPayment($payment);
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"));
            });
        }
        if ($request->filled('user_id')) {
            $uid = (int) $request->input('user_id');
            if ($uid > 0) {
                $query->where('user_id', $uid);
            }
        }

        return $query;
    }

    public function export(Request $request)
    {
        $query = $this->applyFilters(Order::with('user'), $request);

        return $this->exportCsv($query, 'orders-report', [
            'Order #'       => 'order_number',
            'Customer'      => 'user.name',
            'Email'         => 'user.email',
            'Total'         => 'total_amount',
            'Items Count'   => 'items_count',
            'Status'        => 'status',
            'Payment'       => 'payment_status',
            'Payment Method'=> 'payment_method',
            'Created At'    => 'created_at',
        ]);
    }

    /**
     * Show order detail.
     */
    public function show(int $id)
    {
        $order = Order::with([
            'user', 'items.product', 'items.vendor', 'items.productVariant',
            'statusHistory', 'refunds', 'invoices'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status'  => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled',
            'comment' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $order->updateStatus($request->status, $request->comment);

        return back()->with('success', "Order status updated to {$request->status}.");
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, int $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'paid' ? now() : $order->paid_at,
        ]);

        return back()->with('success', "Payment status updated to {$request->payment_status}.");
    }
}
