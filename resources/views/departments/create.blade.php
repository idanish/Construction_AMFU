@extends('master')

@section('title', 'Add Department')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Add Department</h2>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary vip-btn">
            <i class="bi bi-arrow-left-circle"></i> Go Back
        </a>
    </div>

    <div class="">
        <div class="card-body">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                {{-- Department Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                        rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="vip-btn btn-submit">
                        <i class="bi bi-check-lg"></i> Save
                    </button>
                    <a href="{{ route('departments.index') }}" class="btn btn-light vip-btn">
                        <i class="bi bi-x-octagon"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
