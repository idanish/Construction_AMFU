@extends('../master')
@section('title', 'Create New Role')    
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            ⬅️ Back to Dashboard
        </a>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            ➕ Add New Role
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Create New Role</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
                </div>
                <button type="submit" class="btn btn-success">Create Role</button>
            </form>
        </div>
    </div>
</div>
@endsection
