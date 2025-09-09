@extends('master')

@section('content')
<div class="container">
    <h4 class="mb-4">Payments</h4>

    {{-- ✅ Alert Message Heading ke niche --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('finance.payments.create') }}" class="btn btn-primary mb-3">Add Payment</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>#{{ $payment->invoice_id }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>
                        @if ($payment->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </td>
                    <td>
                        {{-- ✅ Show button --}}
                        <a href="{{ route('finance.payments.show', $payment->id) }}" class="btn btn-info btn-sm">Show</a>

                        {{-- Only Admin can approve --}}
                        @if ($payment->status == 'pending' && auth()->user()->role == 'admin')
                            <form action="{{ route('finance.payments.approve', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                        @endif

                        {{-- Edit/Delete --}}
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
                    <td colspan="6" class="text-center">No payments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
