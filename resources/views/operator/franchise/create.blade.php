<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Apply for Franchise</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        @if (session('error'))
        <div class="mb-4 text-red-600 font-semibold">{{ session('error') }}</div>
        @endif
        @if (session('success'))
        <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('operator.franchise.store') }}">
            @csrf

            {{-- Application Type --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Application Type</label>
                <select name="application_type" class="mt-1 block w-full border rounded p-2">
                    <option value="new">New</option>
                    <option value="renewal">Renewal</option>
                </select>
            </div>

            {{-- Previous Application ID (only visible if Renewal is selected) --}}
            <div class="mb-4" id="previousAppDiv" style="display: none;">
                <label class="block text-sm font-medium text-gray-700">Previous Application ID</label>
                <input type="text" name="previous_application_id" class="mt-1 block w-full border rounded p-2">
            </div>

            {{-- Driver --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Select Driver</label>
                <select name="driver_id" class="mt-1 block w-full border rounded p-2" required>
                    @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}">
                        {{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Franchise No & Sticker No --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Franchise No</label>
                    <input type="text" name="franchise_no" class="mt-1 block w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sticker No</label>
                    <input type="text" name="sticker_no" class="mt-1 block w-full border rounded p-2">
                </div>
            </div>

            {{-- Operator Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Operator Name</label>
                <input type="text" name="operator_name" value="{{ Auth::user()->name }}" class="mt-1 block w-full border rounded p-2" readonly>
            </div>

            {{-- CTC Details --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC No</label>
                    <input type="text" name="ctc_no" class="mt-1 block w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC Date Issued</label>
                    <input type="date" name="ctc_date_issued" class="mt-1 block w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTC Place Issued</label>
                    <input type="text" name="ctc_place_issued" class="mt-1 block w-full border rounded p-2">
                </div>
            </div>

            {{-- Franchise Fee --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Franchise Fee</label>
                <input type="number" name="franchise_fee" step="0.01" class="mt-1 block w-full border rounded p-2">
            </div>

            <x-button type="submit" class="w-full justify-center">
                Submit Franchise Application
            </x-button>
        </form>
    </div>

    <script>
        // Show/hide previous app field if renewal
        document.addEventListener('DOMContentLoaded', function() {
            const appType = document.querySelector('select[name="application_type"]');
            const prevAppDiv = document.getElementById('previousAppDiv');
            appType.addEventListener('change', function() {
                prevAppDiv.style.display = this.value === 'renewal' ? 'block' : 'none';
            });
        });
    </script>
</x-app-layout>