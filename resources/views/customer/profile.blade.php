@extends('layouts.customer')
@section('title', 'Profile Settings')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col space-y-2">
        <h2 class="text-2xl font-heading font-bold text-slate-900 dark:text-white text-3xl">Profile Settings</h2>
        <p class="text-slate-500 dark:text-slate-400 font-light text-sm">Update your account information and security settings.</p>
    </div>

    <!-- Personal Information Card -->
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-10 shadow-sm relative overflow-hidden">
        <div class="absolute -top-[10%] -right-[5%] w-[150px] h-[150px] bg-primary/5 blur-[50px] rounded-full pointer-events-none"></div>
        
        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-8 relative z-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required 
                           class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                           placeholder="Your full name">
                    @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1 text-xs">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required 
                           class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                           placeholder="you@email.com">
                    @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Phone Number</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" 
                           class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                           placeholder="+88017xxxxxxxx">
                    @error('phone') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-6">Security Update</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">New Password (Optional)</label>
                        <input id="new_password" name="new_password" type="password" 
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                               placeholder="••••••••">
                        @error('new_password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Confirm New Password</label>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" 
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                               placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- Current Password -->
            <div class="pt-4 max-w-sm">
                <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 ml-1">Current Password (Required to save changes)</label>
                <input id="current_password" name="current_password" type="password" 
                       class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-primary focus:border-primary text-sm transition-colors text-slate-900 dark:text-white" 
                       placeholder="••••••••">
                @error('current_password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6">
                <button type="submit" class="inline-flex justify-center items-center py-4 px-10 border border-transparent rounded-2xl shadow-xl shadow-primary/30 text-sm font-bold text-white bg-primary hover:bg-primaryHover hover:scale-[1.02] transform transition-all active:scale-95">Update Profile & Security</button>
            </div>
        </form>
    </div>
</div>
@endsection
