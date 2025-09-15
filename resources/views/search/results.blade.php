@extends('master')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Search Results for: <strong>{{ $query }}</strong></h1>

    @if($budgets->isEmpty() && $invoices->isEmpty())
        <div class="alert alert-warning">
            Sorry, no results found for "<strong>{{ $query }}</strong>".
        </div>
    @else
        @if($budgets->isNotEmpty())
            <h2>Budgets</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Transaction No</th>
                        <th>Status</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budgets as $budget)
                        <tr>
                            <td>{{ $budget->transaction_no }}</td>
                            <td>{{ $budget->status }}</td>
                            <td>{{ $budget->balance }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($invoices->isNotEmpty())
            <h2>Invoices</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->amount }}</td>
                            <td>{{ $invoice->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
</div>
@endsection
