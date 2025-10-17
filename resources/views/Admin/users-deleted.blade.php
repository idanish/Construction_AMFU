@extends('master')
@section('title', 'Deleted Users')
@section('content')


<div class="main-card mb-3">
    <div class="card-body">
        <h5 class="card-title">Deleted Users</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
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
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST"
                            style="display:inline;">
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
                <tr>
                    <td colspan="5" class="text-center">No soft deleted users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection