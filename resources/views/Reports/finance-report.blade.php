@extends('master')
@section('title', 'Finance Report')
@section('content')
<div class="container">
    <h2>Finance Report</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.finance') }}" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                </select>
            </div>
            <div class="col">
                <select name="per_page" class="form-control">
                    <option value="25">25</option>
                    <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
                    <option value="100" {{ request('per_page')==100?'selected':'' }}>100</option>
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-download vip-btn btn-filter"> <I class="bi bi-funnel"></I> Filter
                </button>
            </div>
        </div>
    </form>

    <!-- Export buttons -->
    <div class="mb-3">
        <a href="{{ route('reports.finance.export.excel') }}" class="btn btn-success vip-btn btn-excel">
            <i class="bi bi-file-earmark-excel"></i> Export Excel

        </a>
        <a href="{{ route('reports.finance.export.pdf') }}" class="btn btn-danger vip-btn btn-pdf">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF

        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive-lg">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>S.No</th>
                    <th>Department</th>
                    <th>Allocated</th>
                    <th>Spent</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $key => $budget)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $budget->department->name ?? '-' }}</td>
                    <td>{{ $budget->allocated }}</td>
                    <td>{{ $budget->spent }}</td>
                    <td>{{ $budget->balance }}</td>
                    <td>{{ $budget->status }}</td>
                    <td>{{ $budget->created_at->format('d-M-Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div>
        {{ $budgets->links() }}
    </div>
</div>
@endsection