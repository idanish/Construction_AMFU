@extends('master')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Edit Budget</h2>

<form action="{{ route('budgets.update', $budget->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')


        {{-- Department --}}
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

        {{-- Allocated --}}
        <div class="mb-3">
            <label for="allocated" class="form-label">Allocated</label>
            <input type="number" name="allocated" class="form-control" id="allocated" 
                   value="{{ old('allocated', $budget->allocated) }}">
            @error('allocated')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Spent --}}
        <div class="mb-3">
            <label for="spent" class="form-label">Spent</label>
            <input type="number" name="spent" class="form-control" id="spent" 
                   value="{{ old('spent', $budget->spent) }}">
            @error('spent')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Balance (disabled) --}}
        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" class="form-control" id="balance" 
                   value="{{ old('balance', $budget->allocated - $budget->spent) }}" disabled>
        </div>

        {{-- Attachment --}}
        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment</label>
            <input type="file" name="attachment" id="attachment" class="form-control" 
                   accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx">

            @if($budget->attachment)
                <p class="mt-2">
                    Current File: 
                    <a href="{{ asset('storage/'.$budget->attachment) }}" target="_blank">View / Download</a>
                </p>
            @endif

            @error('attachment')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
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
                <input type="hidden" name="status" value="{{ $budget->status }}">
            @endif
        </div>

        {{-- Transaction No (disabled) --}}
        <!-- <div class="mb-3">
            <label for="transaction_no" class="form-label">Transaction No</label>
            <input type="text" class="form-control" name="transaction_no" value="{{ $budget->transaction_no }}" disabled>
        </div> -->

        <button type="submit" class="btn btn-success">Update Budget</button>
        <a href="{{ route('budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@endsection
