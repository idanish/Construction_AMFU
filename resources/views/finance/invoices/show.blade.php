@extends('master')

@section('title', 'Invoice Details')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">
                Invoice Details
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary mb-3 vip-btn">
                    <i class="bi bi-arrow-left-circle"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="card-body">
        <h4>Invoice #{{ $invoice->invoice_no }}</h4>
        <p><strong>Vendor:</strong> {{ $invoice->vendor_name }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</p>
        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') }}</p>
        <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>
        <!-- <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p> -->

        <a href="{{ route('finance.invoices.download', $invoice->id) }}" class="btn btn-info">
            <i class="bi bi-download"></i> Download PDF
        </a>
    </div>
</div>



@endsection