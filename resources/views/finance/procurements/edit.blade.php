@extends('master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Edit Procurement</h4>

    <form action="{{ route('finance.procurements.update', $procurement->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $procurement->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $procurement->department_id == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $procurement->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Attachment</label>
            @if($procurement->attachment)
                <p>Current File: <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank">View</a></p>
            @endif
            <input type="file" name="attachment" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending" {{ $procurement->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $procurement->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $procurement->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
