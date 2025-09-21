@extends('master') @section('content')
<div class="container">
    <h1>Rejected Requests Dashboard</h1>

    <hr>

    <h2>Rejected Procurements</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Department</th>
                <th>Rejection Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rejectedProcurement as $procurement)
            <tr>
                <td>{{ $procurement->id }}</td>
                <td>{{ $procurement->item_name }}</td>
                <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                <td>{{ $procurement->updated_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No rejected procurements found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Rejected Invoices</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice Number</th>
                <th>Amount</th>
                <th>Rejection Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rejectedInvoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->amount }}</td>
                <td>{{ $invoice->updated_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No rejected invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Rejected Payments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Payee</th>
                <th>Amount</th>
                <th>Rejection Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rejectedPayments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->payee_name }}</td>
                <td>{{ $payment->amount }}</td>
                <td>{{ $payment->updated_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No rejected payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Rejected Budget Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Rejection Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rejectedBudget as $budget)
            <tr>
                <td>{{ $budget->id }}</td>
                <td>{{ $budget->title }}</td>
                <td>{{ $budget->amount }}</td>
                <td>{{ $budget->updated_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No rejected budget requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection