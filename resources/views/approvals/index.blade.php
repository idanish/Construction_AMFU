@extends('master')
@section('title', 'Pending Approvals')

@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-check icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Pending Approvals</div>
        </div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-responsive">
    <table class="table datatable table-bordered table-striped table-sm w-100">
        <thead class="table thead-dark text-center fw-bold bg-light text-dark">
            <tr>
                <th>S.No</th>
                <th>Request Title</th>
                <th>Requestor</th>
                <th>Amount</th>
                <th>Current Level</th>
                <th>Submitted On</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($approvals as $key => $appr)
            <tr class="text-center align-middle">
                <td>{{ $key + 1 }}</td>
                <td>{{ $appr->request->title }}</td>
                <td>{{ $appr->request->requestor->name }}</td>
                <td>${{ number_format($appr->request->amount) }}</td>
                <td>Level {{ $appr->level }}</td>
                <td>{{ $appr->created_at->format('d-M-Y h:i A') }}</td>

                <td>
                    <a href="{{ route('approvals.show', $appr->id) }}"
                        class="btn btn-primary btn-sm vip-btn">
                        <i class="bi bi-eye"></i> View
                    </a>

                    <button class="btn btn-success btn-sm vip-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#approveModal{{ $appr->id }}">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>

                    <button class="btn btn-dark btn-sm vip-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal{{ $appr->id }}">
                        <i class="bi bi-x-circle"></i> Reject
                    </button>
                </td>
            </tr>

            {{-- Approve Modal --}}
            <div class="modal fade" id="approveModal{{ $appr->id }}">
                <div class="modal-dialog">
                <form method="POST" action="{{ route('approvals.updateStatus', $appr->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="approved">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Approve Request</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label>Comments (Optional)</label>
                            <textarea name="comments" class="form-control"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div class="modal fade" id="rejectModal{{ $appr->id }}">
                <div class="modal-dialog">
                <form method="POST" action="{{ route('approvals.updateStatus', $appr->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Reject Request</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label>Reason</label>
                            <textarea name="comments" class="form-control" required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-dark">Reject</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        @empty
            <tr><td colspan="7" class="text-center">No pending approvals.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
