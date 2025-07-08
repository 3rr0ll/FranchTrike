<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">My Drivers</h1>

        @if ($drivers->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Name</th>
                        <th class="px-4 py-2 border-b">Address</th>
                        <th class="px-4 py-2 border-b">License No</th>
                        <th class="px-4 py-2 border-b">Validity</th>
                        <th class="px-4 py-2 border-b">Nature</th>
                        <th class="px-4 py-2 border-b">Contact No</th>
                        <th class="px-4 py-2 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $driver)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}</td>
                        <td class="px-4 py-2 border-b">{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</td>
                        <td class="px-4 py-2 border-b">{{ $driver->license_no }}</td>
                        <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($driver->license_validity)->format('M d, Y') }}</td>
                        <td class="px-4 py-2 border-b">{{ $driver->license_nature }}</td>
                        <td class="px-4 py-2 border-b">{{ $driver->contact_no }}</td>
                        <td class="px-4 py-2 border-b">
                            <a href="{{ route('operator.driver.show', ['driver' => $driver->driver_id]) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500">You currently have no assigned drivers.</p>
        @endif
    </div>
</x-app-layout>