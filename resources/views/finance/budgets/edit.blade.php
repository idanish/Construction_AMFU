@extends('master')


@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Budget</h2>

    <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $budget->title) }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount" value="{{ old('amount', $budget->amount) }}">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="Pending" {{ old('status', $budget->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ old('status', $budget->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ old('status', $budget->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection