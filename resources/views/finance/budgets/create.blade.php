@extends('master')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Add New Budget</h2>

    <form action="{{ route('finance.budgets.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount" value="{{ old('amount') }}">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status: hamesha Pending for normal users --}}
        @php
            $isAdmin = auth()->user()->role === 'admin';
        @endphp

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            @if($isAdmin)
                <select name="status" id="status" class="form-select">
                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            @else
                <input type="text" name="status" class="form-control" value="Pending">
            @endif
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

@endsection
