@extends('master')
@section('title', 'Procument Details')
@section('content')
<div class="container py-4">
    <h4 class="mb-4">Procurement Details</h4>

    <div class="card shadow-sm p-4">
        <div class="mb-3"><strong>ID:</strong> {{ $procurement->id }}</div>
        <div class="mb-3"><strong>Item Name:</strong> {{ $procurement->item_name }}</div>
        <div class="mb-3"><strong>Quantity:</strong> {{ $procurement->quantity }}</div>
        <div class="mb-3"><strong>Cost Estimate:</strong> <span id="currencySymbol">$</span> {{ number_format($procurement->cost_estimate, 2) }}</div>
        <div class="mb-3"><strong>Department:</strong> {{ $procurement->department->name ?? 'N/A' }}</div>
        <div class="mb-3"><strong>Justification:</strong> {{ $procurement->justification }}</div>
        <div class="mb-3">
            <strong>Status:</strong>
            <!-- @if($procurement->status == 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif($procurement->status == 'approved')
                <span class="badge bg-success">Approved</span>
            @else
                <span class="badge bg-danger">Rejected</span>
            @endif -->
        </div>
        <div class="mb-3">
            <strong>Attachment:</strong>
            <!-- @if($procurement->attachment)
                <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">View File</a>
            @else
                <span class="text-muted">No File</span>
            @endif -->
            @foreach($procurement->getMedia('attachments') as $media)
            <a href="{{ $media->getUrl() }}"  target="_blank" title="{{ $media->file_name }}">
                <i class="bi bi-paperclip"></i> {{ $media->file_name }}</a><br>
        @endforeach
        </div>
        <div class="mb-3"><strong>Created At:</strong> {{ $procurement->created_at->format('d M, Y H:i') }}</div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('finance.procurements.edit', $procurement->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
