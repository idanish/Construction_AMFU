@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Invoice Details</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $invoice->id }}</td>
        </tr>
        <tr>
            <th>Request ID</th>
            <td>{{ $invoice->request_id }}</td>
        </tr>
        <tr>
            <th>Invoice No</th>
            <td>{{ $invoice->invoice_no }}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $invoice->amount }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $invoice->status }}</td>
        </tr>
    </table>

    <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
