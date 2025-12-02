@extends('master')
@section('title', 'Request #' . $request->id)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Request #{{ $request->id }}</h1>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Request Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Title:</strong> {{ $request->title }}</p>
                        <p><strong>Requestor:</strong> {{ $request->requestor->name }}</p>
                        <p><strong>Department:</strong> {{ $request->department->name }}</p>
                        <p><strong>Amount:</strong> ${{ number_format($request->amount, 2) }}</p>
                        <p><strong>Description:</strong> {{ $request->description }}</p>
                        <p><strong>Status:</strong>
                            @if($request->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($request->status === 'reverted')
                                <span class="badge bg-danger">Reverted</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                        <p><strong>Current Step:</strong> <span class="badge bg-info">{{ $request->current_approval_step }}</span></p>
                        @if($request->approved_at)
                            <p><strong>Approved On:</strong> {{ $request->approved_at->format('d-M-Y h:i A') }}</p>
                        @endif
                        <p><strong>Created:</strong> {{ $request->created_at->format('d-M-Y h:i A') }}</p>
                    </div>
                </div>

                @if($request->revert_reason)
                    <div class="alert alert-warning">
                        <h6>Revert Reason:</h6>
                        <p>{{ $request->revert_reason }}</p>
                    </div>
                @endif

                <!-- Approval Workflow Steps -->
                <div class="card">
                    <div class="card-header">
                        <h5>Approval Workflow</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @php
                                $approvals = $request->approvals()->orderBy('step_order')->get();
                            @endphp

                            @forelse($approvals as $approval)
                                <div class="timeline-item mb-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6>
                                                Step {{ $approval->step_order }}: {{ $approval->approval_step }}
                                                @if($approval->status === 'approved')
                                                    <span class="badge bg-success ms-2">Approved</span>
                                                @elseif($approval->status === 'rejected')
                                                    <span class="badge bg-danger ms-2">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning ms-2">Pending</span>
                                                @endif
                                            </h6>
                                            <p class="text-muted mb-1">
                                                <strong>Assigned To:</strong> {{ $approval->approver->name }}
                                            </p>
                                            @if($approval->acted_at)
                                                <p class="text-muted mb-1">
                                                    <strong>Action Taken:</strong> {{ $approval->acted_at->format('d-M-Y h:i A') }}
                                                </p>
                                            @endif
                                            @if($approval->note)
                                                <p class="text-muted mb-1">
                                                    <strong>Note:</strong> {{ $approval->note }}
                                                </p>
                                            @endif
                                            @if($approval->revert_reason)
                                                <div class="alert alert-danger mt-2 mb-0">
                                                    <strong>Rejection Reason:</strong>
                                                    <p>{{ $approval->revert_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            @if($approval->status === 'approved')
                                                <span class="badge bg-success p-2">✓ Approved</span>
                                            @elseif($approval->status === 'rejected')
                                                <span class="badge bg-danger p-2">✗ Rejected</span>
                                            @else
                                                <span class="badge bg-warning p-2">⏱ Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @empty
                                <p class="text-muted">No approvals assigned.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        @if($request->status === 'reverted' && $request->requestor_id === auth()->id())
                            <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil-square"></i> Edit & Resubmit
                            </a>
                        @elseif($request->status !== 'approved' && $request->requestor_id === auth()->id())
                            <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-pencil-square"></i> Edit Request
                            </a>
                        @endif

                        @if(auth()->id() === $request->requestor_id && $request->status !== 'approved')
                            <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash"></i> Delete Request
                                </button>
                            </form>
                        @endif

                        @if($request->status === 'approved' && auth()->id() === $request->requestor_id)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> This request has been approved and is ready for implementation.
                            </div>
                        @endif

                        <a href="{{ route('requests.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left-circle"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 12px;
            height: 12px;
            background: #0066cc;
            border-radius: 50%;
            border: 2px solid #fff;
        }
    </style>
@endsection