@extends('master')

@section('title', 'Departments Management')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Departments Management</div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                @can('create-department')
                <a href="{{ route('departments.create') }}" class="btn btn-download mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Create Department
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>


{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert"> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert"> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


<div class="table-responsive mt-3">
    <table class="table datatable table-bordered table-striped table-sm w-100">
        <thead class="table-light">
            <tr>
                <th width="5%">S.NO</th>
                <th width="20%">Name</th>
                <th width="35%">Description</th>
                <th width="10%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $key => $dept)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td class="">{{ $dept->name }}</td>
                <td>{{ $dept->description }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        @can('update-department')
                        <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-warning vip-btn"
                            title="Edit">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        @endcan

                        @can('delete-department')
                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this department?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm vip-btn">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-3">No departments found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection