@extends('../master')
@section('title', 'User Management')

@section('content')
    <div class="p-6">
        <!-- Heading -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Users Management</h1>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="px-4 py-3 border">ID</th>
                        <th class="px-4 py-3 border">Username</th>
                        <th class="px-4 py-3 border">Name</th>
                        <th class="px-4 py-3 border">Email</th>
                        <th class="px-4 py-3 border">Role</th>
                        <th class="px-4 py-3 border">Department</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-2">{{ $counter++ }}</td>
                            <td class="border px-4 py-2 font-medium text-gray-800">{{ $user->username }}</td>
                            <td class="border px-4 py-2 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2 text-blue-600">
                                {{ $user->roles->pluck('name')->implode(', ') ?? 'No Role' }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ $user->department ? $user->department->name : '-' }}
                            </td>
                            <td class="border px-4 py-2">
                                @if ($user->status)
                                    <span class="text-green-600 font-semibold">Active</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <div class="grid grid-cols-2 gap-4">
                                    

                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="btn btn-warning" title="Edit">
                                        <i class="bx bx-pencil"> </i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                        class="text-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger"  title="Delete">
                                            <i class="bx bx-trash"> </i>
                                        </button>
                                    </form>

                                    <a href="{{ route('users.edit-permissions', $user) }}"
                                        class="btn btn-primary"  title="Assign Permissions">
                                        <i class="bx bx-user-check"> </i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
