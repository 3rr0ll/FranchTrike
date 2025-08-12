<x-guest-layout>
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">Driver Details</h2>
        <a href="{{ route('operator.driver.index') }}" class="inline-block mt-6 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Back to List</a>

        <div class="bg-white shadow rounded-lg p-6">
            <h4 class="text-xl font-semibold mb-4">{{ $driver->full_name }}</h4>
            <div class="space-y-2">
                <p><span class="font-semibold">Address:</span> {{ $driver->full_address }}</p>
                <p><span class="font-semibold">Birth Date:</span> {{ $driver->birth_date->format('F d, Y') }}</p>
                <p><span class="font-semibold">Age:</span> {{ $driver->age }}</p>
                <p><span class="font-semibold">Sex:</span> {{ ucfirst($driver->sex) }}</p>
                <p><span class="font-semibold">Civil Status:</span> {{ ucfirst($driver->civil_status) }}</p>
                <p><span class="font-semibold">Contact Number:</span> {{ $driver->contact_no }}</p>
                <hr class="my-4">
                <p><span class="font-semibold">License No:</span> {{ $driver->license_no }}</p>
                <p><span class="font-semibold">License Validity:</span> {{ optional($driver->license_validity)->format('F d, Y') }}</p>
                <p><span class="font-semibold">License Nature:</span> {{ $driver->license_nature }}</p>
                <p><span class="font-semibold">License Status:</span> {{ ucfirst($driver->license_status) }}</p>
            </div>
        </div>

    </div>
</x-guest-layout>
