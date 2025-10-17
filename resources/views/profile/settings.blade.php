@extends('master')

@section('title', 'Profile Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Profile Settings</h2>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Profile Picture --}}
                <div class="text-center mb-4">
                    <img src="{{ Auth::user()->profile_picture
                        ? asset('uploads/profile_pictures/' . Auth::user()->profile_picture)
                        : asset('assets/img/avatars/1.png') }}"
                        alt="Profile Picture" class="rounded-circle border" width="120" height="120">
                    <div class="mt-2">
                        <input type="file" name="profile_picture"
                            class="form-control @error('profile_picture') is-invalid @enderror">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Change --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-download vip-btn">
                        <i class="bi bi-arrow-repeat"></i> Update Profile
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-light vip-btn">
                        <i class="bi bi-x-octagon"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
