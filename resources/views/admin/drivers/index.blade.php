@extends('layouts.admin')

@section('title', 'Drivers')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2" >
Drivers List
</h2>
@endsection

@section('content')
<div class="w-full mt-4">


    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $drivers->count() }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Valid Licenses</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $drivers->where('license_validity', '>=', now())->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Expired Licenses</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $drivers->where('license_validity', '<', now())->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Distinct Natures</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $drivers->pluck('license_nature')->unique()->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 overflow-auto">
        <table id="drivers-table" class="table-auto row-border w-full text-left">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>License No</th>
                    <th>Validity</th>
                    <th>Nature</th>
                    <th>Contact No</th>
                    <th>Action</th>
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
                    <td>
                        <a href="{{ route('admin.documents.driver.show', ['driver' => $driver->driver_id]) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#drivers-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ]
        });
    });
</script>
@endpush
@endsection