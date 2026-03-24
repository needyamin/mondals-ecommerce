@extends('layouts.admin')

@section('title', 'IP & user blocking')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">IP &amp; user blocking</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1.5 text-sm max-w-2xl">Block IPs or CIDR ranges from the storefront and API. Ban accounts by email (admins cannot be banned here).</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/25 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-200 text-sm font-medium">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/25 border border-rose-200 dark:border-rose-800/80 text-rose-800 dark:text-rose-200 text-sm font-medium">{{ session('error') }}</div>
@endif

<div class="grid gap-6 lg:grid-cols-2 mb-8">
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/30">
            <h3 class="font-bold text-slate-900 dark:text-white">Blocked IPs</h3>
            <p class="text-xs text-slate-500 mt-0.5">Single IP (e.g. 203.0.113.10) or CIDR (e.g. 203.0.113.0/24).</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.ip-blocking.ips.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-6">
                @csrf
                <input type="text" name="ip_address" value="{{ old('ip_address') }}" placeholder="IP or CIDR" class="flex-1 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm" required>
                <input type="text" name="note" value="{{ old('note') }}" placeholder="Note (optional)" class="flex-1 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
                <button type="submit" class="shrink-0 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold">Add</button>
            </form>
            @error('ip_address')<p class="text-sm text-rose-600 mb-4">{{ $message }}</p>@enderror
            @error('note')<p class="text-sm text-rose-600 mb-4">{{ $message }}</p>@enderror

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <th class="py-2 pr-4">Rule</th>
                            <th class="py-2 pr-4">Note</th>
                            <th class="py-2 text-right w-24"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($ips as $row)
                            <tr>
                                <td class="py-2.5 pr-4 font-mono text-xs">{{ $row->ip_address }}</td>
                                <td class="py-2.5 pr-4 text-slate-500 text-xs">{{ Str::limit($row->note, 40) }}</td>
                                <td class="py-2.5 text-right">
                                    <form action="{{ route('admin.ip-blocking.ips.destroy', $row) }}" method="POST" class="inline" onsubmit="return confirm('Remove this rule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-600 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-8 text-center text-slate-500 text-sm">No IP rules yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $ips->withQueryString()->links() }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/30">
            <h3 class="font-bold text-slate-900 dark:text-white">Banned users</h3>
            <p class="text-xs text-slate-500 mt-0.5">Sets account status to <span class="font-semibold">banned</span> (storefront &amp; customer area).</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.ip-blocking.users.ban') }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-6">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="User email" class="flex-1 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm" required>
                <button type="submit" class="shrink-0 px-4 py-2 rounded-xl bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-sm font-bold">Ban user</button>
            </form>
            @error('email')<p class="text-sm text-rose-600 mb-4">{{ $message }}</p>@enderror

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <th class="py-2 pr-4">User</th>
                            <th class="py-2 text-right w-24"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($bannedUsers as $u)
                            <tr>
                                <td class="py-2.5 pr-4">
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $u->email }}</p>
                                </td>
                                <td class="py-2.5 text-right">
                                    <form action="{{ route('admin.ip-blocking.users.unban', $u) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-emerald-600 hover:underline">Unban</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-8 text-center text-slate-500 text-sm">No banned users.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $bannedUsers->withQueryString()->links() }}</div>
        </div>
    </div>
</div>

<div class="text-xs text-slate-500 dark:text-slate-400">
    Admin, login, register, and payment callback routes are not blocked so you can recover access.
</div>
@endsection
