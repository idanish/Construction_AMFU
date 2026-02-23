@extends('master')
@section('title', 'Deleted Users')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Deleted Users</div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('admin.user-management') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left-circle"></i> Go Back
            </a>
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

<div class="table-responsive mt-3">
    <table class="table datatable table-bordered table-striped table-sm w-100">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Deleted Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->deleted_at->format('d-M-Y H:i') }}</td>
                <td>
                    {{-- RESTORE FORM --}}
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        {{-- POST method ko use kar ke restore karein --}}
                        <button type="submit" class="btn btn-sm btn-success"
                            onclick="return confirm('Are you sure you want to restore this user?');">
                            Restore
                        </button>
                    </form>

                    {{-- FORCE DELETE FORM --}}
                    <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE') {{-- Laravel DELETE method use karein --}}
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('WARNING: Are you sure you want to PERMANENTLY delete this user? This cannot be undone.');">
                            Permanently Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty

            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection