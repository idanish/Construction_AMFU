@extends('master')

@section('content')
<div class="container">
    <h2>Finance Report</h2>

    {{-- Filters --}}
    <form method="GET" action="{{ route('reports.finance') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="department_id" class="form-control" placeholder="Department ID" value="{{ request('department_id') }}">
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-control">
                    <option value="25" {{ request('per_page')==25?'selected':'' }}>25</option>
                    <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
                    <option value="100" {{ request('per_page')==100?'selected':'' }}>100</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    {{-- Export Buttons --}}
    <div class="mb-3">
        <a href="#" class="btn btn-success">Export Excel</a>
        <a href="#" class="btn btn-danger">Export PDF</a>
    </div>

    {{-- Report Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Department</th>
                <th>Allocated</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Transaction No</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $index => $budget)
                <tr>
                    <td>{{ $budgets->firstItem() + $index }}</td>
                    <td>{{ $budget->department->name ?? 'N/A' }}</td>
                    <td>{{ $budget->allocated }}</td>
                    <td>{{ $budget->spent }}</td>
                    <td>{{ $budget->balance }}</td>
                    <td>{{ ucfirst($budget->status) }}</td>
                    <td>{{ $budget->transaction_no }}</td>
                    <td>{{ $budget->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $budgets->appends(request()->input())->links() }}
    </div>
</div>
@endsection
