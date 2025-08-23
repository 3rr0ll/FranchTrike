@extends('layouts.superadmin')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
        <a href="{{ route('superadmin.users.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Users
        </a>
    </div>

    @if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role_id" id="role_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('superadmin.users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <x-button type="submit">
                        Update User
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Information -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-800 mb-2">User Information</h4>
        <div class="text-sm text-gray-600 space-y-1">
            <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y \a\t g:i A') }}</p>
            <p><strong>Current Role:</strong> {{ ucfirst($user->role->name) }}</p>
            @if($user->email_verified_at)
            <p><strong>Email Verified:</strong> {{ $user->email_verified_at->format('M d, Y \a\t g:i A') }}</p>
            @else
            <p><strong>Email Status:</strong> <span class="text-red-600">Not verified</span></p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="text-sm font-medium text-blue-800 mb-2">Quick Actions</h4>
        <div class="space-y-2">
            <a href="{{ route('superadmin.users.password-reset', $user) }}"
                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Reset Password
            </a>
            @if($user->role->name !== 'superadmin')
            <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center text-sm text-orange-600 hover:text-orange-800">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                    {{ ($user->is_active ?? true) ? 'Deactivate User' : 'Activate User' }}
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection