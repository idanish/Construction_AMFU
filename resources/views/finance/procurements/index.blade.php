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
            @can('create-procurement')
            <a href="{{ route('finance.procurements.create') }}" class="btn btn-download mb-3 vip-btn">
                <i class="bi bi-plus-circle"></i> Create
            </a>
            @endcan
        </div>
    </div>
</div>


{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


{{-- filter --}}
<div class="mb-2">
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('finance.procurements.index') }}" class="row mb-2">

            <!-- Search -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search Item"
                    value="{{ request('search') }}">
            </div>

            <!-- Department -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Department</label>
                <select name="department_id" class="form-control">
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">By Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="submit" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table datatable table-bordered table-striped table-sm w-100">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Cost Estimate</th>
                <th>Unit Price</th>
                <th>Department</th>
                <th>Attachment</th>
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
                <td>${{ $procurement->cost_estimate }}</td>
                <td>${{ $procurement->quantity > 0 
            ? number_format($procurement->cost_estimate / $procurement->quantity, 2) 
            : 'N/A' }} </td>
                <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                <td>
                    @can('view attachment')
                     @foreach($procurement->getMedia('attachments') as $media)
            <a href="{{ $media->getUrl() }}"  target="_blank" title="{{ $media->file_name }}">
                <i class="bi bi-paperclip"></i> {{ $media->file_name }}</a><br>
        @endforeach
                    @endcan

                </td>
                <td>{{ ucfirst($procurement->status) }}</td>
                <td>
                    <a href="{{ route('finance.procurements.show', $procurement->id) }}" class="btn btn-success vip-btn mb-1">
                    <i class="fas fa-eye"></i> View</a>

                    @can('delete-procurement')
                    <form action="{{ route('finance.procurements.destroy', $procurement->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger vip-btn mb-1"
                            onclick="return confirm('Are you sure you want to delete this procurement?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <!-- <div class="d-flex justify-content-between align-items-center mt-3">
        {{ $procurements->links() }}
    </div> -->
</div>
@endsection