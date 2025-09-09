@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Payment</h2>

    <form action="{{ route('finance.payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice</label>
            <select name="invoice_id" id="invoice_id" class="form-select">
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ $payment->invoice_id == $invoice->id ? 'selected' : '' }}>
                        {{ $invoice->invoice_no }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" id="payment_date" value="{{ $payment->payment_date }}">
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount" value="{{ $payment->amount }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>

            @if(auth()->user()->role === 'admin')
                {{-- ✅ sirf admin ke liye editable --}}
                <select name="status" id="status" class="form-select">
                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            @else
                {{-- ❌ baqi users ke liye readonly --}}
                <input type="text" class="form-control" value="{{ ucfirst($payment->status) }}" disabled>
                <input type="hidden" name="status" value="{{ $payment->status }}">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
