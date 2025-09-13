@extends('../master')
@section('title', 'User Management')

@section('content')
    <div class="p-6">
        <!-- Heading -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">👥 User Management</h1>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="px-4 py-3 border">ID</th>
                        <th class="px-4 py-3 border">Name</th>
                        <th class="px-4 py-3 border">Email</th>
                        <th class="px-4 py-3 border">Role</th>
                        <th class="px-4 py-3 border">Department</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-2">{{ $user->id }}</td>
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
                                        class="text-blue-600 hover:underline text-center">
                                        Edit
                                    </a>


                                    <!-- Delete -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                        class="text-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:underline bg-transparent border-0 cursor-pointer">
                                            Delete
                                        </button>
                                    </form>

                                    <a href="{{ route('users.edit-permissions', $user) }}"
                                        class="text-green-600 hover:underline text-center">
                                        Assign Permissions
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
