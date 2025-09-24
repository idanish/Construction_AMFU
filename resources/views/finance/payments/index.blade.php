@extends('master')

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
                <a href="{{ route('finance.payments.create') }}" class="btn btn-primary mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Create
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive-lg ">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Payment Reference</th>
                    <th>Invoice No</th>
                    <th>Invoice Amount</th>
                    <th>Payment</th>
                    <th>Balance</th>
                    <th>Status</th>
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
                        <td>{{ number_format($payment->invoice_amount, 2) }}</td> <!-- invoice amount -->
                        <td>{{ number_format($payment->amount, 2) }}</td> <!-- payment made -->
                        <td>{{ number_format($payment->balance, 2) }}</td> <!-- remaining balance -->
                        <td>
                            <span
                                class="badge bg-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'partial' ? 'info' : 'warning') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($payment->attachment)
                                <a href="{{ asset('storage/payments/' . $payment->attachment) }}" target="_blank" class="btn vip-btn">
                                   <i class="bi bi-eye"></i> View
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('finance.payments.edit', $payment->id) }}"
                                class="btn btn-sm btn-primary vip-btn">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('finance.payments.destroy', $payment->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger vip-btn" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
