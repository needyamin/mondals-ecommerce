<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, Vendor};
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application admin dashboard.
     */
    public function index(Request $request): View
    {
        $stats = [
            'total_sales' => Order::where('payment_status', 'paid')->sum('total'),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_products' => Product::active()->count(),
            'total_users' => User::count(),
            'approved_vendors' => Vendor::approved()->count(),
            'pending_vendors' => Vendor::pending()->count(),
        ];

        // Ensure user is admin or staff to view this.
        // Middleware handles the restriction, but we prep data here.

        return view('admin.dashboard', compact('stats'));
    }
}
