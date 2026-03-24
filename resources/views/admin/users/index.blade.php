@extends('layouts.admin')

@section('title', 'Users')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Users</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Search accounts, filter by role and status, export or manage access.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.users.export', request()->query()) }}" class="inline-flex items-center bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm text-sm">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-brand-500/25 transition text-sm">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add user
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/25 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @php
        $qStatus = array_filter(['search' => request('search'), 'role' => request('role')], fn ($v) => $v !== null && $v !== '');
        $qRole = array_filter(['search' => request('search'), 'status' => request('status')], fn ($v) => $v !== null && $v !== '');
        $activeStatusAll = !request()->filled('status');
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
        <a href="{{ route('admin.users.index', $qRole) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-600 {{ $activeStatusAll ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">All</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </a>
        <a href="{{ route('admin.users.index', $qRole + ['status' => 'active']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-emerald-50 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-800 {{ request('status') === 'active' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Active</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-emerald-950 dark:text-emerald-50">{{ number_format($stats['active']) }}</p>
        </a>
        <a href="{{ route('admin.users.index', $qRole + ['status' => 'inactive']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600 {{ request('status') === 'inactive' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Inactive</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-slate-800 dark:text-slate-100">{{ number_format($stats['inactive']) }}</p>
        </a>
        <a href="{{ route('admin.users.index', $qRole + ['status' => 'banned']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 {{ request('status') === 'banned' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-800 dark:text-rose-400">Banned</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-rose-950 dark:text-rose-50">{{ number_format($stats['banned']) }}</p>
        </a>
        <a href="{{ route('admin.users.index', $qRole + ['status' => 'pending']) }}" class="rounded-2xl p-4 border-2 shadow-sm hover:shadow-md transition-all bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 {{ request('status') === 'pending' ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 border-brand-500' : '' }}">
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-900 dark:text-amber-400">Pending</p>
            <p class="text-2xl font-black mt-1 tabular-nums text-amber-950 dark:text-amber-100">{{ number_format($stats['pending']) }}</p>
        </a>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.users.index', $qStatus) }}" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide border {{ !request()->filled('role') ? 'bg-brand-600 text-white border-brand-600' : 'bg-white dark:bg-darkpanel border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400' }}">All roles</a>
        @foreach(['customer' => 'Customers', 'vendor' => 'Vendors', 'admin' => 'Admins'] as $rk => $rl)
            <a href="{{ route('admin.users.index', $qStatus + ['role' => $rk]) }}" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide border {{ request('role') === $rk ? 'bg-brand-600 text-white border-brand-600' : 'bg-white dark:bg-darkpanel border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400' }}">{{ $rl }}</a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-darkpanel rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-2 mb-6 flex flex-col sm:flex-row gap-2 sm:items-center">
        <div class="flex-1 relative min-w-0">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Name, email, or user ID…">
        </div>
        <div class="flex gap-2 shrink-0 flex-wrap">
            <select name="status" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 py-2.5 px-4 min-w-[140px] focus:ring-2 focus:ring-brand-500">
                <option value="">All statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <select name="role" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 py-2.5 px-4 min-w-[140px] focus:ring-2 focus:ring-brand-500">
                <option value="">All roles</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="vendor" {{ request('role') === 'vendor' ? 'selected' : '' }}>Vendor</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition">Apply</button>
            @if(request()->hasAny(['search', 'status', 'role']))
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Joined</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Orders</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center hidden sm:table-cell">Marketing</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($users as $user)
                        @php
                            $statusClass = match ($user->status) {
                                'active' => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-900/25 dark:text-emerald-300 dark:border-emerald-800',
                                'inactive' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-600',
                                'banned' => 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-900/25 dark:text-rose-300 dark:border-rose-800',
                                'pending' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-900/25 dark:text-amber-300 dark:border-amber-800',
                                default => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[200px]">
                                    <img src="{{ $user->display_avatar }}" alt="" class="w-11 h-11 rounded-xl object-cover border border-slate-200 dark:border-slate-600 shrink-0" loading="lazy">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($user->roles as $role)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-500/10 text-brand-600 dark:text-brand-400">{{ $role->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $user->created_at->format('M j, Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4 text-center text-sm font-semibold tabular-nums text-slate-700 dark:text-slate-300">{{ number_format($user->orders_count) }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide border {{ $statusClass }}">{{ $user->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-center hidden sm:table-cell">
                                @if($user->marketing_opt_in)
                                    <span class="text-xs font-semibold text-brand-600 dark:text-brand-400">Yes</span>
                                @else
                                    <span class="text-xs text-slate-400">No</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-1.5 opacity-80 group-hover:opacity-100">
                                    <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:opacity-90" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-xl bg-brand-600 text-white hover:bg-brand-700" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-sm text-slate-500">No users match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">{{ $users->links() }}</div>
        @endif
    </div>

@endsection
