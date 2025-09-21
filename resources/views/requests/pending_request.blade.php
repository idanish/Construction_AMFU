@extends('master') @section('content')
<div class="container">
    <h1>Pending Requests Dashboard</h1>

    <hr>

    <h2>Pending Procurements</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingProcurement as $procurement)
            <tr>
                <td>{{ $procurement->id }}</td>
                <td>{{ $procurement->item_name }}</td>
                <td>{{ $procurement->quantity }}</td>
                <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                <td>
                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>

                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No pending procurements found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Pending Invoices</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice Number</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingInvoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->amount }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No pending invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Pending Payments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Payee</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingPayments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->payee_name }}</td>
                <td>{{ $payment->amount }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No pending payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2>Pending Budget Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingBudget as $budget)
            <tr>
                <td>{{ $budget->id }}</td>
                <td>{{ $budget->title }}</td>
                <td>{{ $budget->amount }}</td>
                <td>{{ ucfirst($budget->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No pending budget requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection