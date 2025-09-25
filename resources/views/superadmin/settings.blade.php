@extends('layouts.superadmin')

@section('title', 'Settings')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Settings
</h2>
@endsection

@section('content')
<div class="w-full mx-auto bg-white p-8 rounded-lg shadow">
    @if(session('success'))
    <div class="mb-6 flex items-center gap-2 bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('info') === 'No changes detected.')
    <div class="mb-6 flex items-center gap-2 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded shadow">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
        </svg>
        <span>No changes detected. Nothing was updated.</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-3 rounded bg-red-100 text-red-800 border border-red-300">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- SuperAdmin Profile Photo Update --}}
    <h3 class="text-xl font-semibold mb-6">Change Profile Photo</h3>

    <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 mb-12">
        @csrf
        @method('PUT')
        <input type="hidden" name="photo_update" value="1">

        <!-- SuperAdmin Profile Photo Card -->
        <div class="bg-gray-50 rounded-xl shadow p-6 mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="profile_photo">Profile Photo</label>
            <div class="flex items-center gap-4">
                <div>
                    <input
                        type="file"
                        name="profile_photo"
                        id="profile_photo"
                        accept="image/*"
                        class="block w-full text-sm text-gray-700 border-gray-300 rounded-md shadow-sm focus:ring-primary-gold focus:border-primary-gold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-gold file:text-white hover:file:bg-primary-navy">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, or GIF. Max 2MB.</p>
                    @error('profile_photo')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @if($superadmin->profile_photo_path)
                <img src="{{ $superadmin->profile_photo_path }}" alt="Profile"
                    class="w-20 h-20 rounded-full object-cover border-2 border-primary-gold shadow">
                @else
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border-2 border-gray-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                @endif
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-primary-navy text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-900 transition">
                Update Photo
            </button>
        </div>
    </form>

    <h3 class="text-xl font-semibold mb-6">Update Profile</h3>

    <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-6 mb-12">
        @csrf
        @method('PUT')
        <input type="hidden" name="profile_update" value="1">

        <div>
            <label for="name" class="block font-medium mb-2">Name</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg p-2.5" value="{{ old('name', $superadmin->name) }}">
            @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email" class="block font-medium mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-lg p-2.5" value="{{ old('email', $superadmin->email) }}">
            @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Add more profile fields as needed --}}

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-navy text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-900 transition">
                Update Profile
            </button>
        </div>
    </form>

    {{-- SuperAdmin Password Change --}}
    <h3 class="text-xl font-semibold mb-6">Change Password</h3>
    <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="password_update" value="1">

        <div>
            <label for="current_password" class="block font-medium mb-2">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="w-full border border-gray-300 rounded-lg p-2.5">
            @error('current_password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password" class="block font-medium mb-2">New Password</label>
            <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded-lg p-2.5">
            @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block font-medium mb-2">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-gray-300 rounded-lg p-2.5">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-navy text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-900 transition">
                Change Password
            </button>
        </div>
    </form>
</div>
@endsection