@extends('master')
@section('title', 'View Procurement')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Procurement Details</div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            @can('update-procurement')
            <a href="{{ route('finance.procurements.edit', $procurement->id) }}" class="btn btn-download vip-btn">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
        </div>
    </div>
</div>

{{-- Alerts --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- Procurement Info --}}
    <div class="col-md-6">
        <div class="mb-3">
            <div class="card-header fw-bold bg-light">
                <i class="bi bi-info-circle"></i> Procurement Information
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="40%">Item Name</th>
                        <td>{{ $procurement->item_name }}</td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td>{{ $procurement->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Cost Estimate</th>
                        <td>${{ number_format($procurement->cost_estimate, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Unit Price</th>
                        <td>
                            @if($procurement->quantity > 0)
                            ${{ number_format($procurement->cost_estimate / $procurement->quantity, 2) }}
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge 
                                {{ $procurement->status == 'approved' ? 'bg-success' : 
                                   ($procurement->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($procurement->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Attachments --}}
    <div class="col-md-6">
        <div class="mb-3">
            <div class="card-header fw-bold bg-light">
                <i class="bi bi-paperclip"></i> Attachments
            </div>
            <div class="card-body">
                @can('view attachment')
                @forelse($procurement->getMedia('attachments') as $media)
                <a href="{{ $media->getUrl() }}" target="_blank" class="d-block mb-1">
                    <i class="bi bi-paperclip"></i> {{ $media->file_name }}
                </a>
                @empty
                <span class="text-muted">No attachments available.</span>
                @endforelse
                @else
                <span class="text-muted">You are not allowed to view attachments.</span>
                @endcan
            </div>
        </div>
    </div>
</div>

{{-- Notes / Justification --}}
@if($procurement->justification)
<div class="mb-3">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-journal-text"></i> Justification / Notes
    </div>
    <div class="card-body">
        {{ $procurement->justification }}
    </div>
</div>
@endif

@if( ucfirst($procurement->status) == 'Pending')

@can('approve-procurement')
<form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="status" value="approved">
    <button type="submit" class="btn btn-success vip-btn">
        <i class="bi bi-check-circle"></i> Approve
    </button>
</form>
@endcan

@can('reject-procurement')
<form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="status" value="rejected">
    <button type="submit" class="btn btn-danger vip-btn">
        <i class="bi bi-x-circle"></i> Reject
    </button>
</form>
@endcan

@endif
@endsection