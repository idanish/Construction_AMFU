@extends('master')
@section('title', 'Rejected Requets')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-close-circle icon-gradient bg-love-kiss"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejected Procurements --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">🛑 Rejected Procurements</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Department</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedProcurement as $procurement)
                    <tr class="text-center align-middle">
                        <td>{{ $procurement->id }}</td>
                        <td>{{ $procurement->item_name }}</td>
                        <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($procurement->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No rejected procurements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Rejected Invoices --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">📑 Rejected Invoices</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Amount</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedInvoices as $invoice)
                    <tr class="text-center align-middle">
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No rejected invoices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Rejected Payments --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">💰 Rejected Payments</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Payee</th>
                    <th>Amount</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedPayments as $payment)
                    <tr class="text-center align-middle">
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->payee_name }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No rejected payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Rejected Budget Requests --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">📊 Rejected Budget Requests</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Amount</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedBudget as $budget)
                    <tr class="text-center align-middle">
                        <td>{{ $budget->id }}</td>
                        <td>{{ $budget->title }}</td>
                        <td>{{ number_format($budget->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($budget->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No rejected budget requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
