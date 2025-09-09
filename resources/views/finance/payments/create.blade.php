@extends('master')

@section('content')
<div class="container">
    <h4 class="mb-4">Add Payment</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('finance.payments.store') }}" method="POST">
        @csrf

        {{-- Invoice --}}
        <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice</label>
            <select name="invoice_id" id="invoice_id" class="form-control" required>
                <option value="">Select Invoice</option>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}">
                        #{{ $invoice->id }} - {{ $invoice->customer_name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Payment Date --}}
        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
        </div>

        {{-- Amount --}}
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
        </div>

        {{-- Status (always pending & disabled) --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" value="pending" class="form-control" disabled>
            <input type="hidden" name="status" value="pending">
        </div>

        <button type="submit" class="btn btn-primary">Save Payment</button>
    </form>
</div>
@endsection
