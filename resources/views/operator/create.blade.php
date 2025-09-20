<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-[#e5e5e4] p-4">
        <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-3xl border border-primary-gold">
            @php
                $hasOperator = \App\Models\Operator::where('user_id', auth()->id())->exists();
            @endphp

            @if ($hasOperator)
                <div class="flex flex-col items-center justify-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-accent-red mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414A9 9 0 105.636 18.364l1.414 1.414A9 9 0 1018.364 5.636z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9l6 6m0-6l-6 6" />
                    </svg>
                    <div class="text-accent-red text-center font-bold text-lg">
                        You have already submitted your operator profile.
                    </div>
                </div>
            @else
                <div class="mb-6 flex items-center gap-3">
                    <div class="bg-primary-gold rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707" />
                        </svg>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-primary-navy tracking-tight">
                        Operator Profile Registration
                    </h1>
                </div>
                <div class="mb-4">
                    <p class="text-gray-600 text-sm">
                        Please fill out the form below to register as an operator. All fields marked with <span class="text-accent-red">*</span> are required.
                    </p>
                </div>
                <x-validation-errors class="mb-4" />
                <form action="{{ route('operator.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input
                            label="Last Name"
                            name="last_name"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="First Name"
                            name="first_name"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Middle Initial"
                            name="middle_initial"
                            :model="$operator ?? null"
                            maxlength="1"
                            pattern="[A-Za-z]"
                            title="Middle initial must be a single letter."
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Barangay"
                            name="barangay"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Municipality"
                            name="municipality"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Province"
                            name="province"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Birth Date"
                            name="birth_date"
                            type="date"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Age"
                            name="age"
                            type="number"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Sex"
                            name="sex"
                            type="select"
                            :model="$operator ?? null"
                            :options="['Male' => 'Male', 'Female' => 'Female']"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Civil Status"
                            name="civil_status"
                            type="select"
                            :model="$operator ?? null"
                            :options="['Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated']"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />

                        <x-input
                            label="Contact Number"
                            name="contact_no"
                            :model="$operator ?? null"
                            required
                            class="bg-[#f9fafb] border-primary-gold focus:ring-primary-gold"
                        />
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-button type="button" id="submit-btn" class="bg-primary-navy hover:bg-primary-gold text-white font-semibold px-8 py-2 rounded shadow transition-colors duration-200">
                            <span class="flex items-center gap-2">

                                Submit
                            </span>
                        </x-button>
                    </div>

                    <!-- SweetAlert2 CDN -->
                    <script>
                        document.getElementById('submit-btn').addEventListener('click', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "Do you want to submit this information?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#1a2742', // primary-navy
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
            @endif
        </div>
    </div>
</x-guest-layout>