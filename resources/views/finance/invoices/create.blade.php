@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Invoice</h2>

    <form action="{{ route('finance.invoices.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="request_id" class="form-label">Select Request</label>
            <select name="request_id" class="form-select">
    <option value="">Select Service Request</option>
    @foreach($requests as $request)
        <option value="{{ $request->id }}">{{ $request->title ?? $request->id }}</option>
    @endforeach
</select>

            @error('request_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="invoice_no" class="form-label">Invoice No</label>
            <input type="text" name="invoice_no" class="form-control" id="invoice_no">
            @error('invoice_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save Invoice</button>
        <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
