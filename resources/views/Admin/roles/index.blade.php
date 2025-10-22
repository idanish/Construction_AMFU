@extends('master')
@section('title', 'Roles')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Roles</h4>
        @can('create-role')
        <a href="{{ route('roles.create') }}" class="btn btn-download vip-btn">
            <i class="bi bi-plus-circle"></i> Create New Role
        </a>
        @endcan
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


    @if ($roles->isEmpty())
    <p class="text-center text-muted">No roles found.</p>
    @else

    <div class="table-responsive-lg ">
        <table id="procurementTable" class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
                <tr>
                    <th>No</th>
                    <th>Role Name</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $role->name }}</td>

                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <!-- Edit -->
                            @can('update-role')
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-download vip-btn"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            @endcan

                            <!-- Delete -->
                            @can('delete-role')
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this role?');"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger vip-btn" title="Delete">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection