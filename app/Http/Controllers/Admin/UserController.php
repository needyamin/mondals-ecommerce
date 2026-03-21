<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Vendor};
use Illuminate\Http\Request;

use App\Traits\ExportsToCsv;

class UserController extends Controller
{
    use ExportsToCsv;
    /**
     * List all customers.
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->with('roles');

        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"));
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($role = $request->input('role')) {
            $query->role($role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a new user account.
     */
    public function create()
    {
        return view('admin.users.form', ['user' => null]);
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:8',
            'role'             => 'required|in:customer,admin,vendor',
            'status'           => 'required|in:active,inactive,banned',
            'marketing_opt_in' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'password'         => bcrypt($validated['password']),
            'status'           => $validated['status'],
            'marketing_opt_in' => $request->has('marketing_opt_in'),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "Account for '{$user->name}' created successfully.");
    }

    /**
     * Show customer detail with orders.
     */
    public function show(int $id)
    {
        $user = User::with(['orders' => fn($q) => $q->latest()->limit(10), 'addresses'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Edit User details.
     */
    public function edit(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.form', compact('user'));
    }

    /**
     * Update User details.
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'password'         => 'nullable|string|min:8',
            'role'             => 'required|in:customer,admin,vendor',
            'status'           => 'required|in:active,inactive,banned',
            'marketing_opt_in' => 'nullable|boolean',
        ]);

        $user->update([
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'status'           => $validated['status'],
            'marketing_opt_in' => $request->has('marketing_opt_in'),
        ]);

        if ($validated['password']) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', "Information for '{$user->name}' updated successfully.");
    }

    /**
     * Ban / Activate / Deactivate user.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:active,inactive,banned']);
        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);
        return back()->with('success', "User status changed to {$request->status}.");
    }

    /**
     * Delete user account.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', "You cannot terminate your own active session.");
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "User account purged successfully.");
    }

    public function export(Request $request)
    {
        $query = User::with('roles');
        
        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"));
        }
        if ($role = $request->input('role')) {
            $query->role($role);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return $this->exportCsv($query, 'user-ledger', [
            'Name'      => 'name',
            'Email'     => 'email',
            'Role'      => fn($u) => $u->roles->pluck('name')->implode(', '),
            'Status'    => 'status',
            'Opt-In'    => fn($u) => $u->marketing_opt_in ? 'Yes' : 'No',
            'Created At'=> 'created_at',
        ]);
    }
}
