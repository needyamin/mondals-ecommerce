<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'statusHistory'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('customer.orders.show', compact('order'));
    }
}
