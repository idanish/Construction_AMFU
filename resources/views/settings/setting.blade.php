@extends('master')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">⚙️ General Settings</h5>
        </div>
        <div class="card-body">

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('settings.updateLogo') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Site Name -->
                <div class="mb-3">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control"
                        value="{{ old('site_name', 'Construction-AMFU') }}">
                </div>

                <!-- Logo Upload -->
                <div class="mb-3">
                    <label class="form-label">Logo Upload</label><br>

                    {{-- Show Current Logo --}}
                    @if (!empty($settings->logo))
                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Current Logo" height="80"
                            class="mb-3 d-block">
                    @endif

                    {{-- Upload New Logo --}}
                    <input type="file" name="logo" class="form-control">
                </div>

                <!-- Email Settings -->
                <div class="mb-3">
                    <label class="form-label">System Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', 'admin@example.com') }}">
                </div>

                <!-- Notification Preferences -->
                <div class="mb-3">
                    <label class="form-label">Notifications</label><br>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Enable Email Notifications</label>
                    </div>
                </div>

                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-check-lg"></i> Submit
                </button>
            </form>
        </div>
    </div>

@endsection
