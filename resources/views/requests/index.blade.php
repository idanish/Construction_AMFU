@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Requests</h2>

    {{-- Normal users aur Admin dono create kar sakte hain --}}
    <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3">Create New Request</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Requestor</th>
                <th>Department</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->requestor->name ?? '-' }}</td>
                <td>{{ $req->department->name ?? '-' }}</td>
                <td>{{ $req->description }}</td>
                <td>{{ $req->amount }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>
                    {{-- Har koi view kar sakta hai --}}
                    <a href="{{ route('requests.show', $req->id) }}" class="btn btn-info btn-sm">View</a>

                    {{-- Sirf Admin ko Edit/Delete button dikhana --}}
                     @if(auth()->user()->role == 'admin')
                         <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('requests.destroy', $request->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                   @endif
                    {{-- Approval buttons sirf FCO, PMO, CSO ke liye --}}
                    @if(auth()->user()->hasAnyRole(['FCO','PMO','CSO']))
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
