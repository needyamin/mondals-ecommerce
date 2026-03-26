@extends('layouts.app')

@section('title', 'Become a seller')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active">Sell on Mondals</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="bg-white shadow-sm p-4 p-md-5" style="border-radius: 4px; border-top: 3px solid var(--lm-primary);">
                <h1 class="h4 fw-bold mb-1" style="color: var(--lm-text);">Become a seller</h1>
                <p class="small text-muted mb-4">Create your account and submit your store for approval.</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 py-2 mb-4" style="font-size: 13px; border-radius: 4px;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.vendor') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="store_name" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Store name</label>
                        <input type="text" class="form-control" id="store_name" name="store_name" value="{{ old('store_name') }}" required placeholder="Your shop name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">About (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="What do you sell?">{{ old('description') }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+880…">
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Bangladesh') }}" required>
                    </div>
                    <hr class="my-4">
                    <p class="small fw-bold text-muted text-uppercase mb-3" style="font-size: 11px;">Account</p>
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Your name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 11px;">Confirm</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required {{ old('terms') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="terms">I agree to the seller terms and privacy policy.</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" style="border-radius: 2px;">Submit application</button>
                </form>

                <p class="text-center small text-muted mt-4 mb-0">
                    <a href="{{ route('register') }}" class="fw-bold">Customer sign up</a>
                    &nbsp;·&nbsp;
                    <a href="{{ route('login') }}" class="fw-bold">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
