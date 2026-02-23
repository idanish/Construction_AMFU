@extends('master')
@section('title', 'User Management')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit"></i>
            </div>
            <div class="h4 m-0">
                Users Management
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('admin.register') }}" class="btn btn-download vip-btn">
                <i class="bi bi-plus-circle"></i> Create User
            </a>
        </div>
    </div>
</div>
<br>
{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<br>
<div class="table-responsive">
    <table id="usersTable" class="table datatable table-bordered table-striped table-sm w-100">
        <thead>
            <tr>
                <th class="" style="width: 5%;">S.No</th>
                <!-- <th class="" style="width: 15%;">Username</th> -->
                <th class="" style="width: 15%;">Name</th>
                <th class="" style="width: 25%;">Email</th>
                <th class="" style="width: 10%;">Role</th>
                <th class="" style="width: 10%;">Department</th>
                <th class="" style="width: 10%;">Level</th>
                <th class="" style="width: 5%;">Status</th>
                <th class="text-center" style="width: 5%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $key => $user)
            <tr>
                <td class="text-break small">{{ $key + 1 }}</td>
                <!-- <td class="">{{ $user->username }}</td> -->
                <td class=" small">{{ $user->name }}</td>
                <td class=" small">{{ $user->email }}</td>
                <td class="text-break small">
                    @forelse($user->roles as $role)
                        <span class="badge bg-info mb-1 d-inline-block">{{ $role->name }}</span>
                    @empty
                        <span>-</span>
                    @endforelse
                </td>
                <td class="text-break small">
                    @forelse($user->departments as $dept)
                    <span class="badge bg-primary">{{ $dept->name }}</span>
                    @empty
                    <span>-</span>
                    @endforelse
                </td>

                <td class="text-break small">{{ $user->approvalLevel?->name }}</td>

                <td class="small">
                    @if ($user->status == 1)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td class="text-center small">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" type="button" id="dropdownMenuButton{{ $user->id }}"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                            @can('update-user')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                </a>
                            </li>
                            @endcan

                            @can('delete-user')
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i> Delete
                                    </button>
                                </form>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection