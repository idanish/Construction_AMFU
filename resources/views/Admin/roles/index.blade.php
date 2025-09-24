
@extends('master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Roles List</h4>
            <a href="{{ route('roles.create') }}" class="btn btn-primary vip-btn">
               <i class="bi bi-plus-circle"></i> Create New Role
            </a>
        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if ($roles->isEmpty())
                    <p class="text-center text-muted">No roles found.</p>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Role Name</th>

                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($roles as $role)
                                    <tr>
                                        <td><strong>{{ $role->name }}</strong></td>

                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('roles.edit', $role->id) }}"
                                                    class="btn btn-info vip-btn me-2">
                                                    <i class="bi bi-pencil-square"></i> Edit

                                                </a>

                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                    onsubmit="return confirm('Kya aap is role ko delete karna chahte hain?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger vip-btn">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
