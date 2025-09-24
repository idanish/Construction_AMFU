@extends('master')
@section('title', 'Audit Report')
@section('content')
    <div class="container">
        <h2>Audit Report</h2>

        <!-- Filters -->
        <form method="GET" action="{{ route('reports.audit') }}" class="row g-3 mb-3">
            <div class="col-md-3">
                <input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{ request('user_id') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="role" class="form-control" placeholder="Role" value="{{ request('role') }}">
            </div>
            <div class="col-md-3">
                <select name="action" class="form-control">
                    <option value="">--Action--</option>
                    <option value="created" @selected(request('action') == 'created')>Created</option>
                    <option value="updated" @selected(request('action') == 'updated')>Updated</option>
                    <option value="deleted" @selected(request('action') == 'deleted')>Deleted</option>
                    <option value="login" @selected(request('action') == 'login')>Login</option>
                    <option value="logout" @selected(request('action') == 'logout')>Logout</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary vip-btn btn-filter">
                      <I class="bi bi-funnel"></I> Filter

                </button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-3 text-end">
            <a href="{{ route('reports.audit.export.excel') }}" class="btn btn-success vip-btn btn-excel">
                <i class="bi bi-file-earmark-excel"></i> Export Excel

            </a>
            <a href="{{ route('reports.audit.export.pdf') }}" class="btn btn-danger vip-btn btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF

            </a>
        </div>

        <!-- Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $activity->causer?->name }}</td>
                        <td>{{ $activity->causer?->roles->pluck('name')->join(', ') }}</td>
                        <td>{{ ucfirst($activity->event) }}</td>
                        <td>{{ class_basename($activity->subject_type) }}</td>
                        <td>{{ $activity->properties['old']['title'] ?? '' }}</td>
                        <td>{{ $activity->properties['attributes']['title'] ?? '' }}</td>
                        <td>{{ $activity->created_at->format('d-M-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $activities->withQueryString()->links() }}
    </div>
@endsection
