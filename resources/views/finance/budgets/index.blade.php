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

            <div class="col-md-3 col-6">
                <label class="filter-label">Status</label>
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


{{-- TABLE --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="bg-light">
            <tr>
                <th>No</th>
                <th>Department</th>
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
                <td>{{ $key + 1 }}</td>
                <td>{{ $budget->department->name ?? 'N/A' }}</td>
                <td>{{ $budget->year }}</td>
                <td>${{ number_format($budget->allocated) }}</td>
                <td>${{ number_format($budget->requested_budget) }}</td>
                <td>{{ ucfirst($budget->budget_type) }}</td>
                <td>${{ number_format($budget->spent) }}</td>
                <td>${{ number_format($budget->balance) }}</td>
                <td>{{ ucfirst($budget->status) }}</td>
                <td>{{ $budget->created_at?->format('Y-m-d') }}</td>

                <td>
                    @php
                        $atts = is_array($budget->attachment)
                                ? $budget->attachment
                                : ($budget->attachment ? (json_decode($budget->attachment, true) ?? [$budget->attachment]) : []);
                    @endphp

                    @forelse ($atts as $att)
                        @php
                            $attPath = is_array($att) ? ($att['path'] ?? '') : $att;
                            $attName = is_array($att) ? ($att['name'] ?? basename($attPath)) : basename($attPath);
                        @endphp

                        <a href="{{ asset('storage/' . $attPath) }}" target="_blank"
                           class="btn btn-info attachment-btn mb-1">
                           <i class="bi bi-eye"></i> {{ $attName }}
                        </a>
                    @empty
                        -
                    @endforelse
                </td>

                <td>
                    <div class="btn-action-group">

                        @if ($budget->status === 'pending')
                            @can('approve-budget')
                            <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button class="btn btn-success vip-btn">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            </form>
                            @endcan

                            @can('reject-budget')
                            <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-dark vip-btn">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>
                            @endcan
                        @endif

                        @can('update-budget')
                        <a href="{{ route('finance.budgets.edit', $budget->id) }}"
                           class="btn btn-warning vip-btn">
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
                <td colspan="12" class="text-center">No budgets found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $budgets->appends(request()->query())->links() }}
</div>

@endsection
