@extends('master')
@section('title', 'Budget')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Budgets</div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                @can('create-budget')
                <a href="{{ route('finance.budgets.create') }}" class="btn btn-download mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Create
                </a>
                @endcan
            </div>
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
        <form method="GET" action="{{ route('finance.budgets.index') }}" class="row mb-2">
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

            <!-- Year Wise -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">By Year</label>
                <input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year') }}">
            </div>

            <!-- Status -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- per page -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">By Records</label>
                <select name="per_page" class="form-control">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="submit" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
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
                <th>Year</th>
                <th>Allocated</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgets as $key => $budget)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $budget->department->name ?? 'N/A' }}</td>
                <td>{{ $budget->year }}</td>
                <td>${{ number_format($budget->allocated) }}</td>
                <td>${{ number_format($budget->spent) }}</td>
                <td>${{ number_format($budget->balance) }}</td>
                <td>{{ ucfirst($budget->status) }}</td>

                <td>
                    @if ($budget->attachment)
                    <a href="{{ asset('storage/' . $budget->attachment) }}" target="_blank"
                        class="btn  btn-info vip-btn">
                        <i class="bi bi-eye"></i> View</a>
                    @else
                    -
                    @endif
                </td>
                <td>

                    <!-- Status Change Buttons -->

                    @if ($budget->status === 'pending')
                    @can('approve-budget')
                    <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success vip-btn">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                    @endcan

                    @can('reject-budget')
                    <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-dark vip-btn">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    @endcan
                    <br><br>
                    @endif
                    <!-- Status Change Buttons -->
                    @can('update-budget')
                    <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn  btn-warning vip-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan

                    @can('delete-budget')
                    <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST"
                        class="d-inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this budget?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger vip-btn">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach

            @if ($budgets->count() == 0)
            <tr>
                <td colspan="9" class="text-center">No budgets found.</td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $budgets->appends(request()->query())->links() }}
    </div>
</div>
@endsection