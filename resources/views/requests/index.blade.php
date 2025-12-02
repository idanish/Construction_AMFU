@extends('master')
@section('title', 'Requets')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Requests</div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                @can('create-request')
                <a href="{{ route('requests.create') }}" class="btn btn-download mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Create
                </a>
                @endcan
            </div>
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
        <form method="GET" action="{{ route('requests.index') }}" class="row mb-2">

            <!-- Requestor -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Requestor</label>
                <select name="requestor_id" class="form-select">
                    <option value="">-- Select Requestor --</option>
                    <option value="">All Requestors</option>
                    @foreach ($allRequestors as $requestor)
                    <option value="{{ $requestor->id }}"
                        {{ request('requestor_id') == $requestor->id ? 'selected' : '' }}>
                        {{ $requestor->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label mb-0">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach (['Pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Date Range (From) --}}
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>

            {{-- Date Range (To) --}}
            <div class="col-md-2 col-sm-6">
                <label class="form-label mb-0">Date To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            {{-- Filter Button --}}
            <div class="btn-group col-md-2 col-sm-6" role="group" aria-label="First group">
                <button type="button" class="btn btn-secondary btn-sm vip-btn btn-filter"><I
                        class="bi bi-funnel"></I>Filter</button>
                <a href="{{ route('requests.index') }}" class="btn btn-secondary btn-sm vip-btn"><I
                        class="bi bi-eraser"></I>Clear</a>
            </div>
        </form>
    </div>
</div>


<div class="table-responsive-lg">
    <table class="table table-bordered table-striped">
        <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
            <tr>
                <th>S.No</th>
                <th>Title</th>
                <th>Requestor</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Current Step</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $key => $request)
            <tr class="text-center align-middle">
                <td>{{ $key + 1 }}</td>
                <td>{{ $request->title }}</td>
                <td>{{ $request->requestor->name ?? 'N/A' }}</td>
                <td>${{ number_format($request->amount) }}</td>
                <td>
                    @if($request->status === 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($request->status === 'reverted')
                        <span class="badge bg-danger">Reverted</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </td>
                <td><span class="badge bg-info">{{ $request->current_approval_step ?? 'N/A' }}</span></td>
                <td>{{ $request->created_at->format('d-M-Y h:i A') }}</td>
                <td>
                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-sm btn-primary vip-btn">
                        <i class="bi bi-eye"></i> View
                    </a>

                    @if($request->status === 'reverted' && $request->requestor_id === auth()->id())
                    <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-sm btn-warning vip-btn">
                        <i class="bi bi-pencil-square"></i> Resubmit
                    </a>
                    @elseif($request->status !== 'approved')
                    <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-sm btn-download vip-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endif

                    @if(auth()->id() === $request->requestor_id && $request->status !== 'approved')
                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="d-inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger vip-btn">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
    <div>
        {{ $requests->appends(request()->query())->links() }}
    </div>
</div>
@endsection