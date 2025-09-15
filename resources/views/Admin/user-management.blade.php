@extends('../master')
@section('title', 'User Management')

@section('content')
    <div class="p-6">
        <!-- Heading -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Users Management</h1>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-gray-700">
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
                            <td class="border px-4 py-2 text-center">
                                @if ($user->status == 1)
                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        ✅ Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                        ❌ Inactive
                                    </span>
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
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
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
