<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Mondals Ecommerce</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-darkbg text-slate-900 dark:text-white font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-indigo-500/20 blur-[120px] rounded-full mix-blend-screen"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 blur-[120px] rounded-full mix-blend-screen mix-blend-color-dodge"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDM5LjVoNDBWNDBIMHptMzkuNSAwVjBoLjV2NDB6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+PC9zdmc+')] mix-blend-overlay"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-4xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden">
        
        <div class="flex flex-col md:flex-row">
            <!-- Left Side: Visual -->
            <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-12 flex-col justify-between text-white relative">
                <div class="relative z-10">
                    <a href="/" class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center font-heading font-extrabold text-2xl mb-10 hover:scale-110 transition-transform">M</a>
                    <h1 class="text-4xl font-heading font-bold leading-tight mb-6">Mondal's <br>E-Commerce</h1>
                    <p class="text-indigo-100 text-sm font-light leading-relaxed">Sign in to access your dashboard, track current orders, and explore personalized deals waiting just for you.</p>
                </div>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex items-center space-x-4 text-xs font-medium">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-indigo-100">Verified Secure Environment</span>
                    </div>
                </div>

                <!-- Abstract shape in bg of left side -->
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 blur-3xl rounded-full -mb-20 -mr-20"></div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-3/5 px-6 py-10 md:px-14 md:py-16">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold font-heading tracking-tight mb-2">Welcome Back</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Please enter your credentials to continue.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-8 p-4 rounded-2xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-sm">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus 
                                   class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white placeholder-slate-400" 
                                   placeholder="you@email.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2 ml-1">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Password</label>
                            <a href="#" class="text-xs font-bold text-primary hover:text-indigo-500 transition-colors">Forgot?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input id="password" name="password" type="password" required 
                                   class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white placeholder-slate-400" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 dark:border-slate-600 rounded bg-slate-50 dark:bg-slate-800 cursor-pointer">
                        <label for="remember" class="ml-2 block text-xs font-bold text-slate-500 dark:text-slate-400 cursor-pointer">
                            Keep me signed in
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-indigo-500/30 text-sm font-bold text-white bg-primary hover:bg-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all hover:scale-[1.02] transform active:scale-95">
                            Access Terminal &rarr;
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm">
                    <p class="text-slate-500 dark:text-slate-400">
                        New Member? 
                        <a href="{{ route('register') }}" class="font-bold text-primary hover:text-indigo-500 transition-colors">Create Account</a>
                    </p>
                </div>
                
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-4 text-center">Development Lab Access</span>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="flex items-center justify-between text-[10px] p-2.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold uppercase">Admin</span>
                            <code class="text-slate-600 dark:text-slate-300">admin@mondals.com <span class="mx-1 opacity-30">|</span> password</code>
                        </div>
                        <div class="flex items-center justify-between text-[10px] p-2.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold uppercase">Vendor</span>
                            <code class="text-slate-600 dark:text-slate-300">vendor1@mondals.com <span class="mx-1 opacity-30">|</span> password</code>
                        </div>
                        <div class="flex items-center justify-between text-[10px] p-2.5 rounded-xl bg-amber-50/50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50">
                            <span class="text-amber-600 dark:text-amber-400 font-bold uppercase">Customer</span>
                            <code class="text-slate-600 dark:text-slate-300">customer1@mondals.com <span class="mx-1 opacity-30">|</span> password</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
