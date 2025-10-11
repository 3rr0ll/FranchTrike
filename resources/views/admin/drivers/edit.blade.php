@extends('layouts.admin')

@section('title', 'Edit Driver')
@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Edit Driver
</h2>
@endsection

@section('content')
<div class="w-full mx-auto py-8 sm:px-6 lg:px-8">
  

    @if ($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg px-6 py-8">
        <form action="{{ route('admin.drivers.update', $driver->driver_id ?? $driver->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-primary-navy mb-1">Last Name</label>
                    <input value="{{ old('last_name', $driver->last_name) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                           id="last_name" name="last_name" required>
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-primary-navy mb-1">First Name</label>
                    <input value="{{ old('first_name', $driver->first_name) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                           id="first_name" name="first_name" required>
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Middle Initial -->
                <div>
                    <label for="middle_initial" class="block text-sm font-medium text-primary-navy mb-1">Middle Initial</label>
                    <input value="{{ old('middle_initial', $driver->middle_initial) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('middle_initial') border-red-500 @enderror"
                           id="middle_initial" name="middle_initial" maxlength="2">
                    @error('middle_initial')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-primary-navy mb-1">Age</label>
                    <input value="{{ old('age', $driver->age) }}" type="number" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('age') border-red-500 @enderror"
                           id="age" name="age" required>
                    @error('age')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sex -->
                <div>
                    <label for="sex" class="block text-sm font-medium text-primary-navy mb-1">Sex</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('sex') border-red-500 @enderror"
                        id="sex" name="sex" required>
                        <option value="">Select...</option>
                        <option value="Male" {{ old('sex', $driver->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $driver->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Barangay -->
                <div>
                    <label for="barangay" class="block text-sm font-medium text-primary-navy mb-1">Barangay</label>
                    <input value="{{ old('barangay', $driver->barangay) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('barangay') border-red-500 @enderror"
                           id="barangay" name="barangay" required>
                    @error('barangay')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Municipality -->
                <div>
                    <label for="municipality" class="block text-sm font-medium text-primary-navy mb-1">Municipality</label>
                    <input value="{{ old('municipality', $driver->municipality) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('municipality') border-red-500 @enderror"
                           id="municipality" name="municipality" required>
                    @error('municipality')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-medium text-primary-navy mb-1">Province</label>
                    <input value="{{ old('province', $driver->province) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('province') border-red-500 @enderror"
                           id="province" name="province" required>
                    @error('province')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Birthdate -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-primary-navy mb-1">Birth Date</label>
                    <input 
                        value="{{ old('birth_date', $driver->birth_date ? \Carbon\Carbon::parse($driver->birth_date)->format('Y-m-d') : '') }}" 
                        type="date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('birth_date') border-red-500 @enderror"
                        id="birth_date" 
                        name="birth_date" 
                        required
                    >
                    @error('birth_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Civil Status -->
                <div>
                    <label for="civil_status" class="block text-sm font-medium text-primary-navy mb-1">Civil Status</label>
                    <input value="{{ old('civil_status', $driver->civil_status) }}" type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('civil_status') border-red-500 @enderror"
                    id="civil_status" name="civil_status" required>
                    @error('civil_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Contact No -->
                <div>
                    <label for="contact_no" class="block text-sm font-medium text-primary-navy mb-1">Contact Number</label>
                    <input value="{{ old('contact_no', $driver->contact_no) }}" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('contact_no') border-red-500 @enderror"
                        id="contact_no" name="contact_no" required>
                    @error('contact_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- License Number -->
                <div>
                    <label for="license_no" class="block text-sm font-medium text-primary-navy mb-1">License Number</label>
                    <input value="{{ old('license_no', $driver->license_no) }}" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('license_no') border-red-500 @enderror"
                        id="license_no" name="license_no" required>
                    @error('license_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- License Validity -->
                <div>
                    <label for="license_validity" class="block text-sm font-medium text-primary-navy mb-1">License Validity</label>
                    <input value="{{ old('license_validity', $driver->license_validity ? \Carbon\Carbon::parse($driver->license_validity)->format('Y-m-d') : '') }}" type="date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('license_validity') border-red-500 @enderror"
                        id="license_validity" name="license_validity" required>
                    @error('license_validity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- License Nature -->
                <div>
                    <label for="license_nature" class="block text-sm font-medium text-primary-navy mb-1">License Nature</label>
                    <input value="{{ old('license_nature', $driver->license_nature) }}" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('license_nature') border-red-500 @enderror"
                        id="license_nature" name="license_nature" required>
                    @error('license_nature')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.drivers.index') }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-300 transition-all">
                    Cancel
                </a>
                <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 shadow-sm transition-all">
                Update Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
