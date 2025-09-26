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
                    <th>S.NO.</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingProcurement as $key => $procurement)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
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
                    <th>S.NO.</th>
                    <th>Invoice Number</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingInvoices as $key => $invoice)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ number_format($invoice->amount, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td></td>
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
                    <th>S.NO.</th>
                    <th>Payee</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPayments as $key => $payment)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $payment->payee_name }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst($payment->status) }}</td>
                        <td></td>
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
                    <th>S.NO.</th>
                    <th>Department</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingBudget as $key => $budget)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $budget->department->name ?? 'N/A' }}</td>
                        <td>{{ number_format($budget->balance, 2) }}</td>
                        <td>{{ ucfirst($budget->status) }}</td>
                        <td>
                        <!-- Status Change Buttons -->
                         @if ($budget->status === 'pending')
                            <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"class="btn btn-success vip-btn">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            </form>

                            <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-dark vip-btn">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>
                        @endif
                        <!-- Status Change Buttons -->

                        </td>
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