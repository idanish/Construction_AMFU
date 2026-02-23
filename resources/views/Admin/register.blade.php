@extends('master')
@section('title', 'Add New User')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-add-user icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                <h2>Add New User</h2>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('admin.user-management') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left-circle"></i> Go Back
            </a>
        </div>
    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="main-card mb-3 ">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.register.store') }}">
            @csrf

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}" required>
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Full Name --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <label class="form-label">Assign Department</label>
                <select name="departments[]" class="form-select @error('departments') is-invalid @enderror" multiple>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}"
                        {{ (collect(old('departments'))->contains($dept->id)) ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
                @error('departments')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Hold Ctrl (Windows) / Command (Mac) to select multiple
                    departments.</small>
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label">Assign Roles</label>
                <select name="roles[]" multiple class="form-select @error('roles') is-invalid @enderror" required>

                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ collect(old('roles'))->contains($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach

                </select>

                @error('roles')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Approval Level --}}
            <div class="mb-3" id="approval_level_field">
                <label class="form-label">Assign Approval Level</label>
                <select name="approval_level_id" id="approval_level_select"
                    class="form-select @error('approval_level_id') is-invalid @enderror">
                    <option value="">-- Select Approval Level --</option>
                    @foreach ($approvalLevels as $level)
                    <option value="{{ $level->id }}" {{ old('approval_level_id') == $level->id ? 'selected' : '' }}>
                        ({{ $level->department->name ?? 'N/A' }}) Level {{ $level->sequence }}: {{ $level->name }}
                    </option>
                    @endforeach
                </select>
                @error('approval_level_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="vip-btn btn-submit">
                <i class="bi bi-check-lg"></i> Create User
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Departments
    $('select[name="departments[]"]').select2({
        placeholder: "Select Departments",
        allowClear: true,
        theme: 'bootstrap4',
        width: '100%'
    });

    // Roles
    $('select[name="roles[]"]').select2({
        placeholder: "Select Roles",
        allowClear: true,
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>

@endsection