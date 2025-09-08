@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Service Requests</h2>
    <a href="{{ route('services.create') }}" class="btn btn-primary mb-3">Add New Service Request</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Request No</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviceRequests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->request_no }}</td>
                <td>{{ $request->description }}</td>
                <td>{{ $request->status }}</td>
                <td>{{ $request->created_at->format('d-m-Y') }}</td>
                <td>
                    <a href="{{ route('services.edit', $request->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('services.show', $request->id) }}" class="btn btn-sm btn-info">View</a>
                    <form action="{{ route('services.destroy', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
