@extends('layouts.admin')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Franchise Applications</h1>
        </div>
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="flex items-center w-full sm:justify-end">
                    <div class="hidden pl-2 space-x-1 md:flex">
                        <a href="{{ route('admin.franchise.export') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
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

<!-- Status Counts -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
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
</div>

<!-- Applications Table -->
<div class="p-6 bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-left" id="applications-table">
            <thead>
                <tr>
                    <th scope="col" class="px-8 py-4">Application #</th>
                    <th scope="col" class="px-8 py-4">Operator</th>
                    <th scope="col" class="px-8 py-4">Driver</th>
                    <th scope="col" class="px-8 py-4">Type</th>
                    <th scope="col" class="px-8 py-4">Status</th>
                    <th scope="col" class="px-8 py-4">Submitted</th>
                    <th scope="col" class="px-8 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-8 py-5 font-medium text-gray-900 whitespace-nowrap">
                        {{ $application->application_number }}
                    </td>
                    <td class="px-8 py-5">
                        {{ $application->operator->last_name }}
                    </td>
                    <td class="px-8 py-5">
                        {{ $application->driver->last_name ?? 'N/A' }}
                    </td>
                    <td class="px-8 py-5">
                        {{ ucfirst($application->application_type) }}
                    </td>
                    <td class="px-8 py-5">
                        @php
                        $statusColors = [
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'under_review' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        {{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.franchise.show', $application) }}" class="font-medium text-primary-navy hover:underline">View</a>
                        </div>
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
            // Order by the "Submitted" column (index 5) descending, so most recent is on top
            order: [
                [5, 'desc']
            ],
            columnDefs: [
                {
                    targets: 6, // Actions column
                    orderable: false,
                    searchable: false
                }
            ],
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
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"ip>',
            initComplete: function() {
                // Add custom styling to DataTable elements
                $('.dataTables_length select').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block p-2.5');
                $('.dataTables_filter input').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block p-2.5');
                // Add a little padding to the DataTable wrapper
                $('#applications-table').closest('.overflow-x-auto').css('padding', '12px');
            }
        });
    });
</script>
@endpush
@endsection