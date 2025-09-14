@extends('../master')
@section('title', 'User Management')

@section('content')
    <div class="p-6">
        <!-- Heading -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">👥 User Management</h1>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                <i class="bx bx-plus mr-2"></i> Add New User
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-3 border">#</th>
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
                                <div class="flex justify-center gap-2">
                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                                        ✏️
                                    </a>
                                    <!-- Permissions -->
                                    <a href="{{ route('users.edit-permissions', $user) }}"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                        🔑
                                    </a>
                                    <!-- Delete -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                            🗑️
                                        </button>
                                    </form>
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
