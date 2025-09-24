@extends('master')
@section('title', 'Search Result')
@section('content')
    <div class="container">
        <h1>Search Results for "{{ $query }}"</h1>

        @if($budgets->isEmpty() && $invoices->isEmpty())
            <p>Sorry No result found for this  "{{ $query }}" keyword.</p>
        @else
            @if($budgets->isNotEmpty())
                <h2>Budgets</h2>
                <ul>
                    @foreach($budgets as $budget)
                        {{-- Budget ka display --}}
                        <li>Transaction No: {{ $budget->transaction_no }} - Status: {{ $budget->status }} - Balance: {{ $budget->balance }}</li>
                    @endforeach
                </ul>
            @endif

            @if($invoices->isNotEmpty())
                <h2>Invoices</h2>
                <ul>
                    @foreach($invoices as $invoice)
                        {{-- Invoice ka display --}}
                        <li>Invoice No: {{ $invoice->invoice_no }} - Amount: {{ $invoice->amount }} - Status: {{ $invoice->status }}</li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>
@endsection