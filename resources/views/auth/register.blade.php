@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-center bg-white border-0">
                    <h3 class="fw-bold text-primary mb-0">✨ Create Your Account</h3>
                    <p class="text-muted">Join us and manage your finance module with ease</p>
                </div>

                <div class="card-body px-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input id="name" type="text"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                placeholder="Enter your full name">
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input id="email" type="email"
                                class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="example@email.com">
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input id="password" type="password"
                                class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="Enter strong password">
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg rounded-3"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Re-enter password">
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                                🚀 Register
                            </button>
                        </div>

                        {{-- Already have account --}}
                        <div class="text-center">
                            <p class="text-muted mb-0">Already have an account?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
