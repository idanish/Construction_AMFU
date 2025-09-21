@extends('master')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0"><span>Procurements</span></div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.procurements.create') }}" class="btn btn-primary mb-3">Create Procurement</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Cost Estimate</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($procurements as $key => $proc)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $proc->item_name }}</td>
                            <td>{{ $proc->quantity }}</td>
                            <td>{{ number_format($proc->cost_estimate) }}</td>
                            <td>{{ $proc->department->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($proc->status) }}</td>
                            <td>
                                @if ($proc->attachment)
                                    <a href="{{ asset('storage/' . $proc->attachment) }}" target="_blank">View</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('finance.procurements.edit', $proc->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('finance.procurements.destroy', $proc->id) }}" method="POST"
                                    class="d-inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this procurement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
