@extends('layouts.admin')

@section('title', 'Motor Details')

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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                  </svg>                  
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Tricycles</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['tricycles'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters + Export (Right aligned) -->
<div class="flex flex-col sm:flex-row sm:justify-end gap-4 mb-4 mr-4">
    <div class="flex flex-wrap gap-2 items-center">
        <!-- Unit Type Filter -->
        <select id="filter-unit-type"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
            <option value="">All Unit Types</option>
            <option value="motocab">Motocab</option>
            <option value="tricycle">Tricycle</option>
        </select>

        <!-- Unit Make Filter -->   
        <select id="filter-unit-make"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
            <option value="">All Unit Makes</option>
            @foreach($unitMakes as $make)
            <option value="{{ strtolower($make->name) }}">{{ $make->name }}</option>
            @endforeach
        </select>

        <!-- Export Buttons -->
        <div id="export-buttons" class="flex flex-wrap gap-2 items-center"></div>
    </div>
</div>

<!-- Motor Details Table -->
<div class="bg-white shadow-sm rounded-lg p-4">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left row-border text-black" id="motor-details-table">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>Application #</th>
                    <th>Operator</th>
                    <th>Driver</th>
                    <th>Unit type</th>
                    <th>Unit make</th>
                    <th>Plate number</th>
                    <th>Motor no</th>
                    <th>Chasis no</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($motorDetails as $motorDetail)
                <tr class="bg-white border-b hover:bg-gray-50 text-black">
                    <td class="px-6 py-4 font-medium">
                        {{ $motorDetail->franchiseApplication->id ?? 'N/A' }}
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


                        <div class="flex space-x-2">
                            <a href="javascript:void(0);" onclick="toggleMotorDetailsModal(true)"  class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.motor-details.edit', $motorDetail) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.motor-details.destroy', $motorDetail) }}" method="POST" class="inline delete-motor-detail-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 hover:text-red-900 delete-motor-detail-btn">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>                            
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


<!-- Motor Details Modal -->
<div id="motorDetailsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-primary-navy">Motor Details</h2>
            <button onclick="toggleMotorDetailsModal(false)" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Motor Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Motor Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($motorDetail->unit_type == 'motocab') bg-blue-100 text-blue-800
                                    @elseif($motorDetail->unit_type == 'tricycle') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($motorDetail->unit_type) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit Make</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->unitMake->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Plate Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $motorDetail->platenumber }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Motor Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->motorno }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Chasis Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->chasisno }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Application Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Application Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $motorDetail->franchiseApplication->application_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Operator</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->franchiseApplication->operator->last_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Driver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->franchiseApplication->driver->last_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Application Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
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
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Route</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $motorDetail->franchiseApplication->route->name ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-3">
                <a href="{{ route('admin.motor-details.edit', $motorDetail) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy">
                    Edit Motor Details
                </a>

                @if($motorDetail->franchiseApplication)
                <a href="{{ route('admin.franchise.show', $motorDetail->franchiseApplication) }}" class="inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    View Application
                </a>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    $(document).ready(function() {
        var table = $('#motor-details-table').DataTable({
            dom: 'Bflrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    className: 'bg-blue-500 text-white px-3 py-1 rounded'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'bg-green-500 text-white px-3 py-1 rounded'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    className: 'bg-red-500 text-white px-3 py-1 rounded'
                },
            ],
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [6, 'desc']
            ],
            columnDefs: [{
                targets: 6, 
                orderable: false,
                searchable: false
            }],
            language: {
                search: "Search applications:",
                lengthMenu: "Show _MENU_ applications per page",
                info: "Showing _START_ to _END_ of _TOTAL_ applications",
                infoEmpty: "Showing 0 to 0 of 0 applications",
                infoFiltered: "(filtered from _MAX_ total applications)",
                zeroRecords: "No applications found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function() {
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
                );
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
                );

                var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
                var $length = $('.dataTables_length').css('margin', '0');
                var $search = $('.dataTables_filter').css('margin', '0');
                $controls.append($length).append($search);

                $controls.insertBefore($('#motor-details-table').closest('.overflow-x-auto'));
            }
        });

        // Move export buttons to custom div
        table.buttons().container().appendTo('#export-buttons');

        // Custom filtering
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'motor-details-table') return true;

            var unitType = $('#filter-unit-type').val();
            var status = $('#filter-status').val();
            var unitMake = $('#filter-unit-make').val();

            var unitTypeCol = $('<div>').html(data[3]).text().trim().toLowerCase();
            var unitMakeCol = $('<div>').html(data[4]).text().trim().toLowerCase();
            var statusCol = $('<div>').html(data[8]).text().trim().toLowerCase();

            // Filter by unit type (exact match)
            if (unitType && unitTypeCol !== unitType) return false;

            // Filter by unit make (exact match)
            if (unitMake && unitMakeCol !== unitMake) return false;

            return true;
        });

        // Trigger filter redraw on change
        $('#filter-unit-type, #filter-unit-make').on('change', function() {
            table.draw();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-motor-detail-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = btn.closest('form');
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
                    form.submit();
                }
            });
        });
    });
});

    function toggleMotorDetailsModal(show = true) {
        const modal = document.getElementById('motorDetailsModal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection