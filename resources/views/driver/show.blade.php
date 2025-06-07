<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Driver Information</h2>
        <p><strong>Name:</strong> {{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}</p>
        <p><strong>Birth Date:</strong> {{ $driver->birth_date }}</p>
        <p><strong>Age:</strong> {{ $driver->age }}</p>
        <p><strong>Sex:</strong> {{ $driver->sex }}</p>
        <p><strong>Civil Status:</strong> {{ $driver->civil_status }}</p>
        <p><strong>Contact No:</strong> {{ $driver->contact_no }}</p>
        <p><strong>Address:</strong> {{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</p>
        <p><strong>License No:</strong> {{ $driver->license_no }}</p>
        <p><strong>License Validity:</strong> {{ $driver->license_validity }}</p>
        <p><strong>License Nature:</strong> {{ $driver->license_nature }}</p>
    </div>
</x-app-layout>