<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="https://amfu.net/wp-content/uploads/2024/04/cropped-amfu-FAV-32x32.png" />
<style>
body {
    background-color: #f8f9fa;
}

.image-column {
    background: url("https://amfu.net/wp-content/uploads/2024/04/construction-silhouette-1.jpg") no-repeat center center fixed;
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
    .image-column {
        display: block;
    }

    .login-form-column {
        height: 100vh;
    }
}

.login-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.login-form-card {
    box-shadow: none;
    border: none;
    width: 100%;
    background-color: transparent;
}

.card-wrapper {
    max-width: 450px;
    width: 90%;
}


.btn-custom {
    background-color: #ffab00;
    border-color: #ffab00;
    color: #212529;
}

.btn-custom:hover {
    background-color: #e69a00;
    border-color: #e69a00;
    color: #212529;
}

.form-control:focus {
    border-color: #ffab00;
    box-shadow: 0 0 0 0.25rem rgba(255, 171, 0, 0.25);
}
</style>

</head>
<body>
    


<div class="row g-0">

    <div class="col-md-8 image-column">

    </div>

    <div class="col-12 col-md-4 login-form-column">

        <!-- Login Form -->
        <div class="card-wrapper">
            <div class="card login-form-card px-4">
                <div class="card-body">

                    <img src="https://amfu.net/wp-content/uploads/2024/07/cropped-amfu-for-web-new.png" alt="Logo"
                        class="card-image text-center mb-5" width="160px">

                    <h2 class="card-title text-center mb-5" style="color: #ffab00;">Login to your Account</h2>

                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">

                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus
                                placeholder="name@example.com">
                            @error('email')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-4">

                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password" name="password" required>
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
                            <a class="text-decoration-none" href="{{ route('password.request') }}"
                                style="color: #ffab00;">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                        @endif -->

                        @if (session('success'))
                        <div class="alert mt-4 d-none" id="alertMessage" role="alert">{{ session('success') }}
                        </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
