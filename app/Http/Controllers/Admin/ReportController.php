<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ExportsToCsv;

class ReportController extends Controller
{
    use ExportsToCsv;
    /**
     * Sales Report showing revenue over time.
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $query = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Grouping sales by date for the chart
        $salesData = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalSales = $salesData->sum('total');
        $totalOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();

        if ($request->has('export')) {
            return $this->exportCsv($query, 'revenue-report', [
                'Order ID'      => 'id',
                'Number'        => 'order_number',
                'Total'         => 'total',
                'Payment Status'=> 'payment_status',
                'Created At'    => 'created_at',
            ]);
        }

        return view('admin.reports.sales', compact('salesData', 'startDate', 'endDate', 'totalSales', 'totalOrders'));
    }

    /**
     * Vendor Report showing performance per vendor.
     */
    public function vendors(Request $request)
    {
        $query = Vendor::withCount(['products', 'orders'])
            ->withSum('earnings', 'vendor_earning');

        if ($request->boolean('export')) {
            return $this->exportCsv($query, 'vendor-accounting-sheet', [
                'Store Name'    => 'store_name',
                'Email'         => 'email',
                'Status'        => 'status',
                'Phone'         => 'phone',
                'Commission %'  => 'commission_rate',
                'Products'      => 'products_count',
                'Order Lines'   => 'orders_count',
                'Net Earnings'  => fn ($v) => number_format((float) ($v->earnings_sum_vendor_earning ?? 0), 2, '.', ''),
                'Registered'    => fn ($v) => $v->created_at?->format('Y-m-d H:i:s'),
            ]);
        }

        $vendors = $query->orderBy('store_name')->get();

        return view('admin.reports.vendors', compact('vendors'));
    }
}
