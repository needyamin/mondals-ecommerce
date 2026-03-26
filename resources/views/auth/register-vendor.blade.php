@extends('layouts.app')

@section('title', 'Become a seller')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white font-heading tracking-tight mb-2">
            Sell on <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-500">Mondals</span>
        </h1>
        <p class="text-slate-500 dark:text-slate-400">Create your account and submit your store for admin approval.</p>
    </div>

    <div class="max-w-xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-8 md:p-10">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-sm">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.vendor') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="store_name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Store name</label>
                    <input id="store_name" name="store_name" type="text" value="{{ old('store_name') }}" required
                           class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white"
                           placeholder="Your shop name">
                </div>
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">About your store (optional)</label>
                    <textarea id="description" name="description" rows="3"
                              class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white resize-none"
                              placeholder="What do you sell?">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                               class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white"
                               placeholder="+880…">
                    </div>
                    <div>
                        <label for="city" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}" required
                               class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label for="country" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Country</label>
                    <input id="country" name="country" type="text" value="{{ old('country', 'Bangladesh') }}" required
                           class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                </div>
                <div class="border-t border-slate-200 dark:border-slate-700 pt-5 space-y-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Account</p>
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Your name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                               class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Password</label>
                            <input id="password" name="password" type="password" required
                                   class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Confirm</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary text-sm text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <input id="terms" name="terms" type="checkbox" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" {{ old('terms') ? 'checked' : '' }} required>
                    <label for="terms" class="text-sm text-slate-600 dark:text-slate-400">I agree to the seller terms and privacy policy.</label>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-2xl text-sm font-bold text-white bg-primary hover:bg-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 focus:ring-primary transition shadow-lg shadow-primary/25">
                    Submit application
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                Shopping only?
                <a href="{{ route('register') }}" class="font-bold text-primary hover:text-purple-600">Create a customer account</a>
            </p>
            <p class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-purple-600">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
