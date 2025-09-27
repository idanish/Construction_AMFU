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
                    @can('create-procurement')
                        <a href="{{ route('finance.procurements.create') }}" class="btn btn-primary mb-3 vip-btn">
                            <i class="bi bi-plus-circle"></i> Create
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    {{-- filter --}}
    <div class="card-body">

        <!-- 🔹 Filter Form -->
        <form method="GET" action="{{ route('finance.procurements.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search Item"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-control">
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="vip-btn btn-filter">
                    <I class="bi bi-funnel"></I> Filter
                </button>
            </div>
        </form>
        <div class="main-card mb-3">
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
                        @foreach ($procurements as $key => $procurement)
                            <tr>
                                <td>{{ $procurements->firstItem() + $key }}</td>
                                <td>{{ $procurement->item_name }}</td>
                                <td>{{ $procurement->quantity }}</td>
                                <td>{{ $procurement->cost_estimate }}</td>
                                <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($procurement->status) }}</td>
                                <td>
                                    <!-- Status Change Buttons -->
                                    @if ($procurement->status === 'pending')
                                        @can('approve-procurement')
                                            <form action="{{ route('procurement.updateStatus', $procurement->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"class="btn btn-success vip-btn">
                                                    <i class="bi bi-check-circle"></i> Approve
                                                </button>
                                            </form>
                                        @endcan

                                        @can('reject-procurement')
                                            <form action="{{ route('procurement.updateStatus', $procurement->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-dark vip-btn">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </form>
                                        @endcan
                                        <br><br>
                                    @endif
                                    <!-- Status Change Buttons -->

                                    @can('update-procurement')
                                        <a href="{{ route('finance.procurements.edit', $procurement->id) }}"
                                            class="btn btn-sm btn-primary vip-btn">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endcan

                                    @can('delete-procurement')
                                        <form action="{{ route('finance.procurements.destroy', $procurement->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger vip-btn"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- 🔹 Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $procurements->links() }}
                </div>
            </div>
        </div>
    @endsection
