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
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                <button type="submit"class="btn btn-primary vip-btn btn-filter">  <I class="bi bi-funnel"></I> Filter
</button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-3">
            <a href="{{ route('reports.requests.export.excel') }}" class="btn btn-success vip-btn btn-excel">
                <i class="bi bi-file-earmark-excel"></i> Export Excel

            </a>
            <a href="{{ route('reports.requests.export.pdf') }}" class="btn btn-danger vip-btn btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF

            </a>
        </div>

        <!-- Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                        <td>{{ $req->department->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($req->status) }}</td>
                        <td>{{ $req->created_at->format('d-M-Y h:i A') }}</td>
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
