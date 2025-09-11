@extends('../master')
@section('title', 'Register New Admin')
@section('content')
    <form method="POST" action="{{ route('admin.register.store') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        {{-- Department Selection --}}
        <div class="mb-3">
            <label class="form-label">Assign Department</label>
            <select name="department_id" class="form-select" required>
                <option value="" disabled selected>-- Select Department --</option>
                {{-- @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach --}}
            </select>
        </div>

        {{-- Role Selection --}}
        <div class="mb-3">
            <label class="form-label">Assign Role</label>
            <select name="role_id" class="form-select" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">🚀 Register User</button>
    </form>
@endsection
