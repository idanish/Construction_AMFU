@extends('master')

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
            <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Create Invoice
            </a>
        </div>
    </div>
</div>

<div class="table-responsive-lg">
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Procurement</th>
                <th>Vendor</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Notes</th>
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
                <td>{{ $invoice->procurement->item_name ?? '-' }}</td>
                <td>{{ $invoice->vendor_name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') ?? '-' }}</td>
                <td>{{ number_format($invoice->amount, 2) }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
                <td>{{ $invoice->notes ?? '-' }}</td>
                <td>
                    @if ($invoice->attachment)
                        <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank">View</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('finance.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">No invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
