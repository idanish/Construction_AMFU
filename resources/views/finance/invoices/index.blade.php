@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Invoices</h2>
    <a href="{{ route('finance.invoices.create') }}" class="btn btn-success mb-3">Add New Invoice</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Request ID</th>
                <th>Invoice No</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->request_id }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->amount }}</td>
                <td>{{ $invoice->status }}</td>
                <td>
                    <a href="{{ route('finance.invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No invoices found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
