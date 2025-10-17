@extends('master')
@section('title', 'Payments')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Payments</div>
        </div>
        <div class="page-title-actions">
            @can('create-payment')
            <a href="{{ route('finance.payments.create') }}" class="btn btn-download mb-3 vip-btn">
                <i class="bi bi-plus-circle"></i> Create
            </a>
            @endcan
        </div>
    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


<div class="mb-2">
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('finance.payments.index') }}" class="row mb-2">
            <!-- Search -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search Payment Ref">
            </div>

            <!-- Single Date -->
            <!-- <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">By date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div> -->

            <!-- Date Range -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date to</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date from</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- Per Page -->
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">By Records</label>
                <select name="per_page" class="form-control">
                    @foreach ([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }} per page
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
              <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="submit" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
            </div>

            <!-- <div class="col-md-2">
                <button type="submit" class="vip-btn btn-filter">
                    <I class="bi bi-funnel"></I> Filter
                </button>
            </div> -->
        </form>
    </div>
</div>

<div class="table-responsive-lg">
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>No</th>
                <th>Payment Reference</th>
                <th>Invoice No</th>
                <th>Invoice Amount</th>
                <th>Payment</th>
                <th>Balance</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $key => $payment)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $payment->payment_ref }}</td>
                <td>{{ optional($payment->invoice)->invoice_no ?? 'N/A' }}</td>
                <td>${{ number_format($payment->invoice_amount, 2) }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>${{ number_format($payment->balance, 2) }}</td>
                <td>
                    @if ($payment->attachment)
                    <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank"
                        class="btn btn-sm btn-info vip-btn">
                        <i class="bi bi-eye"></i> View
                    </a>
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @can('update-payment')
                    <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-sm btn-download vip-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan
                    @can('delete-payment')
                    <form action="{{ route('finance.payments.destroy', $payment->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger vip-btn"
                            onclick="return confirm('Are you sure you want to delete this payment?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $payments->appends(request()->query())->links() }}
    </div>
</div>
@endsection