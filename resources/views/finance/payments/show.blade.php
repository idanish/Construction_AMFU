@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Payment Details</h2>

    <div class="card shadow-sm p-4">
        <div class="mb-3">
            <strong>ID:</strong> {{ $payment->id }}
        </div>
        <div class="mb-3">
            <strong>Invoice:</strong> {{ $payment->invoice->invoice_no ?? 'N/A' }}
        </div>
        <div class="mb-3">
            <strong>Payment Date:</strong> {{ $payment->payment_date }}
        </div>
        <div class="mb-3">
            <strong>Amount:</strong> {{ $payment->amount }}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> 
            <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                {{ ucfirst($payment->status) }}
            </span>
        </div>
        <div class="mb-3">
            <strong>Created At:</strong> {{ $payment->created_at->format('d M, Y H:i') }}
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>
@endsection
