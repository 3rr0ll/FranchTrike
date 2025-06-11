<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Drivers List</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-6 rounded-lg">
            <table class="table-auto w-full text-left">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>License No</th>
                        <th>Validity</th>
                        <th>Nature</th>
                        <th>Contact No</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}</td>
                        <td>{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</td>
                        <td>{{ $driver->license_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($driver->license_validity)->format('M d, Y') }}</td>
                        <td>{{ $driver->license_nature }}</td>
                        <td>{{ $driver->contact_no }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>