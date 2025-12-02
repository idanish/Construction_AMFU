@extends('master')
@section('title', 'My Approvals')
@section('content')
    <div class="container">
        <h1>Pending Approvals</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($pendingApprovals->isEmpty())
            <div class="alert alert-info">No pending approvals assigned to you.</div>
        @else
            <div class="row">
                @foreach($pendingApprovals as $approval)
                    @php $req = $approval->request; @endphp
                    <div class="col-md-6 mb-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">{{ $approval->approval_step }} Approval - Request #{{ $req->id }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Title:</strong> {{ $req->title }}</p>
                                <p><strong>Requestor:</strong> {{ $req->requestor->name }} ({{ $req->department->name }})</p>
                                <p><strong>Amount:</strong> {{ $req->amount }}</p>
                                <p><strong>Description:</strong> {{ substr($req->description, 0, 100) }}...</p>
                                <p><strong>Current Step:</strong> <span class="badge bg-info">{{ $req->current_approval_step }}</span></p>

                                <hr>

                                <div class="approval-actions">
                                    <form action="{{ route('approvals.approve', $approval->id) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                        @csrf
                                        <input type="hidden" name="note" value="Approved by {{ auth()->user()->name }}">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $approval->id }}">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $approval->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('approvals.reject', $approval->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="revert_reason">Reason for Rejection:</label>
                                                        <textarea name="revert_reason" id="revert_reason" class="form-control" rows="4" required placeholder="Please provide a detailed reason..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
@extends('master')
@section('title', 'Approvals')
@section('content')
<div class="container">
    <h2>All Approvals</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Approval ID</th>
                <th>Request Title</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Approved By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $approval)
                <tr>
                    <td>{{ $approval->id }}</td>
                    <td>{{ $approval->request->title ?? 'N/A' }}</td>
                    <td>{{ ucfirst($approval->status) }}</td>
                    <td>{{ $approval->comments }}</td>
                    <td>{{ $approval->approver_id }}</td>
                    <td>{{ $approval->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection