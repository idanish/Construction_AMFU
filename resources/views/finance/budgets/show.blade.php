@extends('master')
@section('title', 'View Budget')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Budget Details</div>
        </div>
        
        <div class="page-title-actions">
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            @can('update-budget')
            <a href="{{ route('finance.budgets.edit', $budget->id) }}" class="btn btn-warning vip-btn">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan

        </div>
    </div>
</div>

{{-- Alerts --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- Budget Info --}}
    <div class="col-md-6">
        <div class="mb-3">
            <div class="card-header fw-bold bg-light">
                <i class="bi bi-info-circle"></i> Budget Information
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="40%">Department</th>
                        <td>{{ $budget->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Month</th>
                        <td>{{ \Carbon\Carbon::create()->month($budget->month)->format('F') }}</td>
                    </tr>
                    <tr>
                        <th>Year</th>
                        <td>{{ $budget->year }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge 
                                {{ $budget->status == 'approved' ? 'bg-success' : 
                                   ($budget->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Financial Info --}}
    <div class="col-md-6">
        <div class=" mb-3">
            <div class="card-header fw-bold bg-light">
                <i class="bi bi-cash-stack"></i> Financial Summary
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="40%">Allocated</th>
                        <td>${{ number_format($budget->allocated, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Spent</th>
                        <td>${{ number_format($budget->spent, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Balance</th>
                        <td>${{ number_format($budget->balance, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Notes --}}
@if($budget->notes)
<div class=" mb-3 ">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-journal-text"></i> Notes
    </div>
    <div class="card-body">
        {{ $budget->notes }}
    </div>
</div>
@endif

{{-- Attachments --}}
<div class=" mb-3 ">
    <div class="card-header fw-bold bg-light">
        <i class="bi bi-paperclip"></i> Attachments
    </div>
    <div class="card-body">
        @can('view attachment')
        @forelse($budget->getMedia('attachments') as $media)
        <a href="{{ $media->getUrl() }}" target="_blank" class="d-block mb-1">
            <i class="bi bi-paperclip"></i> {{ $media->file_name }}
        </a>
        @empty
        <span class="text-muted">No attachments available.</span>
        @endforelse
        @else
        <span class="text-muted">You are not allowed to view attachments.</span>
        @endcan
    </div>
</div>

@if( ucfirst($budget->status) == 'Pending')

@can('approve-budget')
<form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="status" value="approved">
    <button type="submit" class="btn btn-success vip-btn">
        <i class="bi bi-check-circle"></i> Approve
    </button>
</form>
@endcan

@can('reject-budget')
<form action="{{ route('finance.budget.updateStatus', $budget->id) }}" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="status" value="rejected">
    <button type="submit" class="btn btn-dark vip-btn">
        <i class="bi bi-x-circle"></i> Reject
    </button>
</form>
@endcan
@endif

@endsection