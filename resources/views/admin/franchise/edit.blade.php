@extends('layouts.admin')

@section('title', 'Edit Franchise Application')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Edit Franchise Application
</h2>
@endsection

@section('content')
<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg mt-4">

        <div class="px-4 py-5 sm:p-6">

            <form action="{{ route('admin.franchise.update', $encryptedId) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Operator Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Operator Information</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Last Name -->
                                <div>
                                    <label for="operator_last_name" class="block text-sm font-medium text-primary-navy mb-1">Last Name</label>
                                    <input
                                        value="{{ old('operator_last_name', $franchiseApplication->operator->last_name ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_last_name') border-red-500 @enderror"
                                        id="operator_last_name"
                                        name="operator_last_name"
                                        required
                                    >
                                    @error('operator_last_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- First Name -->
                                <div>
                                    <label for="operator_first_name" class="block text-sm font-medium text-primary-navy mb-1">First Name</label>
                                    <input
                                        value="{{ old('operator_first_name', $franchiseApplication->operator->first_name ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_first_name') border-red-500 @enderror"
                                        id="operator_first_name"
                                        name="operator_first_name"
                                        required
                                    >
                                    @error('operator_first_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                 <!-- Middle Initial -->
                                 <div>
                                    <label for="operator_middle_initial" class="block text-sm font-medium text-primary-navy mb-1">Middle Initial</label>
                                    <input
                                        value="{{ old('operator_middle_initial', $franchiseApplication->operator->middle_initial ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_middle_initial') border-red-500 @enderror"
                                        id="operator_middle_initial"
                                        name="operator_middle_initial"
                                        maxlength="1"
                                    >
                                    @error('operator_middle_initial')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Birth Date -->
                                <div>
                                    <label for="operator_birth_date" class="block text-sm font-medium text-primary-navy mb-1">Birth Date</label>
                                    <input
                                        value="{{ old('operator_birth_date', ($franchiseApplication->operator->birth_date ?? '') ? \Carbon\Carbon::parse($franchiseApplication->operator->birth_date)->format('Y-m-d') : '') }}"
                                        type="date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_birth_date') border-red-500 @enderror"
                                        id="operator_birth_date"
                                        name="operator_birth_date"
                                        required
                                    >
                                    @error('operator_birth_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Age -->
                                <div>
                                    <label for="operator_age" class="block text-sm font-medium text-primary-navy mb-1">Age</label>
                                    <input
                                        value="{{ old('operator_age', $franchiseApplication->operator->age ?? '') }}"
                                        type="number"
                                        min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_age') border-red-500 @enderror"
                                        id="operator_age"
                                        name="operator_age"
                                        required
                                    >
                                    @error('operator_age')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Sex -->
                                <div>
                                    <label for="operator_sex" class="block text-sm font-medium text-primary-navy mb-1">Sex</label>
                                    <select
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_sex') border-red-500 @enderror"
                                        id="operator_sex"
                                        name="operator_sex"
                                        required
                                    >
                                        <option value="">Select...</option>
                                        <option value="Male" {{ old('operator_sex', $franchiseApplication->operator->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('operator_sex', $franchiseApplication->operator->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('operator_sex')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <!-- Barangay -->
                                <div>
                                    <label for="operator_barangay" class="block text-sm font-medium text-primary-navy mb-1">Barangay</label>
                                    <select 
                                        id="operator_barangay" 
                                        name="operator_barangay"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_barangay') border-red-500 @enderror" 
                                        required
                                    >
                                        <option value="">Select Barangay</option>
                                        @php
                                            $barangays = [
                                                'Banaba','Banaybanay','Bawi','Bukal','Castillo','Cawongan','Manggas','Maugat East','Maugat West','Pansol','Poblacion','San Felipe','San Vicente','Santa Clara','Santo Niño','Silangan','Tamak','Quilo-quilo North'
                                            ];
                                            $selectedBarangay = old('operator_barangay', $franchiseApplication->operator->barangay ?? '');
                                        @endphp
                                        @foreach($barangays as $barangay)
                                            <option value="{{ $barangay }}" {{ $selectedBarangay == $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                                        @endforeach
                                        @if($selectedBarangay && !in_array($selectedBarangay, $barangays))
                                            <option value="{{ $selectedBarangay }}" selected>{{ $selectedBarangay }}</option>
                                        @endif
                                    </select>
                                    @error('operator_barangay')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                 <!-- Civil Status -->
                                 <div>
                                    <label for="operator_civil_status" class="block text-sm font-medium text-primary-navy mb-1">Civil Status</label>
                                    <select
                                        id="operator_civil_status"
                                        name="operator_civil_status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_civil_status') border-red-500 @enderror"
                                        required
                                    >
                                        <option value="">Select civil status</option>
                                        <option value="single" {{ old('operator_civil_status', $franchiseApplication->operator->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('operator_civil_status', $franchiseApplication->operator->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="widowed" {{ old('operator_civil_status', $franchiseApplication->operator->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="divorced" {{ old('operator_civil_status', $franchiseApplication->operator->civil_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                    @error('operator_civil_status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                               
                               
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Contact No -->
                                <div>
                                    <label for="operator_contact_no" class="block text-sm font-medium text-primary-navy mb-1">Contact Number</label>
                                    <input
                                        value="{{ old('operator_contact_no', $franchiseApplication->operator->contact_no ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_contact_no') border-red-500 @enderror"
                                        id="operator_contact_no"
                                        name="operator_contact_no"
                                        required
                                    >
                                    @error('operator_contact_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            <div>
                                <label for="operator_email" class="block text-sm font-medium text-primary-navy mb-1">Email</label>
                                <input
                                    value="{{ old('operator_email', optional($franchiseApplication->operator->user)->email ?? '') }}"
                                    type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('operator_email') border-red-500 @enderror"
                                    id="operator_email"
                                    name="operator_email"
                                    required
                                    autocomplete="off"
                                >
                                @error('operator_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            </div>
                            
                        </div>
                    </div>


                    <!-- Driver Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Driver Information</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Last Name -->
                                <div>
                                    <label for="driver_last_name" class="block text-sm font-medium text-primary-navy mb-1">Last Name</label>
                                    <input
                                        value="{{ old('driver_last_name', $franchiseApplication->driver->last_name ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_last_name') border-red-500 @enderror"
                                        id="driver_last_name"
                                        name="driver_last_name"
                                        required
                                    >
                                    @error('driver_last_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- First Name -->
                                <div>
                                    <label for="driver_first_name" class="block text-sm font-medium text-primary-navy mb-1">First Name</label>
                                    <input
                                        value="{{ old('driver_first_name', $franchiseApplication->driver->first_name ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_first_name') border-red-500 @enderror"
                                        id="driver_first_name"
                                        name="driver_first_name"
                                        required
                                    >
                                    @error('driver_first_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                 <!-- Middle Initial -->
                                 <div>
                                    <label for="driver_middle_initial" class="block text-sm font-medium text-primary-navy mb-1">Middle Initial</label>
                                    <input
                                        value="{{ old('driver_middle_initial', $franchiseApplication->driver->middle_initial ?? '') }}"
                                        type="text"
                                        maxlength="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_middle_initial') border-red-500 @enderror"
                                        id="driver_middle_initial"
                                        name="driver_middle_initial"
                                    >
                                    @error('driver_middle_initial')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                               
                                
                                <!-- Birth Date -->
                                <div>
                                    <label for="driver_birth_date" class="block text-sm font-medium text-primary-navy mb-1">Birth Date</label>
                                    <input
                                    value="{{ old('driver_birth_date', ($franchiseApplication->driver->birth_date ?? '') ? \Carbon\Carbon::parse($franchiseApplication->driver->birth_date)->format('Y-m-d') : '') }}"
                                    type="date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_birth_date') border-red-500 @enderror"
                                        id="driver_birth_date"
                                        name="driver_birth_date"
                                        required
                                    >
                                    @error('driver_birth_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                 <!-- Age -->
                                 <div>
                                    <label for="driver_age" class="block text-sm font-medium text-primary-navy mb-1">Age</label>
                                    <input
                                        value="{{ old('driver_age', $franchiseApplication->driver->age ?? '') }}"
                                        type="number"
                                        min="18"
                                        max="80"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_age') border-red-500 @enderror"
                                        id="driver_age"
                                        name="driver_age"
                                        required
                                    >
                                    @error('driver_age')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Sex -->
                                <div>
                                    <label for="driver_sex" class="block text-sm font-medium text-primary-navy mb-1">Sex</label>
                                    <select
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_sex') border-red-500 @enderror"
                                        id="driver_sex"
                                        name="driver_sex"
                                        required
                                    >
                                        <option value="">Select...</option>
                                        <option value="Male" {{ old('driver_sex', $franchiseApplication->driver->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('driver_sex', $franchiseApplication->driver->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('driver_sex')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <!-- Barangay -->
                                <div>
                                    <label for="driver_barangay" class="block text-sm font-medium text-primary-navy mb-1">Barangay</label>
                                    <select 
                                        id="driver_barangay" 
                                        name="driver_barangay"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_barangay') border-red-500 @enderror" 
                                        required
                                    >
                                        <option value="">Select Barangay</option>
                                        @php
                                            $barangays = [
                                                'Banaba','Banaybanay','Bawi','Bukal','Castillo','Cawongan','Manggas','Maugat East','Maugat West','Pansol','Poblacion','San Felipe','San Vicente','Santa Clara','Santo Niño','Silangan','Tamak','Quilo-quilo North'
                                            ];
                                            $selectedBarangay = old('driver_barangay', $franchiseApplication->driver->barangay ?? '');
                                        @endphp
                                        @foreach($barangays as $barangay)
                                            <option value="{{ $barangay }}" {{ $selectedBarangay == $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                                        @endforeach
                                        @if($selectedBarangay && !in_array($selectedBarangay, $barangays))
                                            <option value="{{ $selectedBarangay }}" selected>{{ $selectedBarangay }}</option>
                                        @endif
                                    </select>
                                    @error('driver_barangay')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Civil Status -->
                                <div>
                                    <label for="driver_civil_status" class="block text-sm font-medium text-primary-navy mb-1">Civil Status</label>
                                    <select
                                        id="driver_civil_status"
                                        name="driver_civil_status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_civil_status') border-red-500 @enderror"
                                        required
                                    >
                                        <option value="">Select civil status</option>
                                        <option value="single" {{ old('driver_civil_status', $franchiseApplication->driver->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('driver_civil_status', $franchiseApplication->driver->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="widowed" {{ old('driver_civil_status', $franchiseApplication->driver->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="divorced" {{ old('driver_civil_status', $franchiseApplication->driver->civil_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                    @error('driver_civil_status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Contact No -->
                                <div>
                                    <label for="driver_contact_no" class="block text-sm font-medium text-primary-navy mb-1">Contact Number</label>
                                    <input
                                        value="{{ old('driver_contact_no', $franchiseApplication->driver->contact_no ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_contact_no') border-red-500 @enderror"
                                        id="driver_contact_no"
                                        name="driver_contact_no"
                                        required
                                    >
                                    @error('driver_contact_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- License Number -->
                                <div>
                                    <label for="driver_license_no" class="block text-sm font-medium text-primary-navy mb-1">License Number</label>
                                    <input
                                        value="{{ old('driver_license_no', $franchiseApplication->driver->license_no ?? '') }}"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_license_no') border-red-500 @enderror"
                                        id="driver_license_no"
                                        name="driver_license_no"
                                        required
                                    >
                                    @error('driver_license_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- License Validity -->
                                <div>
                                    <label for="driver_license_validity" class="block text-sm font-medium text-primary-navy mb-1">License Validity</label>
                                    <input
                                        value="{{ old('driver_license_validity', ($franchiseApplication->driver->license_validity ?? '') ? \Carbon\Carbon::parse($franchiseApplication->driver->license_validity)->format('Y-m-d') : '') }}"
                                        type="date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_license_validity') border-red-500 @enderror"
                                        id="driver_license_validity"
                                        name="driver_license_validity"
                                        required
                                    >
                                    @error('driver_license_validity')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- License Nature -->
                                <div>
                                    <label for="driver_license_nature" class="block text-sm font-medium text-primary-navy mb-1">License Nature</label>
                                    <select
                                        id="driver_license_nature"
                                        name="driver_license_nature"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 @error('driver_license_nature') border-red-500 @enderror"
                                        required
                                    >
                                        <option value="">Select license nature</option>
                                        <option value="Professional" {{ old('driver_license_nature', $franchiseApplication->driver->license_nature ?? '') == 'Professional' ? 'selected' : '' }}>Professional</option>
                                        <option value="Non-Professional" {{ old('driver_license_nature', $franchiseApplication->driver->license_nature ?? '') == 'Non-Professional' ? 'selected' : '' }}>Non-Professional</option>
                                        <option value="Student" {{ old('driver_license_nature', $franchiseApplication->driver->license_nature ?? '') == 'Student' ? 'selected' : '' }}>Student</option>
                                        <option value="Restriction 1" {{ old('driver_license_nature', $franchiseApplication->driver->license_nature ?? '') == 'Restriction 1' ? 'selected' : '' }}>Restriction 1</option>
                                        <option value="Restriction 2" {{ old('driver_license_nature', $franchiseApplication->driver->license_nature ?? '') == 'Restriction 2' ? 'selected' : '' }}>Restriction 2</option>
                                    </select>
                                    @error('driver_license_nature')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Application Details -->
                <div class="mt-6 bg-gray-50 rounded-lg p-6 border">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Application Type</label>
                            <select name="application_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                                <option value="new" {{ old('application_type', $franchiseApplication->application_type) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="renewal" {{ old('application_type', $franchiseApplication->application_type) == 'renewal' ? 'selected' : '' }}>Renewal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Route</label>
                            <select name="route_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id', $franchiseApplication->route_id) == $route->id ? 'selected' : '' }}>
                                    {{ $route->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CTC Number</label>
                            <input name="ctc_no" value="{{ old('ctc_no', $franchiseApplication->ctc_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CTC Date Issued</label>
                            <input 
                                type="date" 
                                name="ctc_date_issued"
                                value="{{ old('ctc_date_issued', $franchiseApplication->ctc_date_issued ? \Carbon\Carbon::parse($franchiseApplication->ctc_date_issued)->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" 
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CTC Place Issued</label>
                            <input 
                                type="text" 
                                name="ctc_place_issued"
                                value="{{ old('ctc_place_issued', $franchiseApplication->ctc_place_issued) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy"
                                required
                            />
                        </div>
                        @if($franchiseApplication->status == 'approved')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise Number</label>
                            <input name="franchise_no" value="{{ old('franchise_no', $franchiseApplication->franchise_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sticker Number</label>
                            <input name="sticker_no" value="{{ old('sticker_no', $franchiseApplication->sticker_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise Start Date</label>
                            <input 
                                type="date" 
                                name="franchise_start_date"
                                value="{{ old('franchise_start_date', $franchiseApplication->franchise_start_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_start_date)->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" 
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise End Date</label>
                            <input 
                                type="date" 
                                name="franchise_end_date"
                                value="{{ old('franchise_end_date', $franchiseApplication->franchise_end_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_end_date)->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" 
                            />
                        </div>
                        @endif
                        @if($franchiseApplication->status == 'rejected')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea name="rejection_reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" rows="2">{{ old('rejection_reason', $franchiseApplication->rejection_reason) }}</textarea>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Motor Details -->
                @if($franchiseApplication->motorDetail)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Motor Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                            <input value="{{ ucfirst($franchiseApplication->motorDetail->unit_type) }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Make</label>
                            <input value="{{ $franchiseApplication->motorDetail->unitMake->name ?? 'N/A' }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                            <input value="{{ $franchiseApplication->motorDetail->platenumber }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Motor No</label>
                            <input value="{{ $franchiseApplication->motorDetail->motorno }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chassis No</label>
                            <input value="{{ $franchiseApplication->motorDetail->chasisno }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-end mt-8">
                <a href="{{ route('admin.franchise.show', $encryptedId) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition ease-in-out duration-150">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
