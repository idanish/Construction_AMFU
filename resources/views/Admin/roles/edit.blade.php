@extends('master')
@section('title', 'Edit Role')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">
                Edit Role
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                <a href="{{ route('roles.show') }}" class="btn btn-secondary mb-3 vip-btn">
                    <i class="bi bi-arrow-left-circle"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif



@php
$groupedPermissions = $permissions->groupBy(function ($item) {
$parts = explode('-', $item->name);
$groupName = end($parts);

if ($groupName === 'users' && in_array('manage', $parts)) return 'Users';
if ($groupName === 'permissions' && in_array('manage', $parts)) return 'Permissions';
if ($groupName === 'settings' && in_array('profile', $parts)) return 'Profile Settings';
if ($groupName === 'settings' && in_array('site', $parts)) return 'Site Settings';
if ($groupName === 'backup') return 'Backup';
if ($groupName === 'reports' && in_array('request', $parts)) return 'Request Reports';
if ($groupName === 'reports' && in_array('finance', $parts)) return 'Finance Reports';
if ($groupName === 'reports' && in_array('audit', $parts)) return 'Audit Reports';
if ($groupName === 'logs' && in_array('activity', $parts)) return 'Activity Logs';

return ucwords($groupName);
});
@endphp

<div class="">
    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Role Name --}}
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Permissions --}}
            <h5 class="card-title mt-4">Permissions</h5>
            <p class="card-subtitle text-muted mb-4">Update permissions for this role</p>

            <div class="row align-items-stretch">
                @foreach ($groupedPermissions as $groupName => $groupPermissions)

                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100 shadow-sm">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ $groupName }}</h6>
                            <div class="form-check m-0">
                                <input class="form-check-input group-checkbox" type="checkbox"
                                    id="selectAll{{ str_replace(' ', '', $groupName) }}">
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column gap-2">
                            @foreach ($groupPermissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                    value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>

                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                @endforeach
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-primary vip-btn">
                <i class="bi bi-arrow-repeat"></i> Update
            </button>
        </form>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');

    groupCheckboxes.forEach(groupCheckbox => {
        const permissionCheckboxes = groupCheckbox.closest('.card')
            .querySelectorAll('.permission-checkbox');

        groupCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(permissionCheckbox => {
                permissionCheckbox.checked = this.checked;
            });
        });

        permissionCheckboxes.forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', function() {
                const allChecked = Array.from(permissionCheckboxes)
                    .every(cb => cb.checked);
                groupCheckbox.checked = allChecked;
            });
        });

        // Auto check group if all permissions already checked
        const allCheckedInitially = Array.from(permissionCheckboxes)
            .every(cb => cb.checked);
        groupCheckbox.checked = allCheckedInitially;
    });
});
</script>

@endsection