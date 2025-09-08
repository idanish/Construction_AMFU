@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Budgets List</h2>

    <a href="{{ route('finance.budgets.create') }}" class="btn btn-success mb-3">Add New Budget</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Allocated</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($budgets as $budget)
                <tr>
                    <td>{{ $budget->id }}</td>
                    <td>{{ $budget->department->name ?? 'N/A' }}</td>
                    <td>{{ $budget->allocated }}</td>
                    <td>{{ $budget->spent }}</td>
                    <td>{{ $budget->balance }}</td>
                    <td>{{ $budget->status }}</td>
                    <td>
                        <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No budgets found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
