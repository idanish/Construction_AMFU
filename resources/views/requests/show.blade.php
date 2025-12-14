@extends('master')
@section('title', 'Request #' . $request->id)

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Request Details</div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('requests.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            @can('update-request')
            <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-warning vip-btn">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            @endcan
        </div>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- Request Info --}}
    <div class="col-md-6">
        <div class="mb-3">
            <div class="card-header fw-bold bg-light">
                <i class="bi bi-info-circle"></i> Request Information
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Title</th>
                        <td>{{ $request->title }}</td>
                    </tr>
                    <tr>
                        <th>Requestor</th>
                        <td>{{ $request->requestor->name }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ optional($request->department)->name ?? 'N/A (Private Request)' }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>${{ number_format($request->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge 
                                {{ $request->status == 'approved' ? 'bg-success' : 
                                   ($request->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($request->status) }}
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
                    @forelse($request->getMedia('attachments') as $media)
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

{{-- Description / Notes --}}
<div class="mb-3">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-journal-text"></i> Description
    </div>
    <div class="card-body">
        {{ $request->description }}
    </div>
</div>

{{-- Comments --}}
<div class="mb-3">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-chat-left-text"></i> Comments
    </div>
    <div class="card-body">
        {{ $request->comments }}
    </div>
</div>

{{-- Approval Action --}}
@php
$isPendingAndActionable = in_array($request->status, ['pending', 'Needs Revision']);
$currentUserLevelSequence = optional(Auth::user()->approvalLevel)->sequence;
$isCurrentApprover = false;

if ($isPendingAndActionable && $request->current_level) {
    if ($currentUserLevelSequence == $request->current_level) {
        $currentPendingApproval = $request->approvals
            ->where('level', $request->current_level)
            ->where('status', 'pending')
            ->where('approver_id', Auth::id())
            ->first();

        if ($currentPendingApproval) {
            $isCurrentApprover = true;
        }
    }
}
@endphp

@if($isCurrentApprover)
<div class="mb-3">
    <div class="card-header bg-primary text-white fw-bold">
        <i class="bi bi-check2-circle"></i> Approval Action (Level {{ $currentPendingApproval->level }})
    </div>
    <div class="card-body">
        <form action="{{ route('approvals.updateStatus', $currentPendingApproval->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="comments">Comments (Optional)</label>
                <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Approval ya rejection ke liye comments likhen..."></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" name="status" value="rejected" class="btn btn-danger me-2"
                    onclick="return confirm('Are you sure you want to reject?')">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button type="submit" name="status" value="approved" class="btn btn-success"
                    onclick="return confirm('Are you sure you want to approve?')">
                    <i class="fas fa-check"></i> Approve
                </button>
            </div>
        </form>
    </div>
</div>
@else
<div class="alert alert-info">
    @if ($request->status == 'approved')
        <i class="fas fa-thumbs-up"></i> Fully Approved
    @elseif ($request->status == 'rejected')
        <i class="fas fa-ban"></i> Rejected
    @elseif ($request->status == 'Needs Revision')
        <i class="fas fa-edit"></i> Needs Revision
    @elseif ($request->status == 'pending')
        <i class="fas fa-hourglass-half"></i> Pending at Level {{ $request->current_level }}
    @else
        <i class="fas fa-info-circle"></i> {{ ucwords($request->status) }}
    @endif
</div>
@endif

@endsection
