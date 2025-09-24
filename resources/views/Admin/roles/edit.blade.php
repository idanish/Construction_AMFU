@extends('master')
@section('title', 'Edit Role')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Edit Role</h4>
            <a href="{{ route('roles.show') }}" class="btn btn-secondary vip-btn">
                <i class='bx bx-arrow-back'></i> Back
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Kuch errors hain.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
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
                    @foreach ($permissions as $category => $perms)
                        <h5 class="mt-3">{{ ucfirst($category) }} Permissions</h5>
                        <div class="row">
                            @foreach ($perms as $permission)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="form-check-input" id="perm_{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-info text-dark vip-btn">
                        <i class="bi bi-arrow-repeat"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
