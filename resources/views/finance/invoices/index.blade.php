@extends('master')
@section('title', 'Invoices')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Invoices</div>
        </div>
        <div class="page-title-actions">
            @can('create-invoice')
            <a href="{{ route('finance.invoices.create') }}" class="btn btn-download mb-3 vip-btn">
                <i class="bi bi-plus-circle"></i> Create
            </a>
            @endcan
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

<div class="mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('finance.invoices.index') }}" class="row">

            <!-- Search -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Invoice No. / Vendor</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search Invoice No / Vendor">
            </div>

            <!-- Status -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">By Status</label>
                <select name="status" class="form-control">
                    <option value="">-- All Status --</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date To</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date From</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- Buttons -->
            <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="submit" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
            </div>

        </form>
    </div>
</div>


<div class="table-responsive-lg">
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Vendor</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $key => $invoice)
            <tr class="text-center align-middle">
                <td>{{ $key + 1 }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                <td>{{ $invoice->vendor_name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') ?? '-' }}</td>
                <td>${{ number_format($invoice->amount, 2) }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
                <td>
                    @can('view-invoice')
                    @if ($invoice->attachment)
                    <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank"
                        class="btn btn-sm btn-info vip-btn">
                        <i class="bi bi-eye"></i> View
                    </a>
                    @else
                    N/A
                    @endif
                    @endcan
                </td>
                <td>

                    @can('update-invoice')
                    <a href="{{ route('finance.invoices.edit', $invoice->id) }}" class="btn btn-warning vip-btn mb-1">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan

                    @can('delete-invoice')
                    <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST"
                        class="d-inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger vip-btn mb-1">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endcan
                    <a href="{{ route('finance.invoices.show', $invoice->id) }}" class="btn btn-info vip-btn mb-1">
                        <i class="bi bi-eye"></i> View
                    </a>

                    <a href="{{ route('finance.invoices.download', $invoice->id) }}"
                        class="btn btn-secondary vip-btn mb-1">
                        <i class="bi bi-download"></i> Download
                    </a>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">No invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} entries
        </div>
        <div>
            {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection