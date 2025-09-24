@extends('master')
@section('title', 'Procurement reports')
@section('content')
<div class="container">
    <h1 class="mb-4">Procurement Report</h1>

    {{-- Filters --}}
    <form method="GET" action="{{ route('reports.procurement') }}" class="mb-4 row g-3">
        <div class="col-md-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">-- All --</option>
                @foreach(\App\Models\Department::all() as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
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
        <div class="col-md-3">
            <label>Min Cost</label>
            <input type="number" step="0.01" name="cost_min" value="{{ request('cost_min') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Max Cost</label>
            <input type="number" step="0.01" name="cost_max" value="{{ request('cost_max') }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary vip-btn btn-filter">  <I class="bi bi-funnel"></I> Filter
</button>
        </div>
    </form>

    {{-- Export Buttons --}}
    <div class="mb-3">
        <a href="{{ route('reports.procurement.export.excel') }}" class="btn btn-success vip-btn btn-excel">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('reports.procurement.export.pdf') }}" class="btn btn-danger vip-btn btn-pdf">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    {{-- Report Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Cost Estimate</th>
                <th>Department</th>
                <th>Remarks</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($procurements as $procurement)
                <tr>
                    <td>{{ $procurement->item_name }}</td>
                    <td>{{ $procurement->quantity }}</td>
                    <td>{{ number_format($procurement->cost_estimate, 2) }}</td>
                    <td>{{ optional($procurement->department)->name ?? 'N/A' }}</td>
                    <td>{{ $procurement->justification ?? '-' }}</td>
                    <td>{{ $procurement->created_at->format('d-M-Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No procurement records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $procurements->links() }}
</div>
@endsection
