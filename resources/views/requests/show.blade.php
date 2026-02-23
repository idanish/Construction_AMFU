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
    $isActionable = !in_array($request->status, ['approved', 'rejected']);
    $isCurrentApprover = false;
    $currentPendingApproval = null;

    if ($isActionable) {
        // 1. Pehle ye dhoondein ke abhi pending level kaunsa hai
        $currentPendingApproval = $request->approvals
            ->where('level', $request->current_level)
            ->where('status', 'pending')
            ->first();

        // 2. Button dikhane ki logic
        if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('Admin')) {
            // Case A: Agar Admin hai, toh buttons dikhao (chahay record assigned ho ya na ho)
            $isCurrentApprover = true;
            
            // Agar Admin hai aur record nahi mila (level skip hua hai), toh ek dummy object banayein taake form crash na kare
            if (!$currentPendingApproval) {
                // Hum current_level ke mutabiq approval ID nikalte hain bypass ke liye
                $currentPendingApproval = (object)[
                    'id' => $request->approvals->where('level', $request->current_level)->first()->id ?? 0,
                    'level' => $request->current_level
                ];
            }
        } elseif ($currentPendingApproval && $currentPendingApproval->approver_id == Auth::id()) {
            // Case B: Agar normal user hai aur usay hi assign hai
            $isCurrentApprover = true;
        }
    }
@endphp

@if($isCurrentApprover && $currentPendingApproval && $currentPendingApproval->id != 0)
    <div class="mb-3">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-check2-circle"></i> 
            Approval Action (Level {{ $request->current_level }}) 
            @if(Auth::user()->hasRole('super-admin')) <span class="badge bg-warning text-dark">Admin Bypass</span> @endif
        </div>
        <div class="card-body">
            <form action="{{ route('approvals.updateStatus', $currentPendingApproval->id) }}" method="POST">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="comments">Comments (Optional)</label>
                    <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Comment for Approval or rejection"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="status" value="approved" class="btn btn-success vip-btn"
                        onclick="return confirm('Are you sure you want to approve?')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="submit" name="status" value="rejected" class="btn btn-danger me-2 vip-btn"
                        onclick="return confirm('Are you sure you want to reject?')">
                        <i class="fas fa-times"></i> Reject
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
        @elseif ($request->status == 'need revision')
            <i class="fas fa-edit"></i> Need Revision
        @elseif ($request->status == 'pending')
            <i class="fas fa-hourglass-half"></i> Pending at Level {{ $request->current_level }}
        @else
            <i class="fas fa-info-circle"></i> {{ ucwords($request->status) }}
        @endif
    </div>
@endif


{{-- Request ki history dikhane ke liye --}}
<div class="mt-4 shadow-sm border rounded">
    <div class="accordion" id="approvalHistoryAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingHistory">
                <button class="accordion-button collapsed bg-light fw-bold" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#collapseHistory" 
                        aria-expanded="false" aria-controls="collapseHistory">
                    <i class="bi bi-clock-history me-2 text-primary"></i> Approval Timeline & History
                </button>
            </h2>
            <div id="collapseHistory" class="accordion-collapse collapse" 
                 aria-labelledby="headingHistory" data-bs-parent="#approvalHistoryAccordion">
                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Time</th>
                                    <th>Level</th>
                                    <th>Action By</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($request->approvals()->orderBy('updated_at', 'desc')->get() as $history)
                                    <tr class="{{ $history->status == 'rejected' ? 'table-danger' : ($history->status == 'approved' ? 'table-success' : '') }}">
                                        <td class="ps-3 small text-nowrap">{{ $history->updated_at->format('d M, h:i A') }}</td>
                                        <td><span class="badge bg-secondary">Level {{ $history->level }}</span></td>
                                        <td>
                                            <small class="fw-bold text-dark">{{ $history->approver->name ?? 'System/Auto' }}</small>
                                        </td>
                                        <td>
                                            @if($history->status == 'approved')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>
                                            @elseif($history->status == 'rejected')
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-wrap" style="max-width: 250px;">
                                            <i class="small text-muted italic">"{{ $history->comments ?? 'No comments' }}"</i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
