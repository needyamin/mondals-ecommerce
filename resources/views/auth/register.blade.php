<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Us | Mondals Ecommerce</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.marketing-head')
</head>
<body class="bg-slate-50 dark:bg-darkbg text-slate-900 dark:text-white font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-indigo-500/20 blur-[120px] rounded-full mix-blend-screen"></div>
        <div class="absolute bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 blur-[120px] rounded-full mix-blend-screen mix-blend-color-dodge"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDM5LjVoNDBWNDBIMHptMzkuNSAwVjBoLjV2NDB6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIi8+PC9zdmc+')] mix-blend-overlay"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-4xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden">
        
        <div class="flex flex-col md:flex-row min-h-[600px]">
            <!-- Left Side: Visual -->
            <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-12 flex-col justify-between text-white relative">
                <div class="relative z-10">
                    <a href="/" class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center font-heading font-extrabold text-2xl mb-10 hover:scale-110 transition-transform">M</a>
                    <h1 class="text-4xl font-heading font-bold leading-tight mb-6">Join the <br>Community</h1>
                    <p class="text-indigo-100 text-sm font-light leading-relaxed">Create your account to unlock exclusive member-only deals, early access to sales, and seamless order tracking across Bangladesh.</p>
                </div>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex items-center space-x-4 text-xs font-medium">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-indigo-100 italic">256-bit SSL Data Encryption</span>
                    </div>
                </div>

                <!-- Glow effect background -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 blur-3xl rounded-full -mt-20 -mr-20"></div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-3/5 px-8 py-12 md:px-14 md:py-16">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold font-heading tracking-tight mb-2">Create Account</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Become a member of Mondals Ecommerce.</p>
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

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Full Name</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus 
                                   class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white" 
                                   placeholder="e.g. Abdullah Mondal">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                                   class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white" 
                                   placeholder="contact@email.com">
                        </div>
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <input id="password" name="password" type="password" required 
                                       class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white" 
                                       placeholder="••••••••">
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Confirm</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" required 
                                       class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all text-slate-900 dark:text-white" 
                                       placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center pt-2">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-primary focus:ring-primary border-slate-300 dark:border-slate-600 rounded bg-slate-50 dark:bg-slate-800 cursor-pointer">
                        <label for="terms" class="ml-2 block text-xs font-bold text-slate-500 dark:text-slate-400 cursor-pointer">
                            I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-indigo-500/30 text-sm font-bold text-white bg-primary hover:bg-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all hover:scale-[1.02] transform active:scale-95">
                            Create Account &rarr;
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center text-sm">
                    <p class="text-slate-500 dark:text-slate-400">
                        Already a member? 
                        <a href="{{ route('login') }}" class="font-bold text-primary hover:text-indigo-500 transition-colors">Sign in to your account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @include('partials.marketing-body')
</body>
</html>
