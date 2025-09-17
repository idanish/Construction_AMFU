@extends('master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Payments</h4>

    <!-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif -->

    <a href="{{ route('finance.payments.create') }}" class="btn btn-primary mb-3">Add Payment</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Payment Ref</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Method</th> {{-- Naya Column --}}
                <th>Status</th>
                <th>Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->payment_ref }}</td>
                    <td>#{{ $payment->invoice->invoice_no ?? $payment->invoice_id }}</td>
                    <td>₨{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ ucfirst($payment->method ?? 'N/A') }}</td> {{-- Payment Method --}}
                    <td>
                        @if ($payment->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </td>
                    <td>
                        @if ($payment->attachment)
                            <a href="{{ asset('storage/' . $payment->attachment) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        @else
                            <span class="badge bg-secondary">No File</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('finance.payments.show', $payment->id) }}" class="btn btn-info btn-sm">Show</a>
                        <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('finance.payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No payments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
