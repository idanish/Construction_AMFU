@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Budget</h2>

    <form action="{{ route('finance.budgets.store') }}" method="POST">
        @csrf

        {{-- Department --}}
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select">
                <option value="">Select Department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Allocated --}}
        <div class="mb-3">
            <label for="allocated" class="form-label">Allocated Amount</label>
            <input type="number" name="allocated" class="form-control" id="allocated" value="{{ old('allocated') }}">
            @error('allocated')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Spent --}}
        <div class="mb-3">
            <label for="spent" class="form-label">Spent Amount</label>
            <input type="number" name="spent" class="form-control" id="spent" value="{{ old('spent', 0) }}">
            @error('spent')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Balance --}}
        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" name="balance" class="form-control" id="balance" value="{{ old('balance', 0) }}">
            @error('balance')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @endif

        <button type="submit" class="btn btn-success">Save Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
