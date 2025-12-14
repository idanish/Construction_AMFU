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
            <a href="{{ route('finance.procurements.edit', $procurement->id) }}" class="btn btn-warning vip-btn">
                <i class="bi bi-pencil-square"></i> Edit
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

{{-- Future Approval History (if required) --}}
{{-- 
@if($procurement->approvals && $procurement->approvals->count())
<div class="mb-3">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-check2-circle"></i> Approval History
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0 text-center">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Approver</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($procurement->approvals as $key => $approval)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $approval->approver->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge 
                            {{ $approval->status == 'approved' ? 'bg-success' : 
                               ($approval->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($approval->status) }}
                        </span>
                    </td>
                    <td>{{ $approval->remarks ?? '-' }}</td>
                    <td>{{ $approval->created_at?->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
--}}

@endsection



@php
$isPendingAndActionable = in_array($procurement->status, ['pending', 'Needs Revision']);
$currentUserLevelSequence = optional(Auth::user()->approvalLevel)->sequence;
$isCurrentApprover = false;

if ($isPendingAndActionable && $procurement->current_level) {
if ($currentUserLevelSequence == $procurement->current_level) {
$currentPendingApproval = $procurement->approvals
->where('level', $procurement->current_level)
->where('status', 'pending')
->where('approver_id', Auth::id())
->first();

if ($currentPendingApproval) {
$isCurrentApprover = true;
}
}
}
@endphp

@if ($isCurrentApprover)

<div class="card mt-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Approval Action (Level {{ $currentPendingApproval->level }})</h5>
    </div>
    <div class="card-body">

        <form action="{{ route('approvals.updateStatus', $currentPendingApproval->id) }}" method="POST">
            @csrf

            {{-- Comments Field --}}
            <div class="form-group mb-3">
                <label for="comments">Comments (Optional)</label>
                <textarea name="comments" id="comments" class="form-control" rows="3"
                    placeholder="Approval ya rejection ke liye comments likhen..."></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-end">
                {{-- REJECT Button --}}
                <button type="submit" name="status" value="rejected" class="btn btn-danger btn-lg me-3"
                    onclick="return confirm('Are you Confirm this Rejection?')">
                    <i class="fas fa-times"></i> Reject
                </button>

                {{-- APPROVE Button --}}
                <button type="submit" name="status" value="approved" class="btn btn-success btn-lg"
                    onclick="return confirm('Are you Confirm this Approval?')">
                    <i class="fas fa-check"></i> Approve
                </button>
            </div>
        </form>

    </div>
</div>
@else
{{-- Approval Status Box --}}
<div class="alert alert-info mt-4">
    @if ($procurement->status == 'approved')
    <i class="fas fa-thumbs-up"></i> **Status:** Fully Approved.
    @elseif ($procurement->status == 'rejected')
    <i class="fas fa-ban"></i> **Status:** Rejected.
    @elseif ($procurement->status == 'Needs Revision')
    <i class="fas fa-edit"></i> **Status:** Needs Revision.
    @elseif ($procurement->status == 'pending')
    <i class="fas fa-hourglass-half"></i> **Status:** Pending at Level
    {{ $procurement->current_level }}.
    @else
    <i class="fas fa-info-circle"></i> **Status:** {{ ucwords($procurement->status) }}.
    @endif
</div>
@endif