@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Invoice</h2>

    <form action="{{ route('finance.invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="request_id" class="form-label">Request ID</label>
            <input type="number" name="request_id" class="form-control" id="request_id" value="{{ old('request_id', $invoice->request_id) }}">
            @error('request_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="invoice_no" class="form-label">Invoice No</label>
            <input type="text" name="invoice_no" class="form-control" id="invoice_no" value="{{ old('invoice_no', $invoice->invoice_no) }}">
            @error('invoice_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount" value="{{ old('amount', $invoice->amount) }}">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <input type="text" class="form-control" value="{{ $invoice->status }}" disabled>
    <input type="hidden" name="status" value="{{ $invoice->status }}">
</div>


        <button type="submit" class="btn btn-success">Update Invoice</button>
        <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
