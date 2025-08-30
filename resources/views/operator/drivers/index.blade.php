@extends('layouts.operator')

@section('header')
    <h1 class="text-3xl font-bold mb-6">My Drivers</h1>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if ($drivers->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($drivers as $driver)
                    <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between h-full">
                        <div>
                            <h2 class="text-xl font-semibold text-primary-navy mb-2">
                                {{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}
                            </h2>
                            <div class="mb-2">
                                <span class="text-gray-500 text-sm">Address:</span>
                                <span class="text-gray-800">{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-500 text-sm">License No:</span>
                                <span class="text-gray-800">{{ $driver->license_no }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-500 text-sm">Validity:</span>
                                <span class="text-gray-800">{{ \Carbon\Carbon::parse($driver->license_validity)->format('M d, Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-500 text-sm">Nature:</span>
                                <span class="text-gray-800">{{ $driver->license_nature }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-500 text-sm">Contact No:</span>
                                <span class="text-gray-800">{{ $driver->contact_no }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('operator.driver.show', ['driver' => $driver->driver_id]) }}">
                                <x-button>
                                    View Details
                                </x-button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">You currently have no assigned drivers.</p>
        @endif
    </div>
@endsection