<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Add Driver Information
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('driver.store') }}" method="POST">
                @csrf

                {{-- Personal Information Section --}}
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="Last Name"
                            name="last_name"
                            required />

                        <x-input
                            label="First Name"
                            name="first_name"
                            required />

                        <x-input
                            label="Middle Initial"
                            name="middle_initial"
                            maxlength="1"
                            pattern="[A-Za-z]"
                            title="Please enter a single letter" />

                        <x-input
                            label="Birth Date"
                            name="birth_date"
                            type="date"
                            required />

                        <x-input
                            label="Age"
                            name="age"
                            type="number"
                            min="18"
                            max="100"
                            required />

                        <x-input
                            label="Sex"
                            name="sex"
                            type="select"
                            :options="[
                                '' => 'Select Sex',
                                'Male' => 'Male', 
                                'Female' => 'Female'
                            ]"
                            required />

                        <x-input
                            label="Civil Status"
                            name="civil_status"
                            type="select"
                            :options="[
                                '' => 'Select Civil Status',
                                'Single' => 'Single',
                                'Married' => 'Married', 
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed',
                                'Separated' => 'Separated'
                            ]"
                            required />

                        <x-input
                            label="Contact Number"
                            name="contact_no"
                            placeholder="e.g., 09123456789"
                            required />
                    </div>
                </div>

                {{-- Address Information Section --}}
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="Barangay"
                            name="barangay"
                            required />

                        <x-input
                            label="Municipality"
                            name="municipality"
                            required />

                        <x-input
                            label="Province"
                            name="province"
                            required />
                    </div>
                </div>

                {{-- License Information Section --}}
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">License Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="License Number"
                            name="license_no"
                            placeholder="e.g., N01-12-123456"
                            required />

                        <x-input
                            label="License Validity"
                            name="license_validity"
                            type="date"
                            required />

                        <x-input
                            label="License Nature"
                            name="license_nature"
                            type="select"
                            :options="[
                                '' => 'Select License Nature',
                                'Professional' => 'Professional',
                                'Non-Professional' => 'Non-Professional',
                                'Student' => 'Student',
                                'Restriction 1' => 'Restriction 1',
                                'Restriction 2' => 'Restriction 2'
                            ]"
                            required />
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('driver.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Submit Driver Information
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>