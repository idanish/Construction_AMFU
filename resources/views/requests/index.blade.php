@extends('master')
@section('title', 'Requets')
@section('content')
<div class="container mt-4">
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
                    <a href="{{ route('requests.create') }}" class="btn btn-primary mb-3 vip-btn">
                        <i class="bi bi-plus-circle"></i> Create
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive-lg">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Requestor</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
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
                    <td>{{ $request->description}}</td>
                    <td>{{ number_format($request->amount) }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->created_at->format('d-m-Y') }}</td>
                    <td>

                        <!-- Status Change Buttons -->
                        @if ($request->status === 'Pending' || $request->status === 'pending')
                        @can('approve-request')
                        <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success vip-btn">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                        @endcan

                        @can('reject-request')
                        <form action="{{ route('requests.updateStatus', $request->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-dark vip-btn">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </form>
                        @endcan
                        <br><br>
                        @endif

                        @can('update-request')
                        <a href="{{ route('requests.edit', $request->id) }}" class="btn btn-sm btn-warning vip-btn">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        @endcan

                        @can('delete-request')
                        <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                            class="d-inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this request?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger vip-btn">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection