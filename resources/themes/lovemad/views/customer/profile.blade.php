@extends('layouts.customer')
@section('title', 'Profile Settings')

@section('customer_content')
<div class="bg-white shadow-sm mb-4 p-4 border-bottom d-flex justify-content-between align-items-center" style="border-radius: 4px;">
    <h5 class="fw-bold mb-0"><i class="bi bi-person-gear text-primary me-2"></i>Profile Settings</h5>
    <span class="text-muted small">Update your account information</span>
</div>

<div class="bg-white shadow-sm p-4 col-12 col-xl-10" style="border-radius: 4px;">
    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        
        <h6 class="fw-bold mb-3 pb-2 border-bottom">Personal Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="name" class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required 
                       class="form-control bg-light border-0 shadow-none @error('name') is-invalid @enderror" 
                       placeholder="Your full name" style="padding: 10px 14px; font-size: 13px;">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required 
                       class="form-control bg-light border-0 shadow-none @error('email') is-invalid @enderror" 
                       placeholder="you@email.com" style="padding: 10px 14px; font-size: 13px;">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">Phone Number</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" 
                       class="form-control bg-light border-0 shadow-none @error('phone') is-invalid @enderror" 
                       placeholder="+88017xxxxxxxx" style="padding: 10px 14px; font-size: 13px;">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <h6 class="fw-bold mt-4 mb-3 pb-2 border-bottom">Security Update</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="new_password" class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">New Password (Optional)</label>
                <input id="new_password" name="new_password" type="password" 
                       class="form-control bg-light border-0 shadow-none @error('new_password') is-invalid @enderror" 
                       placeholder="••••••••" style="padding: 10px 14px; font-size: 13px;">
                @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="col-md-6">
                <label for="new_password_confirmation" class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">Confirm New Password</label>
                <input id="new_password_confirmation" name="new_password_confirmation" type="password" 
                       class="form-control bg-light border-0 shadow-none" 
                       placeholder="••••••••" style="padding: 10px 14px; font-size: 13px;">
            </div>
        </div>

        <div class="bg-light p-3 rounded mb-4 border" style="max-width: 400px;">
            <label for="current_password" class="form-label small fw-bold text-danger text-uppercase mb-1" style="font-size: 11px;">Current Password (Required to save)</label>
            <input id="current_password" name="current_password" type="password" required
                   class="form-control border-0 shadow-none @error('current_password') is-invalid @enderror" 
                   placeholder="••••••••" style="padding: 10px 14px; font-size: 13px;">
            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 2px; font-size: 14px;">
            Update Profile & Security
        </button>
    </form>
</div>
@endsection
