@extends('layouts.admin')

@section('title', 'Edit Operator')
@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Edit Operator
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

        <form action="{{ route('admin.operators.update', $encryptedId) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-primary-navy mb-1">Last Name</label>
                    <input value="{{ old('last_name', $operator->last_name) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                           id="last_name" name="last_name" required>
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-primary-navy mb-1">First Name</label>
                    <input value="{{ old('first_name', $operator->first_name) }}" type="text"
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
                    <input value="{{ old('middle_initial', $operator->middle_initial) }}" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('middle_initial') border-red-500 @enderror"
                           id="middle_initial" name="middle_initial" maxlength="2">
                    @error('middle_initial')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-primary-navy mb-1">Age</label>
                    <input value="{{ old('age', $operator->age) }}" type="number" min="0"
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
                        <option value="Male" {{ old('sex', $operator->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $operator->sex) == 'Female' ? 'selected' : '' }}>Female</option>
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
                    <select 
                        id="barangay" 
                        value="{{ old('operator_barangay', $franchiseApplication->operator->barangay ?? '') }}"
                        name="barangay" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('barangay') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select Barangay</option>
                        <option value="Banaba" {{ old('barangay', $operator->barangay) == 'Banaba' ? 'selected' : '' }}>Banaba</option>
                        <option value="Banaybanay" {{ old('barangay', $operator->barangay) == 'Banaybanay' ? 'selected' : '' }}>Banaybanay</option>
                        <option value="Bawi" {{ old('barangay', $operator->barangay) == 'Bawi' ? 'selected' : '' }}>Bawi</option>
                        <option value="Bukal" {{ old('barangay', $operator->barangay) == 'Bukal' ? 'selected' : '' }}>Bukal</option>
                        <option value="Castillo" {{ old('barangay', $operator->barangay) == 'Castillo' ? 'selected' : '' }}>Castillo</option>
                        <option value="Cawongan" {{ old('barangay', $operator->barangay) == 'Cawongan' ? 'selected' : '' }}>Cawongan</option>
                        <option value="Manggas" {{ old('barangay', $operator->barangay) == 'Manggas' ? 'selected' : '' }}>Manggas</option>
                        <option value="Maugat East" {{ old('barangay', $operator->barangay) == 'Maugat East' ? 'selected' : '' }}>Maugat East</option>
                        <option value="Maugat West" {{ old('barangay', $operator->barangay) == 'Maugat West' ? 'selected' : '' }}>Maugat West</option>
                        <option value="Pansol" {{ old('barangay', $operator->barangay) == 'Pansol' ? 'selected' : '' }}>Pansol</option>
                        <option value="Poblacion" {{ old('barangay', $operator->barangay) == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
                        <option value="San Felipe" {{ old('barangay', $operator->barangay) == 'San Felipe' ? 'selected' : '' }}>San Felipe</option>
                        <option value="San Vicente" {{ old('barangay', $operator->barangay) == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
                        <option value="Santa Clara" {{ old('barangay', $operator->barangay) == 'Santa Clara' ? 'selected' : '' }}>Santa Clara</option>
                        <option value="Santo Niño" {{ old('barangay', $operator->barangay) == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
                        <option value="Silangan" {{ old('barangay', $operator->barangay) == 'Silangan' ? 'selected' : '' }}>Silangan</option>
                        <option value="Tamak" {{ old('barangay', $operator->barangay) == 'Tamak' ? 'selected' : '' }}>Tamak</option>
                        <option value="Quilo-quilo North" {{ old('barangay', $operator->barangay) == 'Quilo-quilo North' ? 'selected' : '' }}>Quilo-quilo North</option>
                    </select>
                    @error('barangay')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-primary-navy mb-1">Birth Date</label>
                    <input 
                        value="{{ old('birth_date', $operator->birth_date ? \Carbon\Carbon::parse($operator->birth_date)->format('Y-m-d') : '') }}" 
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
                    <select 
                        id="civil_status" 
                        name="civil_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('civil_status') border-red-500 @enderror" 
                        required
                    >
                        <option value="">Select civil status</option>
                        <option value="single" {{ old('civil_status', $operator->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('civil_status', $operator->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="widowed" {{ old('civil_status', $operator->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="divorced" {{ old('civil_status', $operator->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    </select>
                    @error('civil_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Contact No -->
                <div>
                    <label for="contact_no" class="block text-sm font-medium text-primary-navy mb-1">Contact Number</label>
                    <input value="{{ old('contact_no', $operator->contact_no) }}" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('contact_no') border-red-500 @enderror"
                        id="contact_no" name="contact_no" required>
                    @error('contact_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.operators.index') }}"
                   class="inline-flex items-center px-6 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-300 transition-all">
                    Cancel
                </a>
                <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 shadow-sm transition-all">
            Update Operator
        </button>
            </div>
        </form>
    </div>
</div>
@endsection
