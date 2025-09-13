@extends('master')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Assign Permissions for {{ $user->name }}</h2>
        <a href="{{ route('users.index') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
            ← Back to Users
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif


    <form action="{{ route('users.update-permissions', $user) }}" method="POST">
        @csrf

        @php
        $groupedPermissions = $permissions->groupBy(function($item) {
        $parts = explode('-', $item->name);
        return ucwords(implode(' ', array_slice($parts, 1)));
        });
        @endphp

        @foreach ($groupedPermissions as $groupName => $groupPermissions)
        <div class="p-4 bg-gray-50 rounded-lg shadow-sm mb-4">
            <h3 class="font-bold text-lg mb-2 capitalize">{{ $groupName }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-y-2">
                @foreach ($groupPermissions as $permission)
                <div class="flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}
                        class="mr-2 form-checkbox text-blue-600">
                    <label class="text-gray-700 capitalize">
                        {{ str_replace('-', ' ', $permission->name) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-4">Update Permissions</button>
    </form>


</div>
@endsection