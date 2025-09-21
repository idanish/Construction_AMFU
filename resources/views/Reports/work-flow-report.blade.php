@extends('master')

@section('content')
<div class="container">
    <h2>Workflow Report</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.workflows') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
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
        <a href="{{ route('reports.workflows.export.excel') }}" class="btn btn-success">Export Excel</a>
        <a href="{{ route('reports.workflows.export.pdf') }}" class="btn btn-danger">Export PDF</a>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Workflow Name</th>
                <th>Created By</th>
                <th>Department</th>
                <th>Status</th>
                <th>Started At</th>
                <th>Completed At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workflows as $wf)
            <tr>
                <td>{{ $wf->id }}</td>
                <td>{{ $wf->name }}</td>
                <td>{{ $wf->createdBy->name ?? 'N/A' }}</td>
                <td>{{ $wf->department->name ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $wf->status)) }}</td>
                <td>{{ $wf->created_at->format('Y-m-d') }}</td>
                <td>{{ $wf->completed_at ? $wf->completed_at->format('Y-m-d') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        {{ $workflows->appends(request()->query())->links() }}
    </div>
</div>
@endsection
