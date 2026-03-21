@extends('layouts.admin')

@section('title', 'User Ecosystem Management')

@section('content')

    <!-- Premium Hub Interface -->
    <div class="mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 group transition duration-500">
        <div class="relative">
            <h2 class="text-4xl font-black text-slate-900 dark:text-white font-heading tracking-tight leading-none">Database Ecosystem</h2>
            <div class="flex items-center gap-3 mt-4">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-ping"></span>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-brand-500 transition-colors">Audit & Authenticate Accounts</p>
            </div>
        </div>
        <div class="flex items-center gap-4 w-full xl:w-auto">
            <a href="{{ route('admin.users.export', request()->query()) }}" class="flex-1 xl:flex-none px-6 py-4 bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none text-slate-500 font-black text-[10px] uppercase tracking-widest hover:text-brand-600 transition-all flex items-center justify-center transform hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Ledger
            </a>
            <a href="{{ route('admin.users.create') }}" class="flex-1 xl:flex-none px-8 py-4 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-brand-500/30 transition-all hover:scale-105 active:scale-95 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Provision Account
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-10 p-5 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex items-center shadow-lg shadow-emerald-500/5">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-black text-sm uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Registry Terminal -->
    <div class="bg-white dark:bg-darkpanel rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden relative group">
        
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/10 flex flex-col md:flex-row justify-between items-center gap-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="w-full md:w-96 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name, or Role..." class="w-full bg-white dark:bg-slate-900 border-none rounded-2xl px-12 py-3.5 text-sm focus:ring-2 focus:ring-brand-500 shadow-inner font-bold text-slate-900 dark:text-white">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            
            <div class="flex items-center gap-4 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl {{ !request('role') ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">All Entities</a>
                @foreach(['customer', 'vendor', 'admin'] as $role)
                    <a href="{{ route('admin.users.index', ['role' => $role]) }}" class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl {{ request('role') == $role ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">@ {{ $role }}</a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-800/30">
                        <th class="px-10 py-6">Operational Subject</th>
                        <th class="px-10 py-6">Registrar Record</th>
                        <th class="px-10 py-6 text-center">Security Status</th>
                        <th class="px-10 py-6 text-center">Marketing Opt-In</th>
                        <th class="px-10 py-6 text-right">Access Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all group/row">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-50 to-indigo-50 dark:from-slate-800 dark:to-slate-800 text-brand-600 dark:text-brand-400 flex items-center justify-center font-black text-xl shadow-inner group-hover/row:scale-110 transition-transform">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-slate-900 dark:text-white">{{ $user->name }}</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-400">{{ $user->email }}</span>
                                    <div class="flex gap-1.5 mt-1.5">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 py-0.5 rounded bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[8px] font-black uppercase tracking-widest">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-10 py-8">
                            <div class="flex flex-col items-start gap-1">
                                <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ $user->created_at->format('d M, Y') }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </td>

                        <td class="px-10 py-8 text-center">
                            @php
                                $statusColors = [
                                    'active'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                    'inactive' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                    'suspended'=> 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800/50',
                                    'banned'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800/50',
                                ];
                                $colorClass = $statusColors[$user->status] ?? 'bg-slate-100 text-slate-500';
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $colorClass }}">
                                {{ $user->status }}
                            </span>
                        <td class="px-10 py-8 text-center text-xs font-black uppercase tracking-widest text-slate-500">
                            @if($user->marketing_opt_in)
                                <span class="text-brand-500 flex items-center justify-center gap-1.5 animate-pulse">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Enrolled
                                </span>
                            @else
                                <span class="opacity-40 italic">Opt-Out</span>
                            @endif
                        </td>

                        <td class="px-10 py-8 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-60 group-hover/row:opacity-100 transition-all duration-300">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="w-10 h-10 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all" title="Inspect Dossier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all" title="Modify Access">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Purge account database record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all" title="Terminate Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-200 mb-8 animate-pulse">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.985-7C14 5 13.5 8 16 11c1 1 1 2.185 1.015 3.324A11.956 11.956 0 0021 12a11.95 11.95 0 01-3.343 6.657z"></path></svg>
                                </div>
                                <h4 class="text-xl font-black text-slate-300 dark:text-slate-700 uppercase tracking-[0.3em]">No Ecosystem Subjects</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-10 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>

@endsection
