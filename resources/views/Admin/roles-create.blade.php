@extends('master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Roles /</span> Create New Role
        </h4>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="roleName" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="roleName" name="name"
                                    placeholder="Enter role name" required>
                            </div>

                            <h5 class="card-title mt-4">Permissions</h5>
                            <p class="card-subtitle text-muted mb-4">Is role ke liye permissions select karen.</p>

                            @php
                                $groupedPermissions = $permissions->groupBy(function ($item) {
                                    $parts = explode('-', $item->name);

                                    $groupName = end($parts);

                                    if ($groupName === 'users' && in_array('manage', $parts)) {
                                        return 'Users';
                                    }
                                    if ($groupName === 'permissions' && in_array('manage', $parts)) {
                                        return 'Permissions';
                                    }
                                    if ($groupName === 'settings' && in_array('profile', $parts)) {
                                        return 'Profile Settings';
                                    }
                                    if ($groupName === 'settings' && in_array('site', $parts)) {
                                        return 'Site Settings';
                                    }
                                    if ($groupName === 'backup') {
                                        return 'Backup';
                                    }
                                    if ($groupName === 'reports' && in_array('request', $parts)) {
                                        return 'Request Reports';
                                    }
                                    if ($groupName === 'reports' && in_array('finance', $parts)) {
                                        return 'Finance Reports';
                                    }
                                    if ($groupName === 'reports' && in_array('audit', $parts)) {
                                        return 'Audit Reports';
                                    }
                                    if ($groupName === 'logs' && in_array('activity', $parts)) {
                                        return 'Activity Logs';
                                    }

                                    return ucwords($groupName);
                                });
                            @endphp

                            <div class="row">
                                @foreach ($groupedPermissions as $groupName => $groupPermissions)
                                    <div class="col-12 mb-3">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-capitalize">{{ $groupName }}</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input group-checkbox" type="checkbox"
                                                        id="selectAll{{ str_replace(' ', '', $groupName) }}">
                                                    <label class="form-check-label"
                                                        for="selectAll{{ str_replace(' ', '', $groupName) }}">
                                                        Select All
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox"
                                                                    type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    id="{{ $permission->name }}">
                                                                <label class="form-check-label"
                                                                    for="{{ $permission->name }}">
                                                                    {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="vip-btn btn-submit">
                                <i class="bi bi-check-lg"></i> Save
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const groupCheckboxes = document.querySelectorAll('.group-checkbox');

            groupCheckboxes.forEach(groupCheckbox => {
                const permissionCheckboxes = groupCheckbox.closest('.card').querySelectorAll(
                    '.permission-checkbox');

                groupCheckbox.addEventListener('change', function() {
                    permissionCheckboxes.forEach(permissionCheckbox => {
                        permissionCheckbox.checked = this.checked;
                    });
                });

                permissionCheckboxes.forEach(permissionCheckbox => {
                    permissionCheckbox.addEventListener('change', function() {
                        const allChecked = Array.from(permissionCheckboxes).every(cb => cb
                            .checked);
                        groupCheckbox.checked = allChecked;
                    });
                });
            });
        });
    </script>
@endsection
