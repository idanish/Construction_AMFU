@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Service Request</h2>

    <form action="{{ route('services.update', $serviceRequest->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="request_no" class="form-label">Request No</label>
            <input type="text" name="request_no" class="form-control" id="request_no" value="{{ old('request_no', $serviceRequest->request_no) }}">
            @error('request_no') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description">{{ old('description', $serviceRequest->description) }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" id="status" disabled>
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
