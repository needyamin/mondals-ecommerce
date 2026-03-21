<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

use App\Traits\ExportsToCsv;

class VendorController extends Controller
{
    use ExportsToCsv;
    /**
     * List vendors with filtering.
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user')->withCount('products', 'orders');

        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('store_name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"));
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $vendors = $query->latest()->paginate(20)->withQueryString();
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show form to add/register a merchant manually.
     */
    public function create()
    {
        return view('admin.vendors.form', ['vendor' => null]);
    }

    /**
     * Store a new vendor and their user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email|unique:vendors,email',
            'password'        => 'required|string|min:8',
            'store_name'      => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'description'     => 'nullable|string',
        ]);

        $user = \App\Models\User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status'   => 'active',
        ]);

        $user->assignRole('vendor');

        $vendor = Vendor::create([
            'user_id'         => $user->id,
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'store_name'      => $validated['store_name'],
            'commission_rate' => $validated['commission_rate'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'description'     => $validated['description'],
            'status'          => 'approved',
            'approved_at'     => now(),
        ]);

        return redirect()->route('admin.vendors.index')
            ->with('success', "Merchant '{$vendor->store_name}' has been registered successfully.");
    }

    /**
     * Show vendor detail .
     */
    public function show(int $id)
    {
        $vendor = Vendor::with(['user', 'products' => fn($q) => $q->latest()->limit(10)])->withCount('products', 'orders')->findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Approve vendor application.
     */
    public function approve(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->approve();
        return back()->with('success', "Vendor '{$vendor->store_name}' approved.");
    }

    /**
     * Reject vendor application.
     */
    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);
        $vendor = Vendor::findOrFail($id);
        $vendor->reject($request->reason);
        return back()->with('success', "Vendor '{$vendor->store_name}' rejected.");
    }

    /**
     * Suspend active vendor.
     */
    public function suspend(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->suspend();
        return back()->with('success', "Vendor '{$vendor->store_name}' suspended.");
    }

    /**
     * Unsuspend a vendor.
     */
    public function unsuspend(int $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->unsuspend();
        return back()->with('success', "Vendor '{$vendor->store_name}' restored.");
    }

    /**
     * Update commission rate.
     */
    public function updateCommission(Request $request, int $id)
    {
        $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['commission_rate' => $request->commission_rate]);
        return back()->with('success', "Commission updated to {$request->commission_rate}%.");
    }

    public function export(Request $request)
    {
        $query = Vendor::with('user');
        
        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('store_name', 'LIKE', "%{$search}%")->orWhere('store_email', 'LIKE', "%{$search}%"));
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return $this->exportCsv($query, 'vendor-directory', [
            'Store Name'    => 'store_name',
            'Legal Name'    => 'user.name',
            'Store Email'   => 'store_email',
            'Phone'         => 'phone',
            'Status'        => 'status',
            'Commission'    => fn($v) => $v->commission_rate . '%',
            'Products Count'=> 'products_count',
            'Joined At'     => 'created_at',
        ]);
    }
}
