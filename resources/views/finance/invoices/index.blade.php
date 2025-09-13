@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Invoices</h2>
    <a href="{{ route('finance.invoices.create') }}" class="btn btn-success mb-3">Add New Invoice</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Vendor</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->date->format('d-m-Y') }}</td>
                <td>{{ $invoice->vendor }}</td>
                <td>{{ number_format($invoice->amount, 2) }}</td>
                <td>{{ Str::limit($invoice->description, 50) }}</td>
                <td>
                    @if($invoice->attachment)
                        <a href="{{ asset('storage/'.$invoice->attachment) }}" target="_blank">View</a>
                    @else
                        N/A
                    @endif
                </td>
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
                <td colspan="8" class="text-center">No invoices found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    
</div>
@endsection
