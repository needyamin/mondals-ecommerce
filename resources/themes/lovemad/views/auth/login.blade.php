@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active">Sign in</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="bg-white shadow-sm p-4 p-md-5" style="border-radius: 4px; border-top: 3px solid var(--lm-primary);">
                <h1 class="h4 fw-bold mb-1" style="color: var(--lm-text);">Sign in</h1>
                <p class="small text-muted mb-4">Use your registered email and password.</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 py-2 mb-4" style="font-size: 13px; border-radius: 4px;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 2px;">Sign in</button>
                </form>

                <p class="text-center small text-muted mt-4 mb-1">
                    New customer?
                    <a href="{{ route('register') }}" class="fw-bold">Create account</a>
                </p>
                <p class="text-center small text-muted mb-0">
                    Sell on Mondals?
                    <a href="{{ route('register.vendor') }}" class="fw-bold">Seller sign up</a>
                </p>
            </div>

            @if(config('app.debug'))
            <div class="mt-3 p-3 small text-muted" style="background: #fff; border-radius: 4px; font-size: 11px;">
                <strong class="text-uppercase">Debug</strong> — admin@mondals.com / vendor1@mondals.com / customer1@mondals.com — password: <code>password</code>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
