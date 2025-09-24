@extends('master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Audit Logs</h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('audit.logs.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="user_id" class="form-control" placeholder="Filter by User ID"
                        value="{{ request('user_id') }}">
                </div>
                <div class="col-md-2">
                    <select name="event" class="form-control">
                        <option value="">All Events</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="subject_type" class="form-control" placeholder="Filter by Subject"
                        value="{{ request('subject_type') }}">
                </div>
                <div class="col-md-2">
                    <select name="per_page" class="form-control">
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="vip-btn btn-filter">
                        <I class="bi bi-funnel"></I> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-3">
            <a href="{{ route('audit.logs.export', request()->all()) }}" class="btn btn-success vip-btn btn-download">
                <i class="bi bi-download"></i> Download logs Excel
                </button>

            </a>
            <a href="{{ route('audit.logs.exportFull') }}" class="btn btn-danger vip-btn btn-download">
                <i class="bi bi-download"></i> Download Full Logs
                </button>

            </a>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>User</th>
                    <th>Operation</th>
                    <th>Subject</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>
                        <td>{{ ucfirst($log->event) }}</td>
                        <td>{{ class_basename($log->subject_type) }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $logs->links() }}
    </div>
@endsection
