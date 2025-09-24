@extends('master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Department</h2>

        {{-- Go Back Button --}}
        <a href="{{ route('departments.index') }}" class="btn btn-secondary vip-btn">
            <i class="bi bi-arrow-left-circle"></i> Go Back
        </a>

        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" name="name" class="form-control" id="name"
                    value="{{ old('name', $department->name) }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description">{{ old('description', $department->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type= "submit"class="btn btn-info text-dark vip-btn">
                <i class="bi bi-arrow-repeat"></i> Update
            </button>
            <button type ="submit"class="btn btn-outline-danger vip-btn">
                <i class="bi bi-x-octagon"></i> Cancel
            </button>

        </form>
    </div>
@endsection
