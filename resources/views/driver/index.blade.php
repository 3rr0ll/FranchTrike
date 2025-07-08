<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">My Registered Drivers</h1>

        @if($drivers->isEmpty())
        <p class="text-gray-500">You haven't registered any drivers yet.</p>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($drivers as $driver)
            <div class="bg-white p-4 rounded shadow border">
                <h2 class="text-lg font-semibold">{{ $driver->first_name }} {{ $driver->last_name }}</h2>
                <p class="text-sm text-gray-600">License #: {{ $driver->license_no }}</p>
                <p class="text-sm text-gray-600">Contact: {{ $driver->contact_no }}</p>
                <p class="text-sm text-gray-600">Age: {{ $driver->age }}</p>
                <p class="text-sm text-gray-600">Sex: {{ ucfirst($driver->sex) }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>