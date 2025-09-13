@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Budget</h2>

    <form action="{{ route('finance.budgets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Budget Title</label>
            <input type="text" name="title" class="form-control" id="title" 
                value="{{ old('title') }}" placeholder="Enter budget title" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Department --}}
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select" required>
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
            <input type="number" step="0.01" name="allocated" class="form-control" id="allocated" 
                value="{{ old('allocated') }}" placeholder="e.g. 50000" required>
            @error('allocated')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Spent --}}
        <div class="mb-3">
            <label for="spent" class="form-label">Spent Amount</label>
            <input type="number" step="0.01" name="spent" class="form-control" id="spent" 
                value="{{ old('spent', 0) }}" placeholder="e.g. 1000">
            @error('spent')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Attachment (Optional) --}}
        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment (Optional)</label>
            <input type="file" name="attachment" id="attachment" class="form-control" 
                accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx">
            @error('attachment')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <select name="status" id="status" class="form-select">
                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            @else
                {{-- Normal user: show status but keep it disabled --}}
                <select class="form-select" disabled>
                    <option selected>Pending</option>
                </select>
                {{-- Hidden input for actual submission --}}
                <input type="hidden" name="status" value="Pending">
            @endif
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-success">Save Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
