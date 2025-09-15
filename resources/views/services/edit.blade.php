@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Work Order</h2>

    <form action="{{ route('services.update', $serviceRequest->id) }}" method="POST">
        @csrf
        @method('PUT')

        
        {{-- Request No (readonly, not editable by user) --}}
        <input type="hidden" name="request_no" value="{{ $serviceRequest->request_no }}">
        <div class="mb-3">
            <label for="request_no" class="form-label">Request No</label>
            <input type="text" class="form-control" id="request_no" 
                   value="{{ $serviceRequest->request_no }}" readonly>
        </div>
        
        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" 
                   value="{{ old('title', $serviceRequest->title) }}">
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description">{{ old('description', $serviceRequest->description) }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Status (Disabled for user, hidden input to keep value on update) --}}
        <input type="hidden" name="status" value="{{ $serviceRequest->status }}">
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" disabled>
                <option value="Pending" {{ $serviceRequest->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $serviceRequest->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ $serviceRequest->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <small class="text-muted">Status can only be changed by Admin</small>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
