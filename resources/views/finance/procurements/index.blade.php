@extends('master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Procurements</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('finance.procurements.create') }}" class="btn btn-primary mb-3">Add Procurement</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Status</th>
                <th>Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($procurements as $procurement)
                <tr>
                    <td>{{ $procurement->id }}</td>
                    <td>{{ $procurement->title }}</td>
                    <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                    <td>
                        @if($procurement->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($procurement->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($procurement->attachment)
                            <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                        @else
                            <span class="text-muted">No File</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('finance.procurements.show', $procurement->id) }}" class="btn btn-info btn-sm">Show</a>
                        <a href="{{ route('finance.procurements.edit', $procurement->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('finance.procurements.destroy', $procurement->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No procurements found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
