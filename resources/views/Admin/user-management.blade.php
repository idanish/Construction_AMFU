@extends('../master')
@section('title', 'User Management')

@section('content')
    <div class="p-6">
        <!-- Heading -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-700">👥 User Management</h1>
            <form method="GET" action="{{ route('admin.user-management') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                    class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    🔍 Search
                </button>
            </form>
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
                        <th class="px-4 py-3 border text-center">Action</th>
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
                            <td class="border px-4 py-2 text-center">
                                <form action="{{ route('users.assignRole', $user->id) }}" method="POST"
                                    class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role"
                                        class="border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-300">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                        class="bg-green-600 text-dark px-3 py-1 rounded hover:bg-green-700 transition">
                                        ✅ Assign
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
