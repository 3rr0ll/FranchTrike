@extends('layouts.operator')

@section('content')
    <div class="py-6 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('operator.driver.store') }}" method="POST">
                <input type="hidden" name="operator_id" value="{{ auth()->user()->operator->operator_id }}">
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
                            placeholder="e.g.,09123456789"
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
                            type="select"
                            :options="[
                                '' => 'Select Barangay',
                                'Banaba' => 'Banaba',
                                'Banaybanay' => 'Banaybanay',
                                'Bawi' => 'Bawi',
                                'Bukal' => 'Bukal',
                                'Castillo' => 'Castillo',
                                'Cawongan' => 'Cawongan',
                                'Manggas' => 'Manggas',
                                'Maugat East' => 'Maugat East',
                                'Maugat West' => 'Maugat West',
                                'Pansol' => 'Pansol',
                                'Poblacion' => 'Poblacion',
                                'San Felipe' => 'San Felipe',
                                'San Vicente' => 'San Vicente',
                                'Santa Clara' => 'Santa Clara',
                                'Santo Niño' => 'Santo Niño',
                                'Silangan' => 'Silangan',
                                'Tamak' => 'Tamak',
                                'Quilo-quilo North' => 'Quilo-quilo North'
                            ]"
                            required
                        />
                    </div>
                </div>

                {{-- License Information Section --}}
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">License Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input
                            label="License Number"
                            name="license_no"
                            placeholder="e.g.,N01-12-123456"
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

                <div class="mt-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('operator.dashboard') }}">
                            <x-button type="button">
                                Back
                            </x-button>
                        </a>
                        <x-button type="button" id="submit-btn">
                            Submit
                        </x-button>
                    </div>
                </div>

                <script>
                    document.getElementById('submit-btn').addEventListener('click', function(e) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Do you want to submit this information?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, submit'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.closest('form').submit();
                            }
                        });
                    });
                </script>
            </form>
        </div>
    </div>
@endsection