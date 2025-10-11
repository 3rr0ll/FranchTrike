@extends('layouts.admin')

@section('title', 'Create Application')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Create Franchise Application
</h2>
@endsection

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    @if (session('error'))
    <div class="mb-4 text-red-600 font-semibold bg-red-100 p-4 rounded">{{ session('error') }}</div>
    @endif
    @if (session('success'))
    <div class="mb-4 text-green-600 font-semibold bg-green-100 p-4 rounded">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.franchise.store') }}" class="space-y-8">
        @csrf

        <!-- Application Type -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Application Type</label>
                    <select name="application_type" id="application_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        <option value="new">New Application</option>
                        <option value="renewal">Renewal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Route</label>
                    <select name="route_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select a route</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Franchise Details for Renewal -->
            <div id="franchise_details" class="mt-4 hidden bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h4 class="text-md font-medium text-orange-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Previous Franchise Details (Required for Renewal)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Previous Franchise Number <span class="text-red-500">*</span></label>
                        <input type="text" name="previous_franchise_no" id="previous_franchise_no" value="{{ old('previous_franchise_no') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy"
                               placeholder="Enter previous franchise number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Previous Sticker Number <span class="text-red-500">*</span></label>
                        <input type="text" name="previous_sticker_no" id="previous_sticker_no" value="{{ old('previous_sticker_no') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy"
                               placeholder="Enter previous sticker number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Previous Application ID (Optional)</label>
                        <input type="number" name="previous_application_id" value="{{ old('previous_application_id') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy"
                               placeholder="Enter previous application ID if known">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Previous Franchise End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="previous_franchise_end_date" id="previous_franchise_end_date" value="{{ old('previous_franchise_end_date') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                    </div>
                </div>
            </div>
        </div>

        <!-- Operator Information -->
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Operator Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                    <input type="text" name="operator_last_name" value="{{ old('operator_last_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name *</label>
                    <input type="text" name="operator_first_name" value="{{ old('operator_first_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Middle Initial</label>
                    <input type="text" name="operator_middle_initial" value="{{ old('operator_middle_initial') }}" maxlength="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Barangay *</label>
                    <input type="text" name="operator_barangay" value="{{ old('operator_barangay') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Municipality *</label>
                    <input type="text" name="operator_municipality" value="{{ old('operator_municipality') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Province *</label>
                    <input type="text" name="operator_province" value="{{ old('operator_province') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Birth Date *</label>
                    <input type="date" name="operator_birth_date" value="{{ old('operator_birth_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age *</label>
                    <input type="number" name="operator_age" value="{{ old('operator_age') }}" min="18" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sex *</label>
                    <select name="operator_sex" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select sex</option>
                        <option value="male" {{ old('operator_sex') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('operator_sex') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Civil Status *</label>
                    <select name="operator_civil_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select civil status</option>
                        <option value="single" {{ old('operator_civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('operator_civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="widowed" {{ old('operator_civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="divorced" {{ old('operator_civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
                    <input type="text" name="operator_contact_no" value="{{ old('operator_contact_no') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address *</label>
                    <input type="email" name="operator_email" value="{{ old('operator_email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label for="operator_password" class="block mb-1 text-sm font-semibold text-primary-navy">Password *</label>
                    <div class="relative">
                        <input type="password" name="operator_password" id="operator_password" class="pr-10 py-2 w-full border border-gray-300 rounded-lg focus:ring-primary-navy focus:border-primary-navy text-gray-900 bg-white" placeholder="Enter password" required>
                
                        <!-- Show/Hide Toggle -->
                        <button type="button" id="toggleOperatorPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-primary-navy focus:outline-none" tabindex="-1">
                            <!-- Eye (visible) -->
                            <svg id="eyeIconOperator" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                
                            <!-- Eye Slash (hidden) -->
                            <svg id="eyeSlashIconOperator" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Driver Information -->
        <div class="bg-green-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Driver Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                    <input type="text" name="driver_last_name" value="{{ old('driver_last_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name *</label>
                    <input type="text" name="driver_first_name" value="{{ old('driver_first_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Middle Initial</label>
                    <input type="text" name="driver_middle_initial" value="{{ old('driver_middle_initial') }}" maxlength="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Barangay *</label>
                    <input type="text" name="driver_barangay" value="{{ old('driver_barangay') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Municipality *</label>
                    <input type="text" name="driver_municipality" value="{{ old('driver_municipality') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Province *</label>
                    <input type="text" name="driver_province" value="{{ old('driver_province') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Birth Date *</label>
                    <input type="date" name="driver_birth_date" value="{{ old('driver_birth_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age *</label>
                    <input type="number" name="driver_age" value="{{ old('driver_age') }}" min="18" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sex *</label>
                    <select name="driver_sex" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select sex</option>
                        <option value="male" {{ old('driver_sex') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('driver_sex') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Civil Status *</label>
                    <select name="driver_civil_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select civil status</option>
                        <option value="single" {{ old('driver_civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('driver_civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="widowed" {{ old('driver_civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="divorced" {{ old('driver_civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
                    <input type="text" name="driver_contact_no" value="{{ old('driver_contact_no') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">License Number *</label>
                    <input type="text" name="driver_license_no" value="{{ old('driver_license_no') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">License Validity *</label>
                    <input type="date" name="driver_license_validity" value="{{ old('driver_license_validity') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">License Nature *</label>
                    <select name="driver_license_nature" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select license nature</option>
                        <option value="Professional" {{ old('driver_license_nature') == 'Professional' ? 'selected' : '' }}>Professional</option>
                        <option value="Non-Professional" {{ old('driver_license_nature') == 'Non-Professional' ? 'selected' : '' }}>Non-Professional</option>
                        <option value="Student" {{ old('driver_license_nature') == 'Student' ? 'selected' : '' }}>Student</option>
                        <option value="Restriction 1" {{ old('driver_license_nature') == 'Restriction 1' ? 'selected' : '' }}>Restriction 1</option>
                        <option value="Restriction 2" {{ old('driver_license_nature') == 'Restriction 2' ? 'selected' : '' }}>Restriction 2</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Motor Details -->
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Motor Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Type *</label>
                    <select name="unit_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select unit type</option>
                        <option value="motocab" {{ old('unit_type') == 'motocab' ? 'selected' : '' }}>Motocab</option>
                        <option value="tricycle" {{ old('unit_type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Make *</label>
                    <select name="unit_make_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        <option value="">Select unit make</option>
                        @foreach(\App\Models\UnitMake::all() as $unitMake)
                        <option value="{{ $unitMake->id }}" {{ old('unit_make_id') == $unitMake->id ? 'selected' : '' }}>
                            {{ $unitMake->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Motor Number *</label>
                    <input type="text" name="motorno" value="{{ old('motorno') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Chassis Number *</label>
                    <input type="text" name="chasisno" value="{{ old('chasisno') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Plate Number *</label>
                    <input type="text" name="platenumber" value="{{ old('platenumber') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
            </div>
        </div>

        <!-- CTC Details -->
        <div class="bg-yellow-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">CTC Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC No *</label>
                    <input type="text" name="ctc_no" value="{{ old('ctc_no') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC Date Issued *</label>
                    <input type="date" name="ctc_date_issued" value="{{ old('ctc_date_issued') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC Place Issued *</label>
                    <input type="text" name="ctc_place_issued" value="{{ old('ctc_place_issued') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Franchise Fee (Optional)</label>
                <input type="number" name="franchise_fee" value="{{ old('franchise_fee') }}" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
            </div>
        </div>

        <!-- Document Submission (Checkboxes) -->
        <div class="bg-purple-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Submission</h3>
            <p class="text-sm text-gray-600 mb-4">Check the documents that have been physically submitted by the walk-in client:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Operator Documents -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">Operator Documents</h4>
                    <div class="space-y-2">
                        @foreach($documentTypes->where('applies_to', 'operator') as $docType)
                        <label class="flex items-center">
                            <input type="checkbox" name="operator_documents[]" value="{{ $docType->document_id }}" 
                                   class="rounded border-gray-300 text-primary-navy shadow-sm focus:border-primary-navy focus:ring focus:ring-primary-navy focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $docType->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Driver Documents -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">Driver Documents</h4>
                    <div class="space-y-2">
                        @foreach($documentTypes->where('applies_to', 'driver') as $docType)
                        <label class="flex items-center">
                            <input type="checkbox" name="driver_documents[]" value="{{ $docType->document_id }}" 
                                   class="rounded border-gray-300 text-primary-navy shadow-sm focus:border-primary-navy focus:ring focus:ring-primary-navy focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $docType->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.franchise.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-navy text-white rounded-md hover:bg-primary-gold hover:text-primary-navy transition-colors">
                Create Application
            </button>
        </div>
    </form>
</div>

@if ($errors->any())
<div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
    <h4 class="font-semibold">Please correct the following errors:</h4>
    <ul class="list-disc list-inside mt-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const applicationTypeSelect = document.getElementById('application_type');
    const franchiseDetailsDiv = document.getElementById('franchise_details');
    // Removed previousFranchiseNo since franchise application number column is removed
    const previousStickerNo = document.getElementById('previous_sticker_no');
    const previousFranchiseEndDate = document.getElementById('previous_franchise_end_date');
    
    function toggleFranchiseDetails() {
        if (applicationTypeSelect.value === 'renewal') {
            franchiseDetailsDiv.classList.remove('hidden');
            // Make required fields mandatory
            previousStickerNo.setAttribute('required', 'required');
            previousFranchiseEndDate.setAttribute('required', 'required');
        } else {
            franchiseDetailsDiv.classList.add('hidden');
            // Remove required attributes
            previousStickerNo.removeAttribute('required');
            previousFranchiseEndDate.removeAttribute('required');
        }
    }
    
    // Initial check
    toggleFranchiseDetails();
    
    // Listen for changes
    applicationTypeSelect.addEventListener('change', toggleFranchiseDetails);
});
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('operator_password');
    const togglePassword = document.getElementById('toggleOperatorPassword');
    const eyeIcon = document.getElementById('eyeIconOperator');
    const eyeSlashIcon = document.getElementById('eyeSlashIconOperator');

    if (togglePassword) {
        togglePassword.addEventListener('click', function (e) {
            e.preventDefault();
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
@endsection
