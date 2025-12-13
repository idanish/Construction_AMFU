@extends('master')
@section('title', 'Budget')
@section('content')
<style>

/* -------------------------------------------
   UNIVERSAL BUTTON FIX (ALL BUTTONS SAME SIZE)
-------------------------------------------- */
.vip-btn,
.attachment-btn,
.btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    height: 36px !important;
    padding: 4px 12px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    white-space: nowrap !important;
}

/* -------------------------------------------
   BUTTONS IN 2 x 2 GRID
-------------------------------------------- */
.btn-action-group {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;  /* 2 buttons per row */
    grid-gap: 6px !important;
    width: 170px; /* optional fixed width */
}

/* Fix attachment buttons */
.attachment-btn {
    height: 32px !important;
    font-size: 11px !important;
}

/* Table alignment */
table th,
table td {
    vertical-align: middle !important;
    text-align: center !important;
    padding: 7px !important;
    font-size: 12px !important;
}

/* FILTER LABELS */
.filter-label {
    font-weight: 700;
    font-size: 12px;
    margin-bottom: 3px;
}

/* -------------------------------------------
   RESPONSIVE FIXES
-------------------------------------------- */
@media (max-width: 768px) {
    .vip-btn,
    .btn {
        width: 100%;
    }

    .btn-action-group {
        grid-template-columns: repeat(1, 1fr) !important; /* Mobile → 1 per row */
        width: 100% !important;
    }
}

</style>



<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center flex-wrap">

        <div class="page-title-heading m-0 mb-2">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Budgets</div>
        </div>

        <div class="page-title-actions mb-2">
            @can('create-budget')
            <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary vip-btn">
                <i class="bi bi-plus-circle"></i> Create
            </a>
            @endcan
        </div>

    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Error Message --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- FILTERS --}}
<div class="card mb-3">
    <div class="card-body">

        <form method="GET" action="{{ route('finance.budgets.index') }}" class="row g-3">

            <div class="col-md-3 col-12">
                <label class="filter-label">Department</label>
                <select name="department_id" class="form-control">
                    <option value="">-- All --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-6">
                <label class="filter-label">Year</label>
                <input type="number" name="year" class="form-control" value="{{ request('year') }}" placeholder="Year">
            </div>



            <!-- Status -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Status</label>
                <select name="status" class="form-control">
                    <option value="">-- All --</option>
                    <option value="pending"  {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-2 col-6">
                <label class="filter-label">Records</label>
                <select name="per_page" class="form-control">
                    <option value="10"  {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25"  {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50"  {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="col-md-2 col-6 d-flex align-items-end gap-2">
                <button class="btn btn-secondary vip-btn w-100" type="submit">
                    <i class="bi bi-funnel"></i> Filter
                </button>

                <a href="{{ route('finance.budgets.index') }}" class="btn btn-dark vip-btn w-100">
                    <i class="bi bi-eraser"></i> Reset
                </a>
            </div>

        </form>

    </div>
</div>

<div class="table-responsive-lg ">
    <table id="budgetsTable" class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
            <tr class="text-center align-middle fw-bold ">
                <th>No</th>
                <th>Department</th>
                <th>Month</th>
                <th>Year</th>
                <th>Allocated</th>
                <th>Requested</th>
                <th>Type</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Date</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($budgets as $key => $budget)
            <tr>
                <td>{{ $key + $budgets->firstItem() }}</td>
                <td>{{ $budget->department->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::create()->month($budget->month)->format('F') }}</td>
                <td>{{ $budget->year }}</td>
                <td>${{ number_format($budget->allocated, 2) }}</td>
                <td>${{ number_format($budget->spent, 2) }}</td>
                <td>${{ number_format($budget->balance, 2) }}</td>
                <td>{{ ucfirst($budget->status) }}</td>
                <td>{{ $budget->created_at?->format('Y-m-d') }}</td>

                <td>
                    @can('view attachment')


                    @forelse($budget->getMedia('attachments') as $media)
                    <a href="{{ $media->getUrl() }}" target="_blank" title="{{ $media->file_name }}">
                        <i class="bi bi-paperclip"></i> {{ $media->file_name }}</a><br>
                    @empty
                    N/A
                    @endforelse
                    @endcan
                </td>

                <td>


                    @php
                    $isPendingAndActionable = in_array($budget->status, ['pending', 'Needs Revision']);
                    $currentUserLevelSequence = optional(Auth::user()->approvalLevel)->sequence;
                    $isCurrentApprover = false;

                    if ($isPendingAndActionable && $budget->current_level) {
                    if ($currentUserLevelSequence == $budget->current_level) {
                    $currentPendingApproval = $budget->approvals
                    ->where('level', $budget->current_level)
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
                        @if ($budget->status == 'approved')
                        <i class="fas fa-thumbs-up"></i> **Status:** Fully Approved.
                        @elseif ($budget->status == 'rejected')
                        <i class="fas fa-ban"></i> **Status:** Rejected.
                        @elseif ($budget->status == 'Needs Revision')
                        <i class="fas fa-edit"></i> **Status:** Needs Revision.
                        @elseif ($budget->status == 'pending')
                        <i class="fas fa-hourglass-half"></i> **Status:** Pending at Level
                        {{ $budget->current_level }}.
                        @else
                        <i class="fas fa-info-circle"></i> **Status:** {{ ucwords($budget->status) }}.
                        @endif
                    </div>
                    @endif




                    <!-- Status Change Buttons -->
                    @can('update-budget')
                    <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn  btn-warning vip-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan

                        @can('delete-budget')
                        <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger vip-btn">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        @endcan

                    </div>
                </td>

            </tr>

            @empty
            <tr>
                <td colspan="10" class="text-center">No budgets found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $budgets->appends(request()->query())->links() }}
</div>

@endsection
