@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Departments</h2>
    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Add Department</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
            <tr>
                <td>{{ $dept->id }}</td>
                <td>{{ $dept->name }}</td>
                <td>{{ $dept->description }}</td>
                <td>{{ $dept->created_at->format('d-m-Y') }}</td>
                <td>{{ $dept->updated_at->format('d-m-Y') }}</td>
                <td>
                    <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" style="display:inline-block;">
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