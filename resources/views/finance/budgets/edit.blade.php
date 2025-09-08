@extends('master')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Edit Budget</h2>

    <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select">
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $budget->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="allocated" class="form-label">Allocated</label>
            <input type="number" name="allocated" class="form-control" id="allocated" value="{{ old('allocated', $budget->allocated) }}">
            @error('allocated')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="spent" class="form-label">Spent</label>
            <input type="number" name="spent" class="form-control" id="spent" value="{{ old('spent', $budget->spent) }}">
            @error('spent')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" name="balance" class="form-control" id="balance" value="{{ old('balance', $budget->balance) }}">
            @error('balance')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <select name="status" id="status" class="form-select">
                    <option value="Pending" {{ $budget->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ $budget->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $budget->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            @else
                <select class="form-select" disabled>
                    <option>{{ $budget->status }}</option>
                </select>
                <!-- Hidden input to submit status for normal users -->
                <input type="hidden" name="status" value="{{ $budget->status }}">
            @endif

            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@endsection
