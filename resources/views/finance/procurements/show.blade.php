@extends('master')
@section('title', 'Procument Details')
@section('content')
<div class="container py-4">
    <h4 class="mb-4">Procurement Details</h4>

    <div class="card shadow-sm p-4">
        <div class="mb-3"><strong>ID:</strong> {{ $procurement->id }}</div>
        <div class="mb-3"><strong>Item Name:</strong> {{ $procurement->item_name }}</div>
        <div class="mb-3"><strong>Quantity:</strong> {{ $procurement->quantity }}</div>
        <div class="mb-3"><strong>Cost Estimate:</strong> PKR {{ number_format($procurement->cost_estimate, 2) }}</div>
        <div class="mb-3"><strong>Department:</strong> {{ $procurement->department->name ?? 'N/A' }}</div>
        <div class="mb-3"><strong>Justification:</strong> {{ $procurement->justification }}</div>
        <div class="mb-3">
            <strong>Status:</strong>
            @if($procurement->status == 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif($procurement->status == 'approved')
                <span class="badge bg-success">Approved</span>
            @else
                <span class="badge bg-danger">Rejected</span>
            @endif
        </div>
        <div class="mb-3">
            <strong>Attachment:</strong>
            @if($procurement->attachment)
                @php
                    $atts = is_array($procurement->attachment) ? $procurement->attachment : (json_decode($procurement->attachment, true) ?? [$procurement->attachment]);
                @endphp
                @foreach($atts as $att)
                    @php
                        $attPath = is_array($att) ? ($att['path'] ?? $att) : $att;
                        $attName = is_array($att) ? ($att['name'] ?? basename($attPath)) : basename($attPath);
                    @endphp
                    <a href="{{ asset('storage/' . $attPath) }}" target="_blank" class="btn btn-sm btn-outline-info mb-1">{{ $attName }}</a>
                @endforeach
            @else
                <span class="text-muted">No File</span>
            @endif
        </div>
        <div class="mb-3"><strong>Created At:</strong> {{ $procurement->created_at->format('d M, Y H:i') }}</div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('finance.procurements.edit', $procurement->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
