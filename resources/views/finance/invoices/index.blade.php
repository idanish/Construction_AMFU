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
                <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Create
                </a>
                @endcan
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
                        <td>{{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td>
                            @if ($invoice->attachment)
                                <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank" class="btn btn-sm btn-info vip-btn">
                                   <i class="bi bi-eye"></i> View
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>

                            <!-- @can('view-invoice')
                            <a href="{{ route('finance.invoices.show', $invoice->id) }}"
                                class="btn  btn-info vip-btn">
                                <i class="bi bi-pencil-square"></i> View
                            </a>
                            @endcan -->

                            @can('update-invoice')
                            <a href="{{ route('finance.invoices.edit', $invoice->id) }}"
                                class="btn  btn-warning vip-btn">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            @endcan

                            @can('delete-invoice')
                            <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST"
                                class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger vip-btn">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                            @endcan
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
