@extends('master')
@section('title', 'Budget')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">Budgets</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary mb-3 vip-btn">
                        <i class="bi bi-plus-circle"></i> Create
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="table-responsive-lg ">
        <table id="budgetsTable" class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark ">
                <tr class="text-center align-middle fw-bold ">
                    <th>No</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Allocated</th>
                    <th>Spent</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Attachment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgets as $key => $budget)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $budget->department->name ?? 'N/A' }}</td>
                        <td>{{ $budget->year }}</td>
                        <td>{{ number_format($budget->allocated) }}</td>
                        <td>{{ number_format($budget->spent) }}</td>
                        <td>{{ number_format($budget->balance) }}</td>
                        <td>{{ ucfirst($budget->status) }}</td>
                        <td>
                            @if ($budget->attachment)
                                <a href="{{ asset('storage/' . $budget->attachment) }}" target="_blank" class="btn  btn-info vip-btn">
                                    <i class="bi bi-eye"></i> View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('finance.budgets.edit', $budget->id) }}"
                                class="btn  btn-warning vip-btn">
                               <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST"
                                class="d-inline-block"
                                onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                @csrf
                                @method('DELETE')
                                <button type = "submit" class="btn btn-danger vip-btn">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if ($budgets->count() == 0)
                    <tr>
                        <td colspan="9" class="text-center">No budgets found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
