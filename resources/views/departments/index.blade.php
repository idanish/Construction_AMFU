@extends('master')

@section('title', 'Departments Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Departments Management</h2>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add Department
        </a>
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

    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">S.NO</th>
                        <th width="20%">Name</th>
                        <th width="35%">Description</th>
                        <th width="15%">Created At</th>
                        <th width="15%">Updated At</th>
                        <th width="10%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $key => $dept) 
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $dept->name }}</td>
                            <td>{{ $dept->description }}</td>
                            <td>{{ $dept->created_at->format('d-m-Y') }}</td>
                            <td>{{ $dept->updated_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="bx bx-pencil"></i>
                                    </a>
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
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
    </div>
@endsection
