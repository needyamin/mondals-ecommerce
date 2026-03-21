@extends('layouts.admin')

@section('title', 'Manage Vendors')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight">Vendor Directory</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Review pending merchant applications and manage active sellers.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.vendors.export', request()->query()) }}" class="btn btn-outline bg-white dark:bg-darkpanel border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-medium px-4 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center shadow-sm transform hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.vendors.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-3 rounded-2xl font-bold shadow-xl shadow-brand-500/30 transition flex items-center hover:scale-105 active:scale-95 transform">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Add Merchant
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-darkpanel rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
            <form method="GET" action="{{ route('admin.vendors.index') }}" class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 pl-12 pr-4 py-2.5 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 shadow-sm" placeholder="Search by store name, user... ">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Store / Partner</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Joined On</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Rate</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Verification Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 border border-indigo-200 dark:border-indigo-800 flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ substr($vendor->store_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $vendor->store_name }}</p>
                                    <span class="text-xs text-slate-500 font-medium">{{ $vendor->user->name ?? 'Unassigned' }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 block">{{ $vendor->created_at->format('M d, Y') }}</span>
                            <span class="text-xs text-slate-400">{{ $vendor->created_at->diffForHumans() }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $vendor->commission_rate }}%</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border 
                                {{ $vendor->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50 dark:text-emerald-400' : '' }}
                                {{ $vendor->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50 dark:text-amber-400 pb-1' : '' }}
                                {{ $vendor->status === 'rejected' ? 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50 dark:text-rose-400' : '' }}
                            ">
                                @if($vendor->status === 'approved') 
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> 
                                @endif
                                {{ $vendor->status === 'pending' ? 'Pending Review' : ucfirst($vendor->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-900 dark:bg-white text-xs font-bold text-white dark:text-slate-900 hover:scale-105 transition-all shadow-md">
                                Details &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No Vendors in Database</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">You currently have no sellers registered to the platform.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($vendors) && $vendors->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            {{ $vendors->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endsection
