@extends('master')
@section('title', 'Requet Details')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Request Details</h1>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $request->title }}</h5>
                    <p class="card-text"><strong>Requestor:</strong> {{ $request->requestor->name }}</p>
                    <p class="card-text"><strong>Department:</strong> {{ optional($request->department)->name ?? 'N/A (Private Request)' }}</p>
                    <p class="card-text"><strong>Amount:</strong> {{ $request->amount }}</p>

                    <p class="card-text">
                        <strong>Attachments:</strong>
                    @foreach($request->getMedia('attachments') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" title="{{ $media->file_name }}">
                        <i class="bi bi-paperclip"></i> {{ $media->file_name }}</a><br>
                    @endforeach
                    </p>

                    <p class="card-text"><strong>Status:</strong>
                        @if($request->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif($request->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                        @else
                        <span class="badge bg-danger">Rejected</span>
                        @endif


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

                        @if ($isCurrentApprover)

                    <div class="card mt-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Approval Action (Level {{ $currentPendingApproval->level }})</h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('approvals.updateStatus', $currentPendingApproval->id) }}"
                                method="POST">
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
                                    <button type="submit" name="status" value="rejected"
                                        class="btn btn-danger btn-lg me-3"
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
                        @if ($request->status == 'approved')
                        <i class="fas fa-thumbs-up"></i> **Status:** Fully Approved.
                        @elseif ($request->status == 'rejected')
                        <i class="fas fa-ban"></i> **Status:** Rejected.
                        @elseif ($request->status == 'Needs Revision')
                        <i class="fas fa-edit"></i> **Status:** Needs Revision.
                        @elseif ($request->status == 'pending')
                        <i class="fas fa-hourglass-half"></i> **Status:** Pending at Level
                        {{ $request->current_level }}.
                        @else
                        <i class="fas fa-info-circle"></i> **Status:** {{ ucwords($request->status) }}.
                        @endif
                    </div>
                    @endif


                    </p>
                    <hr>
                    <p class="card-text"><strong>Description:</strong></p>
                    <p>{{ $request->description }}</p>
                    <hr>
                    <p class="card-text"><strong>Comments:</strong></p>
                    <p>{{ $request->comments }}</p>

                    <a href="{{ route('requests.index') }}" class="btn btn-secondary mt-3 vip-btn">
                        <i class="bi bi-arrow-left-circle"></i> Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection