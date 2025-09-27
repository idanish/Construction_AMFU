@extends('master')

@section('title', 'Invoice Details')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div>Invoice Details</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4>Invoice #{{ $invoice->invoice_no }}</h4>
        <p><strong>Vendor:</strong> {{ $invoice->vendor_name }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</p>
        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') }}</p>
        <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>

        <a href="{{ route('finance.invoices.download', $invoice->id) }}" class="btn btn-info">
            <i class="bi bi-download"></i> Download PDF
        </a>
    </div>
</div>
@endsection
