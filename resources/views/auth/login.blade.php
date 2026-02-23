<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.png')}}" />
    
    <style>
        body { background-color: #f8f9fa; }

        .image-column {
            background: url("{{asset('assets/img/backgrounds/background-login.jpg')}}") no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            padding: 0;
            display: none;
        }

        .login-form-column {
            height: 100vh;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .image-column { display: block; }
            .login-form-column { height: 100vh; }
        }

        .login-form-card { box-shadow: none; border: none; width: 100%; background-color: transparent; }
        .card-wrapper { max-width: 450px; width: 90%; }

        .btn-custom { background-color: #ffab00; border-color: #ffab00; color: #212529; }
        .btn-custom:hover { background-color: #e69a00; border-color: #e69a00; color: #212529; }

        .form-control:focus {
            border-color: #ffab00;
            box-shadow: 0 0 0 0.25rem rgba(255, 171, 0, 0.25);
        }

        /* Password Toggle Specific CSS */
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 10;
        }

        .form-control#password {
            padding-right: 45px; /* Space for the icon */
        }
    </style>
</head>
<body>

<div class="row g-0">
    <div class="col-md-8 image-column"></div>

    <div class="col-12 col-md-4 login-form-column">
        <div class="card-wrapper">
            <div class="text-center mb-4">
                <img src="{{asset('assets/img/logo/logo.png')}}" alt="Logo" width="180px">
            </div>
            <div class="card login-form-card px-4">
                <div class="card-body">

                <div class="card-body">
                    
                    @auth
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <div class="rounded-circle bg-light d-inline-block p-3 mb-3" style="border: 2px solid #ffab00;">
                                    <i class="bi bi-person-check-fill" style="font-size: 3rem; color: #ffab00;"></i>
                                </div>
                                <h3 class="fw-bold">Welcome Back!</h3>
                                <p class="text-muted">You are already logged in as <br> 
                                <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                                </p>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-custom btn-lg">
                                    Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-muted text-decoration-none shadow-none">
                                        Not you? Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <h2 class="card-title text-center mb-5" style="color: #ffab00;">Login to your Account</h2>

                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus
                                placeholder="name@amfu.net">
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="password-wrapper">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password" name="password" required>
                                
                                <span class="toggle-password-icon" id="eyeWrapper">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                            
                            @error('password')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-custom btn-lg">{{ __('Login') }}</button>
                        </div>

                        <!-- @if (Route::has('password.request'))
                        <div class="text-center mt-4">
                            <a class="text-decoration-none" href="{{ route('password.request') }}" style="color: #ffab00;">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                        @endif -->

                    </form>
                    @endauth
                </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const passwordField = document.querySelector('#password');
    const eyeWrapper = document.querySelector('#eyeWrapper');
    const eyeIcon = document.querySelector('#eyeIcon');

    eyeWrapper.addEventListener('click', function() {
        // Toggle type
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle Bootstrap Icon classes
        if (type === 'text') {
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>

</body>
</html>