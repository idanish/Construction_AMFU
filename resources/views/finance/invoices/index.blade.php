@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Invoices</h2>
    <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary mb-3">Add New Invoice</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
    <tr>
        <th>ID</th>
        <th>Invoice No</th>
        <th>Date</th>
        <th>Request</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Notes</th>
        <th>Attachment</th>
        <th>Download</th> {{-- 👈 New Column --}}
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @forelse($invoices as $invoice)
    <tr>
        <td>{{ $invoice->id }}</td>
        <td>{{ $invoice->invoice_no }}</td>
        <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') : 'N/A' }}</td>
        <td>{{ $invoice->serviceRequest->title ?? 'Request #'.$invoice->serviceRequest->id ?? 'N/A' }}</td>
        <td>{{ number_format($invoice->amount, 2) }}</td>
        <td>
            <span class="badge {{ $invoice->status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $invoice->status }}
            </span>
        </td>
        <td>{{ $invoice->notes ? \Illuminate\Support\Str::limit($invoice->notes, 50, '...') : 'N/A' }}</td>
        <td>
            @if($invoice->attachment)
                <a href="{{ asset('storage/'.$invoice->attachment) }}" target="_blank" class="btn btn-info btn-sm">
                    <i class="bi bi-eye"></i> View
                </a>
            @else
                <span class="badge bg-secondary">
                    <i class="bi bi-file-earmark-excel"></i> No File
                </span>
            @endif
        </td>

        {{-- 👇 New Download Column --}}
        <td>
    <a href="#" 
   class="btn btn-primary">
   Download PDF
</a>


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
        <td colspan="10" class="text-center">No invoices found</td>
    </tr>
    @endforelse
</tbody>

    </table>
</div>
@endsection
