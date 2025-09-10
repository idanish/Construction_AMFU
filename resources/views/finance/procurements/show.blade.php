@extends('master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Procurement Details</h4>

    <div class="card p-3">
        <p><strong>ID:</strong> {{ $procurement->id }}</p>
        <p><strong>Title:</strong> {{ $procurement->title }}</p>
        <p><strong>Department:</strong> {{ $procurement->department->name ?? 'N/A' }}</p>
        <p><strong>Description:</strong> {{ $procurement->description }}</p>
        <p><strong>Status:</strong> {{ ucfirst($procurement->status) }}</p>
        <p><strong>Attachment:</strong>
            @if($procurement->attachment)
                <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank">View File</a>
            @else
                <span class="text-muted">No File</span>
            @endif
        </p>
        <p><strong>Created At:</strong> {{ $procurement->created_at }}</p>
    </div>

    <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
