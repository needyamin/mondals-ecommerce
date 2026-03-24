<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use App\Traits\ExportsToCsv;

class UserController extends Controller
{
    use ExportsToCsv;

    /** @var list<string> */
    private const STATUSES = ['active', 'inactive', 'banned', 'pending'];

    /** @var list<string> */
    private const ROLES = ['customer', 'admin', 'vendor'];

    /**
     * List all customers.
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->with('roles');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                if (ctype_digit($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }
        if ($request->filled('status')) {
            $st = $request->string('status')->trim()->toString();
            if (in_array($st, self::STATUSES, true)) {
                $query->where('status', $st);
            }
        }
        if ($request->filled('role')) {
            $role = $request->string('role')->trim()->toString();
            if (in_array($role, self::ROLES, true)) {
                $query->role($role);
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'    => User::count(),
            'active'   => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'banned'   => User::where('status', 'banned')->count(),
            'pending'  => User::where('status', 'pending')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
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
        $user = User::with(['orders' => fn($q) => $q->latest()->limit(10), 'addresses', 'roles'])
            ->withCount('orders')
            ->findOrFail($id);
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

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                if (ctype_digit($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }
        if ($request->filled('role')) {
            $role = $request->string('role')->trim()->toString();
            if (in_array($role, self::ROLES, true)) {
                $query->role($role);
            }
        }
        if ($request->filled('status')) {
            $st = $request->string('status')->trim()->toString();
            if (in_array($st, self::STATUSES, true)) {
                $query->where('status', $st);
            }
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
