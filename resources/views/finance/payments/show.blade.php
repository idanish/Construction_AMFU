@extends('master')
@section('title', 'Payment Details')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Payment Details</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Payment ID:</strong></div>
                <div class="col-md-8">{{ $payment->id }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Payment Reference:</strong></div>
                <div class="col-md-8">{{ $payment->payment_ref }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Invoice:</strong></div>
                <div class="col-md-8">#{{ $payment->invoice->invoice_no ?? 'N/A' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Payment Date:</strong></div>
                <div class="col-md-8">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Amount:</strong></div>
                <div class="col-md-8">₨{{ number_format($payment->amount, 2) }}</div>

            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Status:</strong></div>
                <div class="col-md-8">
                    <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Created At:</strong></div>
                <div class="col-md-8">{{ $payment->created_at->format('d M, Y H:i') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Last Updated:</strong></div>
                <div class="col-md-8">{{ $payment->updated_at->format('d M, Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-download">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

{{-- Optional Styles --}}
<style>
.card-body .row {
    border-bottom: 1px solid #e9ecef;
    padding: 10px 0;
}
.card-body .row:last-child {
    border-bottom: none;
}
</style>
@endsection
