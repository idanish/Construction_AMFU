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
                <a href="{{ route('finance.payments.create') }}" class="btn btn-primary mb-3">
                    <i class="bi bi-plus-circle"></i> Add Payment
                </a>
            </div>
        </div>
    </div>

    {{-- Success & Error Messages --}}
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
            {{-- <th>Quantity</th> --}}
            <th>Cost Estimate</th>
            {{-- <th>Department</th> --}}
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
                {{-- <td>{{ optional($payment->invoice)->quantity ?? '-' }}</td> --}}
                <td>{{ optional($payment->invoice)->amount ?? '-' }}</td>
                {{-- <td>{{ optional($payment->invoice->department)->name ?? 'N/A' }}</td> --}}
                <td>
                    <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
                <td>
                    @if ($payment->attachment)
                        <a href="{{ asset('storage/payments/' . $payment->attachment) }}" target="_blank">View</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <a href="{{ route('finance.payments.edit', $payment->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('finance.payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
@endsection
