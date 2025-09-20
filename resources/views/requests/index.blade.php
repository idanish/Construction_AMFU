@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Requests</h2>
    <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3">Create Request</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>S.No</th>
                <th>Requestor</th>
                <th>Department</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach($requests as $req)
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $req->requestor->name ?? '-' }}</td>
                <td>{{ $req->department->name ?? '-' }}</td>
                <td>{{ $req->description }}</td>
                <td>{{ $req->amount }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>
                    <a href="{{ route('requests.show', $req->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('requests.edit', $req->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('requests.destroy', $req->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    @if(auth()->user()->role == 'FCO' || auth()->user()->role == 'PMO' || auth()->user()->role == 'CSO')
                        @if($req->status == 'pending' || $req->status == 'under_review')
                            <a href="{{ route('approvals.create', $req->id) }}" class="btn btn-success btn-sm mt-1">Approve / Reject</a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
