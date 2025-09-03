@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
Motor Details Management
</h2>
@endsection

@section('content')


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

<!-- Motor Details Table -->
<div class="bg-white shadow-sm rounded-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black" id="motor-details-table">
            <thead class="text-xs  bg-gray-50 text-black">
                <tr>
                    <th><strong>Application #</strong></th>
                    <th><strong>Operator</strong></th>
                    <th><strong>Driver</strong></th>
                    <th><strong>Unit type</strong></th>
                    <th><strong>Unit make</strong></th>
                    <th><strong>Plate number</strong></th>
                    <th><strong>Motor no</strong></th>
                    <th><strong>Chasis no</strong></th>
                    <th><strong>Status</strong></th>
                    <th><strong>Actions</strong></th>
                </tr>
            </thead>
            <tbody>
                @forelse($motorDetails as $motorDetail)
                <tr class="bg-white border-b hover:bg-gray-50 text-black">
                    <td class="px-6 py-4 font-medium">
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
                            @else bg-gray-100 text-black
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
                            @else bg-gray-100 text-black
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
                    <td colspan="10" class="px-6 py-4 text-center text-black">
                        No motor details found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    $(document).ready(function() {
        $('#motor-details-table').DataTable({
            "order": [],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "previous": "Prev",
                    "next": "Next"
                }
            }
        });
    });

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