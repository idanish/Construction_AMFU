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

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive-lg">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark text-center align-middle fw-bold bg-light text-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Requestor</th>
                        <th>Department</th>
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
                            <td>{{ $request->department->name ?? 'N/A' }}</td>
                            <td>{{ number_format($request->amount) }}</td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>{{ $request->created_at->format('d-m-Y') }}</td>
                            <td>

                            <!-- Status Change Buttons -->
                                
                                <!-- Status Change Buttons -->

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
                                    <button type="submit"class="btn btn-danger vip-btn">
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
