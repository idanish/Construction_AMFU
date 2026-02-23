@extends('master')

@section('title', 'Edit User')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                <h2>Edit User</h2>
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

<div class="main-card mb-3">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PATCH')

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username', $user->username) }}" required>
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Full Name --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <label class="form-label">Assign Departments</label>
                <div class="@error('departments') is-invalid @enderror">
                    @foreach($departments as $dept)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="departments[]" value="{{ $dept->id }}"
                                {{ (collect(old('departments'))->contains($dept->id) || $user->departments->contains($dept->id)) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $dept->name }}</label>
                        </div>
                    @endforeach
                    @error('departments')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            {{-- Role --}}
            <div class="mb-3">
                <label class="form-label">Assign Role</label>
                <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                    <option value="" disabled>-- Select Role --</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" 
                        {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
                @error('role_id')
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
                    <option value="{{ $level->id }}" 
                        {{ old('approval_level_id', $user->approval_level_id) == $level->id ? 'selected' : '' }}>
                        ({{ $level->department->name ?? 'N/A' }}) Level {{ $level->sequence }}: {{ $level->name }}
                    </option>
                    @endforeach
                </select>
                @error('approval_level_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="vip-btn btn-submit">
                <i class="bi bi-check-lg"></i> Update User
            </button>
        </form>
    </div>
</div>
@endsection