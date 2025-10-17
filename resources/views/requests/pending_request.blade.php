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


{{-- Pending Requests --}}
@can('read-request')
<div class="table-responsive-lg mb-4">
    <h5 class="mb-3">💰 Pending Requests</h5>
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>S.NO.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Requestor</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingRequest as $key => $pendingRequest)
            <tr class="text-center align-middle">
                <td>{{ $key + 1 }}</td>
                <td>{{ $pendingRequest->title }}</td>
                <td>{{ $pendingRequest->description }}</td>
                <td>{{ $pendingRequest->title }}</td>
                <td>{{ $pendingRequest->requestor->name ?? 'N/A' }}</td>
                <td>
                    @can('approve-request')
                    <form action="{{ route('requests.updateStatus', $pendingRequest->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success vip-btn">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                    @endcan

                    @can('reject-request')
                    <form action="{{ route('requests.updateStatus', $pendingRequest->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-dark vip-btn">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No pending request found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endcan


{{-- Pending Procurements --}}
@can('read-procurement')
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
                    @can('approve-procurement')
                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success vip-btn">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                    @endcan

                    @can('reject-procurement')
                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger vip-btn">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    @endcan
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
@endcan

{{-- Unpaid Invoices --}}
@can('read-invoice')
<div class="table-responsive-lg mb-4">
    <h5 class="mb-3">📑 Unpaid Invoices</h5>
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
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ number_format($invoice->amount, 2) }}</td>
                <td>{{ ucfirst($invoice->status) }}</td>
                <td>
                    <a href="{{ route('finance.payments.index') }}" class="btn btn-download vip-btn">
                            <div>Payment</div>
                        </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No unaid invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endcan

{{-- Pending Budget --}}
@can('read-budget')
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
                    @can('approve-budget')
                    <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success vip-btn">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                    @endcan

                    @can('reject-budget')
                    <form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-dark vip-btn">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    @endcan
                    <!-- Status Change Buttons -->

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No pending budget requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endcan

@endsection