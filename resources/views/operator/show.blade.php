<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Operator Information</h2>
        <p><strong>Name:</strong> {{ $operator->first_name }} {{ $operator->middle_initial }} {{ $operator->last_name }}</p>
        <p><strong>Contact No:</strong> {{ $operator->contact_no }}</p>
        <p><strong>Municipality:</strong> {{ $operator->municipality }}</p>

        <hr class="my-4">

        <h3 class="text-lg font-semibold">Associated Drivers</h3>
        <ul class="list-disc list-inside">
            @foreach($operator->drivers as $driver)
            <li>
                <a href="{{ route('admin.drivers.show', $driver->id) }}" class="text-blue-600 hover:underline">
                    {{ $driver->first_name }} {{ $driver->last_name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>