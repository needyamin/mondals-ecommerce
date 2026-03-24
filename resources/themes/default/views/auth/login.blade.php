@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-2">
            Sign <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">in</span>
        </h1>
        <p class="text-slate-500 dark:text-slate-400">Welcome back — use your account email and password.</p>
    </div>

    <div class="max-w-md mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8 md:p-10">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm text-slate-900 dark:text-white placeholder-slate-400"
                           placeholder="you@example.com">
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Password</label>
                    </div>
                    <input id="password" name="password" type="password" required
                           class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm text-slate-900 dark:text-white"
                           placeholder="••••••••">
                </div>
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary bg-slate-50 dark:bg-slate-800">
                    <label for="remember" class="ml-2 text-sm text-slate-600 dark:text-slate-400">Remember me</label>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-2xl text-sm font-bold text-white bg-primary hover:bg-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 focus:ring-primary transition shadow-lg shadow-primary/25">
                    Sign in
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                New here?
                <a href="{{ route('register') }}" class="font-bold text-primary hover:text-purple-600 dark:hover:text-indigo-400 transition-colors">Create an account</a>
            </p>
        </div>

        @if(config('app.debug'))
        <div class="mt-8 p-4 rounded-2xl bg-slate-100/80 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-[11px] text-slate-500 dark:text-slate-400">
            <span class="font-bold uppercase tracking-wider text-slate-400 block mb-2">Debug — test accounts</span>
            <p class="font-mono">admin@mondals.com · vendor1@mondals.com · customer1@mondals.com — password: <code class="text-primary">password</code></p>
        </div>
        @endif
    </div>
</div>
@endsection
