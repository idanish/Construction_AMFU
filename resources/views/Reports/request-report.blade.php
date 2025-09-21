@extends('master')

@section('content')
<div class="container">
    <h2>Requests Report</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.requests') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Department</label>
                <input type="text" name="department_id" value="{{ request('department_id') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Export Buttons -->
    <div class="mb-3">
        <a href="{{ route('reports.requests.export.excel') }}" class="btn btn-success">Export Excel</a>
        <a href="{{ route('reports.requests.export.pdf') }}" class="btn btn-danger">Export PDF</a>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Department</th>
                <th>Status</th>
                <th>Requested At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->user->name ?? 'N/A' }}</td>
                <td>{{ $req->department->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>{{ $req->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        {{ $requests->appends(request()->query())->links() }}
    </div>
</div>
@endsection
