@extends('layouts.app')
@section('content')
    @if (session('success'))
        <div class="alert alert-success text-center mb-3">
            {{ session('success') }}
        </div>
    @endif

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Background Video */
        .video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* pura cover kare */
            z-index: -1;
        }

        /* Dark overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            justify-content: flex-end;
            /* Right side form */
            align-items: center;
            padding-right: 80px;
        }

        .card {
            width: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.8s ease-in-out;
            background: rgba(255, 255, 255, 0.95);
        }

        .card-header {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            text-align: center;
            padding: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            border: none;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #357abd, #2b5f94);
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        /* Agar video ko chhota rakhna ho (contain mode) */
        .video-background.contain {
            object-fit: contain;
            /* pura dikhayega zoom nahi karega */
            background: #000;
            /* side spaces black rahenge */
        }

        /* Sirf desktop view me video show hoga */
        @media (max-width: 991px) {
            .video-background {
                display: none;
                /* mobile aur tablet me off */
            }

            body {
                background: url("{{ asset('assets/img/backgrounds/bg.jpg') }}") no-repeat center center fixed;
                background-size: cover;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <!-- Video Background -->
    <video autoplay muted loop class="video-background">
        <source src="{{ asset('assets/img/backgrounds/video.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay"></div>

    <!-- Login Form -->
    <div class="login-container">
        <div class="card">
            <div class="card-header">{{ __('Welcome Back! Login Your Account') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required>
                        @error('password')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">{{ __('Login') }}</button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
