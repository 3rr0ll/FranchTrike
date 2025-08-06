@extends('layouts.admin')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Motor Details Management</h1>
        </div>
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100">
            <div class="flex items-center mb-4 sm:mb-0">
                <form class="sm:pr-3" action="{{ route('admin.motor-details.index') }}" method="GET">
                    <label for="motor-search" class="sr-only">Search</label>
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <input type="text" name="search" id="motor-search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5" placeholder="Search motor details">
                    </div>
                </form>
                <div class="flex items-center w-full sm:justify-end">
                    <div class="hidden pl-2 space-x-1 md:flex">
                        <a href="{{ route('admin.motor-details.export') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Units</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_units'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Approved Units</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_units'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Motocabs</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['motocabs'] }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Tricycles</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['tricycles'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
    <form action="{{ route('admin.motor-details.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="unit_type" class="block text-sm font-medium text-gray-700 mb-1">Unit Type</label>
            <select name="unit_type" id="unit_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                <option value="">All Types</option>
                <option value="motocab" {{ request('unit_type') == 'motocab' ? 'selected' : '' }}>Motocab</option>
                <option value="tricycle" {{ request('unit_type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
            </select>
        </div>
        <div>
            <label for="unit_make_id" class="block text-sm font-medium text-gray-700 mb-1">Unit Make</label>
            <select name="unit_make_id" id="unit_make_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                <option value="">All Makes</option>
                @foreach($unitMakes as $make)
                <option value="{{ $make->id }}" {{ request('unit_make_id') == $make->id ? 'selected' : '' }}>{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="application_status" class="block text-sm font-medium text-gray-700 mb-1">Application Status</label>
            <select name="application_status" id="application_status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                <option value="">All Statuses</option>
                @foreach($applicationStatuses as $status)
                <option value="{{ $status }}" {{ request('application_status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary-navy text-white px-4 py-2 rounded-md hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Motor Details Table -->
<div class="bg-white shadow-sm rounded-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="motor-details-table">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary-navy bg-gray-100 border-gray-300 rounded focus:ring-primary-navy focus:ring-2" id="select-all">
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">Application #</th>
                    <th scope="col" class="px-6 py-3">Operator</th>
                    <th scope="col" class="px-6 py-3">Driver</th>
                    <th scope="col" class="px-6 py-3">Unit Type</th>
                    <th scope="col" class="px-6 py-3">Unit Make</th>
                    <th scope="col" class="px-6 py-3">Plate Number</th>
                    <th scope="col" class="px-6 py-3">Motor No</th>
                    <th scope="col" class="px-6 py-3">Chasis No</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($motorDetails as $motorDetail)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="w-4 h-4 text-primary-navy bg-gray-100 border-gray-300 rounded focus:ring-primary-navy focus:ring-2 motor-detail-checkbox" value="{{ $motorDetail->id }}">
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $motorDetail->franchiseApplication->application_number ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $motorDetail->franchiseApplication->operator->last_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $motorDetail->franchiseApplication->driver->last_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($motorDetail->unit_type == 'motocab') bg-blue-100 text-blue-800
                            @elseif($motorDetail->unit_type == 'tricycle') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($motorDetail->unit_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $motorDetail->unitMake->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 font-medium">
                        {{ $motorDetail->platenumber }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $motorDetail->motorno }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $motorDetail->chasisno }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $status = $motorDetail->franchiseApplication->status ?? 'unknown';
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($status == 'approved') bg-green-100 text-green-800
                            @elseif($status == 'rejected') bg-red-100 text-red-800
                            @elseif($status == 'under_review') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.motor-details.show', $motorDetail) }}" class="text-primary-navy hover:text-primary-gold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.motor-details.edit', $motorDetail) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button onclick="deleteMotorDetail({{ $motorDetail->id }})" class="text-red-600 hover:text-red-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                        No motor details found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bulk Actions -->
<div class="mt-4 p-4 bg-white rounded-lg border border-gray-200" id="bulk-actions" style="display: none;">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
    <form action="{{ route('admin.motor-details.bulk-update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @csrf
        <input type="hidden" name="motor_detail_ids" id="selected-motor-details">
        <div>
            <label for="bulk_unit_type" class="block text-sm font-medium text-gray-700 mb-1">Update Unit Type</label>
            <select name="unit_type" id="bulk_unit_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                <option value="">No Change</option>
                <option value="motocab">Motocab</option>
                <option value="tricycle">Tricycle</option>
            </select>
        </div>
        <div>
            <label for="bulk_unit_make_id" class="block text-sm font-medium text-gray-700 mb-1">Update Unit Make</label>
            <select name="unit_make_id" id="bulk_unit_make_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                <option value="">No Change</option>
                @foreach($unitMakes as $make)
                <option value="{{ $make->id }}">{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary-navy text-white px-4 py-2 rounded-md hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy">
                Update Selected
            </button>
        </div>
    </form>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $motorDetails->links() }}
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.motor-detail-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    document.querySelectorAll('.motor-detail-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.motor-detail-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedIds = document.getElementById('selected-motor-details');

        if (checkboxes.length > 0) {
            bulkActions.style.display = 'block';
            const values = [];
            checkboxes.forEach(function(cb) {
                values.push(cb.value);
            });
            selectedIds.value = values.join(',');
        } else {
            bulkActions.style.display = 'none';
        }
    }


    function deleteMotorDetail(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/motor-details/${id}`;
                form.submit();
            }
        });
    }
</script>
@endsection