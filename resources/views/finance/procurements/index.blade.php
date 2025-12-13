@extends('master')
@section('title', 'Procurement')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0"><span>Procurements</span></div>
        </div>
        <div class="page-title-actions">
            @can('create-procurement')
            <a href="{{ route('finance.procurements.create') }}" class="btn btn-download mb-3 vip-btn">
                <i class="bi bi-plus-circle"></i> Create
            </a>
            @endcan
        </div>
    </div>
</div>


{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


{{-- filter --}}
<div class="mb-2">
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('finance.procurements.index') }}" class="row mb-2">

            <!-- Search -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search Item"
                    value="{{ request('search') }}">
            </div>

            <!-- Department -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Department</label>
                <select name="department_id" class="form-control">
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="submit" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
            </div>

            <!-- <div class="col-md-2 col-sm-6">
                <button type="submit" class="vip-btn btn-filter">
                    <I class="bi bi-funnel"></I> Filter
                </button>
            </div> -->
        </form>
    </div>
</div>

<div class="table-responsive-lg">
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Cost Estimate</th>
                <th>Unit Price</th>
                <th>Department</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($procurements as $key => $procurement)
            <tr>
                <td>{{ $procurements->firstItem() + $key }}</td>
                <td>{{ $procurement->item_name }}</td>
                <td>{{ $procurement->quantity }}</td>
                <td>${{ $procurement->cost_estimate }}</td>
                <td>${{ $procurement->quantity > 0 
            ? number_format($procurement->cost_estimate / $procurement->quantity, 2) 
            : 'N/A' }} </td>
                <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                <td>
                    @can('view attachment')
                    <!-- @if ($procurement->attachment)
                    <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank"
                        class="btn btn-sm btn-info vip-btn">
                        <i class="bi bi-eye"></i> View
                    </a>
                    @else
                    N/A
                    @endif -->
                     @foreach($procurement->getMedia('attachments') as $media)
            <a href="{{ $media->getUrl() }}"  target="_blank" title="{{ $media->file_name }}">
                <i class="bi bi-paperclip"></i> {{ $media->file_name }}</a><br>
        @endforeach
                    @endcan

                </td>
                <td>{{ ucfirst($procurement->status) }}</td>
                <td>
                    <!-- Status Change Buttons -->
                    <!-- @if ($procurement->status === 'pending')
                    @can('approve-procurement')
                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success vip-btn">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                    @endcan

                    @can('reject-procurement')
                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-dark vip-btn">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    @endcan
                    <br><br>
                    @endif -->



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



                    <!-- Status Change Buttons -->

                    @can('update-procurement')
                    <a href="{{ route('finance.procurements.edit', $procurement->id) }}"
                        class="btn btn-sm btn-download vip-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan

                    @can('delete-procurement')
                    <form action="{{ route('finance.procurements.destroy', $procurement->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger vip-btn"
                            onclick="return confirm('Are you sure you want to delete this procurement?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        {{ $procurements->links() }}
    </div>
</div>
@endsection