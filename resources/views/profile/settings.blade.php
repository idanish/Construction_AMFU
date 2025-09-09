@extends('master')

@section('title', 'Profile Settings')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Profile Settings</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Picture -->
                    <div class="mb-3 text-center">
                        <img src="{{ Auth::user()->profile_picture
                            ? asset('uploads/profile_pictures/' . Auth::user()->profile_picture)
                            : asset('assets/img/avatars/1.png') }}"
                            alt="Profile Picture" class="rounded-circle mb-2" width="120" height="120">
                        <input type="file" name="profile_picture" class="form-control mt-2">
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', Auth::user()->name) }}">
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', Auth::user()->email) }}">
                    </div>

                    <!-- Password Change -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <!-- Role (Readonly, only admin can change in admin panel) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <input type="text" class="form-control"
                            value="{{ Auth::user()->roles->pluck('name')->implode(', ') }}" disabled>
                        <small class="text-muted">Only Admin can change your role</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection
