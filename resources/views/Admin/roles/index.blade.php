@extends('master')
@section('title', 'Roles')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Roles</div>
        </div>
        <div class="page-title-actions">
            @can('create-role')
            <a href="{{ route('roles.create') }}" class="btn btn-download mb-3 vip-btn">
                <i class="bi bi-plus-circle"></i> Create New Role
            </a>
            @endcan
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


    @if ($roles->isEmpty())
    <p class="text-center text-muted">No roles found.</p>
    @else

<div class="table-responsive">
    <table class="table datatable table-bordered table-striped table-sm w-100">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
                <tr>
                    <th>S.No</th>
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