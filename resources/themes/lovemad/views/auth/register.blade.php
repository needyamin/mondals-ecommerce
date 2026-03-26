@extends('layouts.app')

@section('title', 'Create account')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active">Register</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="bg-white shadow-sm p-4 p-md-5" style="border-radius: 4px; border-top: 3px solid var(--lm-primary);">
                <h1 class="h4 fw-bold mb-1" style="color: var(--lm-text);">Create account</h1>
                <p class="small text-muted mb-4">Register to track orders and checkout faster.</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 py-2 mb-4" style="font-size: 13px; border-radius: 4px;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Full name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Min. 8 characters">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Confirm</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Repeat">
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="form-check-label small" for="terms">I agree to the terms and privacy policy.</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 2px;">Create account</button>
                </form>

                <p class="text-center small text-muted mt-4 mb-1">
                    Want to sell?
                    <a href="{{ route('register.vendor') }}" class="fw-bold">Become a seller</a>
                </p>
                <p class="text-center small text-muted mb-0">
                    Already registered?
                    <a href="{{ route('login') }}" class="fw-bold">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
