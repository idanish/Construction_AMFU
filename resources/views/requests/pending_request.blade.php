@extends('master')
@section('title', 'Pending Requets')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Procurements --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">🛒 Pending Procurements</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
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
                    <tr class="text-center align-middle">
                        <td>{{ $procurement->id }}</td>
                        <td>{{ $procurement->item_name }}</td>
                        <td>{{ $procurement->quantity }}</td>
                        <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"class="btn btn-success vip-btn">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger vip-btn">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No pending procurements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pending Invoices --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">📑 Pending Invoices</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingInvoices as $invoice)
                    <tr class="text-center align-middle">
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No pending invoices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pending Payments --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">💰 Pending Payments</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Payee</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPayments as $payment)
                    <tr class="text-center align-middle">
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->payee_name }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst($payment->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No pending payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pending Budget --}}
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">📊 Pending Budget Requests</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingBudget as $budget)
                    <tr class="text-center align-middle">
                        <td>{{ $budget->id }}</td>
                        <td>{{ $budget->title }}</td>
                        <td>{{ number_format($budget->amount, 2) }}</td>
                        <td>{{ ucfirst($budget->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No pending budget requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

  @endsection