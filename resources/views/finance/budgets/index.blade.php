@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Budgets</h2>

    <a href="{{ route('finance.budgets.create') }}" class="btn btn-success mb-3">Add New Budget</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($budgets as $budget)
            <tr>
                <td>{{ $budget->id }}</td>
                <td>{{ $budget->title }}</td>
                <td>{{ $budget->amount }}</td>
                <td>{{ $budget->status }}</td>
                <td>
                    <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    
                    <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure to delete this budget?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No budgets found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection