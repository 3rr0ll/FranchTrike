@extends('layouts.superadmin')

@section('title', 'Franchise Applications')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Franchise Applications
</h2>
@endsection

@section('content')

<!-- Status Counts -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-4">
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Submitted</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['submitted'] }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Under Review</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['under_review'] }}</p>
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
                <p class="text-sm font-medium text-gray-600">Approved</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['approved'] }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Renewed</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['renewed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-lg border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['rejected'] }}</p>
            </div>
        </div>
    </div>
<div class="p-4 bg-white rounded-lg border border-gray-200">
    <div class="flex items-center">
        <div class="p-2 rounded-full bg-gray-200 text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Expired</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['expired'] ?? 0 }}</p>
        </div>
    </div>
</div>
</div>

<div class="p-4 border-b border-gray-200 lg:mt-1.5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    
        <!-- Right: Filters + Date Range Picker + Export Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full sm:w-auto">

            <!-- Application Type Filter -->
            <select id="application_type"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                <option value="">All Types</option>
                <option value="new">New</option>
                <option value="renewal">Renewal</option>
            </select>

            <!-- Status Filter -->
            <select id="status"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                <option value="">All Statuses</option>
                <option value="submitted">Submitted</option>
                <option value="Under review">Under Review</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="renewed">Renewed</option>
                <option value="expired">Expired</option>
            </select>
            
            <!-- Date Range Picker -->
            <div class="flex items-center gap-2">
                <span class="text-gray-600">From:</span>
                <input type="date" id="datepicker-range-start"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                <span class="text-gray-600">to</span>
                <input type="date" id="datepicker-range-end"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
            </div>

            <!-- Export Buttons -->
            <div id="export-buttons" class="flex flex-wrap gap-2 items-center">
                <!-- Buttons will be injected here by DataTables -->
            </div>
        </div>
    </div>
</div>



<!-- Applications Table -->
<div class="p-4 bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-left row-border" id="applications-table">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>Application #</th>
                    <th>Operator</th>
                    <th>Driver</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Reviewer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td class="px-8 py-5 font-medium">{{ $application->id }}</td>
                    <td class="px-8 py-5">{{ $application->operator->last_name }}</td>
                    <td class="px-8 py-5">{{ $application->driver->last_name ?? 'N/A' }}</td>
                    <td class="px-8 py-5">{{ ucfirst($application->application_type) }}</td>
                    <td class="px-8 py-5">
                        @php
                        $statusColors = [
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'under_review' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'expired' => 'bg-gray-300 text-gray-700',
                        'renewed' => 'bg-purple-100 text-purple-800',
                        ];
                        $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        {{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-8 py-5">
                        {{ $application->reviewer ? $application->reviewer->name : 'N/A' }}
                    </td>
                    <td class="px-8 py-5">
                        <a href="{{ route('superadmin.franchise.show', encrypt($application->id)) }}"
                            class="text-blue-600 hover:underline">   
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5 c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
        // Initialize DataTable
        var table = $('#applications-table').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [5, 'desc']
            ], // Submitted column
            columnDefs: [{
                targets: 6, // Actions column
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
            dom: '<"hidden"lfB>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"ip>',
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
            initComplete: function() {
                // Move export buttons to custom container and enhance layout
                var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                $('#export-buttons').empty().append(btns);

                // Styling select + search
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
                );
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
                );
                $('#applications-table').closest('.overflow-x-auto').css('padding', '12px');

                var $controls = $('<div class="w-full flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"></div>');
                var $search = $('.dataTables_filter').addClass('mb-2 sm:mb-0');
                var $length = $('.dataTables_length').addClass('mb-2 sm:mb-0');
                $controls.append($length).append($search);
                $controls.insertBefore($('#applications-table').closest('.overflow-x-auto'));
            }
        });

        // --- Date Range Filter ---
        $.fn.dataTable.ext.search.push(function(settings, data) {
            if (settings.nTable.id !== 'applications-table') return true;

            var start = $('#datepicker-range-start').val();
            var end = $('#datepicker-range-end').val();
            var submitted = data[5]; // Submitted column

            if (!submitted || submitted === 'N/A') return false;

            var submittedDate = new Date(submitted);
            if (start && submittedDate < new Date(start)) return false;
            if (end && submittedDate > new Date(end)) return false;

            return true;
        });

        // Re-draw when date inputs change
        $('#datepicker-range-start, #datepicker-range-end').on('change', function() {
            table.draw();
        });

        $('#application_type, #status').on('change', function() {
            table.draw();
        });

        $.fn.dataTable.ext.search.push(function(settings, data) {
            if (settings.nTable.id !== 'applications-table') return true;

            var typeFilter = $('#application_type').val();
            var statusFilter = $('#status').val();

            var applicationType = data[3].toLowerCase();
            var status = data[4].toLowerCase();

            if (typeFilter && applicationType !== typeFilter.toLowerCase()) {
                return false;
            }
            if (statusFilter && status.indexOf(statusFilter.toLowerCase()) === -1) {
                return false;
            }

            return true;
        });
    });
</script>

@endpush
@endsection