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
                <a href="{{ route('admin.register') }}" class="btn btn-primary">+ Add New User</a>
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
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive-lg ">
                <table id="procurementTable" class="table table-bordered table-striped">
                    <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $user->username }}</strong></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                                    </span>
                                </td>
                                <td>{{ $user->department->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Edit -->
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="bx bx-pencil">Edit</i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bx bx-trash">Delete</i>
                                            </button>
                                        </form>

                                        <!-- Permissions -->
                                        <a href="{{ route('users.edit-permissions', $user) }}"
                                            class="btn btn-sm btn-primary" title="Assign Permissions">
                                            <i class="bx bx-user-check">Permission</i>
                                        </a>
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

    {{-- <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $user->username }}</strong></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                                    </span>
                                </td>
                                <td>{{ $user->department->name ?? '-' }}</td>
                                <td>
                                    @if ($user->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Edit -->
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="bx bx-pencil"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                        <!-- Permissions -->
                                        <a href="{{ route('users.edit-permissions', $user) }}"
                                            class="btn btn-sm btn-primary" title="Assign Permissions">
                                            <i class="bx bx-user-check"></i>
                                        </a>
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
        </div>
    </div> --}}
@endsection
