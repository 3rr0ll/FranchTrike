<x-guest-layout>
    <div class="py-6 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <a href="{{ route('operator.home') }}">
                <x-button class="mt-4">Back</x-button>
            </a>
            @php
            $hasOperator = \App\Models\Operator::where('user_id', auth()->id())->exists();
            @endphp

            @if ($hasOperator)
            <div class="text-red-500 text-center font-bold">
                You have already submitted your operator profile.
            </div>
            <a href="{{ route('operator.driver.create') }}">
                <x-button class="mt-4">Proceed to Driver Registration</x-button>
            </a>
            @else
            <h2 class="text-xl font-semibold leading-tight">
                Add Operator
            </h2>
            <form action="{{ route('operator.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        label="Last Name"
                        name="last_name"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="First Name"
                        name="first_name"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Middle Initial"
                        name="middle_initial"
                        :model="$operator ?? null"
                        maxlength="1"
                        pattern="[A-Za-z]"
                        title="Middle initial must be a single letter." />

                    <x-input
                        label="Barangay"
                        name="barangay"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Municipality"
                        name="municipality"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Province"
                        name="province"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Birth Date"
                        name="birth_date"
                        type="date"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Age"
                        name="age"
                        type="number"
                        :model="$operator ?? null"
                        required />

                    <x-input
                        label="Sex"
                        name="sex"
                        type="select"
                        :model="$operator ?? null"
                        :options="['Male' => 'Male', 'Female' => 'Female']"
                        required />

                    <x-input
                        label="Civil Status"
                        name="civil_status"
                        type="select"
                        :model="$operator ?? null"
                        :options="['Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated']"
                        required />

                    <x-input
                        label="Contact Number"
                        name="contact_no"
                        :model="$operator ?? null"
                        required />
                </div>

                <div class="mt-4">
                    <div class="flex justify-end">
                        <x-button type="button" id="submit-btn">
                            Submit
                        </x-button>
                    </div>
                </div>

                <!-- SweetAlert2 CDN -->
                <script>
                    document.getElementById('submit-btn').addEventListener('click', function(e) {
                        e.preventDefault(); // <- Prevent any default behavior
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
                                this.closest('form').submit(); // only one clean submission
                            }
                        });
                    });
                </script>

            </form>
            @endif
        </div>
        <div class="mt-4">

        </div>
    </div>
</x-guest-layout>