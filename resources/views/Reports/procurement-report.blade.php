@extends('master')

@section('content')
<div class="container">
    <h2>Procurement Analysis Report</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.procurement') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label>Supplier</label>
                <input type="text" name="supplier_id" value="{{ request('supplier_id') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                </select>
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
        <a href="{{ route('reports.procurement.export.excel') }}" class="btn btn-success">Export Excel</a>
        <a href="{{ route('reports.procurement.export.pdf') }}" class="btn btn-danger">Export PDF</a>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Date</th>
                <th>Total Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procurements as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->supplier->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->procurement_date }}</td>
                <td>{{ $p->items->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        {{ $procurements->appends(request()->query())->links() }}
    </div>
</div>
@endsection
