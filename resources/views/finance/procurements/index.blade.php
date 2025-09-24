@extends('master')
@section('title', 'Procurement')
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
                    <a href="{{ route('finance.procurements.create') }}" class="btn btn-primary mb-3 vip-btn">
                        <i class="bi bi-plus-circle"></i> Create

                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Cost Estimate</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($procurements as $procurement)
                        <tr>
                            <td>{{ $procurement->id }}</td>
                            <td>{{ $procurement->item_name }}</td>
                            <td>{{ $procurement->quantity }}</td>
                            <td>{{ $procurement->cost_estimate }}</td>
                            <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($procurement->status) }}</td>
                            <td>
                                <!-- Status Change Buttons -->
                                @if ($procurement->status === 'pending')
                                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit"class="btn btn-success vip-btn">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('procurement.updateStatus', $procurement->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger vip-btn">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                @endif
                                <!-- Status Change Buttons -->

                                <a href="{{ route('finance.procurements.edit', $procurement->id) }}"
                                    class="btn btn-sm btn-primary vip-btn">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('finance.procurements.destroy', $procurement->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger vip-btn"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
