@extends('../master')
@section('title', 'Edit User')

@section('content')
    <div class="container">
        <h2 class="mb-4">✏️ Edit User</h2>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-select">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">-- Select Department --</option>
                    {{-- @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ $user->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach --}}
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
@endsection
