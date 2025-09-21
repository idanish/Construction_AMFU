@extends('master')

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
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Export buttons -->
    <div class="mb-3">
        <a href="{{ route('reports.finance.export.excel') }}" class="btn btn-success">Export Excel</a>
        <a href="{{ route('reports.finance.export.pdf') }}" class="btn btn-danger">Export PDF</a>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Department</th>
                <th>Allocated</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Transaction</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td>{{ $budget->department->name ?? '-' }}</td>
                <td>{{ $budget->allocated }}</td>
                <td>{{ $budget->spent }}</td>
                <td>{{ $budget->balance }}</td>
                <td>{{ $budget->status }}</td>
                <td>{{ $budget->transaction_no }}</td>
                <td>{{ $budget->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $budgets->links() }}
</div>
@endsection
