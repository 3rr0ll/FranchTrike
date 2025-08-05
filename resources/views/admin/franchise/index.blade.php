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
<div class="bg-white shadow-sm rounded-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="applications-table">
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
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Submitted</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary-navy bg-gray-100 border-gray-300 rounded focus:ring-primary-navy focus:ring-2 application-checkbox" value="{{ $application->id }}">
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $application->application_number }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $application->operator->last_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $application->driver->last_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ ucfirst($application->application_type) }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.franchise.show', $application) }}" class="font-medium text-primary-navy hover:underline">View</a>
                            @if($application->status === 'submitted')
                            <button type="button" class="text-yellow-600 hover:text-yellow-900" onclick="updateStatus({{ $application->id }}, 'under_review')">
                                Review
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Bulk Actions -->
<div class="mt-4 p-4 bg-white rounded-lg border border-gray-200" id="bulk-actions" style="display: none;">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
    <div class="flex items-center space-x-4">
        <select id="bulk-status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block p-2.5">
            <option value="">Select Status</option>
            <option value="under_review">Under Review</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <button type="button" onclick="bulkUpdateStatus()" class="text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:ring-4 focus:ring-primary-navy font-medium rounded-lg text-sm px-5 py-2.5">
            Update Status
        </button>
    </div>
</div>

<!-- Status Update Modal -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Application Status</h3>
            <form id="status-form" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5" required>
                        <option value="">Select Status</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div id="rejection-reason-field" class="mb-4 hidden">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5"></textarea>
                </div>
                
                <div id="franchise-details" class="mb-4 hidden">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Franchise Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="franchise_no" class="block text-sm font-medium text-gray-700 mb-1">Franchise No</label>
                            <input type="text" id="franchise_no" name="franchise_no" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5">
                        </div>
                        <div>
                            <label for="sticker_no" class="block text-sm font-medium text-gray-700 mb-1">Sticker No</label>
                            <input type="text" id="sticker_no" name="sticker_no" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5">
                        </div>
                        <div>
                            <label for="franchise_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="franchise_start_date" name="franchise_start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5">
                        </div>
                        <div>
                            <label for="franchise_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="franchise_end_date" name="franchise_end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="franchise_fee" class="block text-sm font-medium text-gray-700 mb-1">Franchise Fee</label>
                        <input type="number" step="0.01" id="franchise_fee" name="franchise_fee" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:ring-4 focus:ring-primary-navy font-medium rounded-lg text-sm px-5 py-2.5">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#applications-table').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'desc']], // Sort by Application # column descending
            columnDefs: [
                {
                    targets: 0, // Checkbox column
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 7, // Actions column
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
            }
        });

        // Checkbox functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
        const bulkActions = document.getElementById('bulk-actions');

        selectAllCheckbox.addEventListener('change', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkActions();
        });

        applicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleBulkActions);
        });

        function toggleBulkActions() {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            bulkActions.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
        }

        // Redraw table when page changes to maintain checkbox functionality
        table.on('draw', function() {
            // Re-attach event listeners to checkboxes after table redraw
            const newCheckboxes = document.querySelectorAll('.application-checkbox');
            newCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkActions);
            });
        });
    });

    // Status update functionality
    function updateStatus(applicationId, status) {
        const modal = document.getElementById('status-modal');
        const form = document.getElementById('status-form');
        const statusSelect = document.getElementById('status');
        
        form.action = `/admin/franchise/${applicationId}/status`;
        statusSelect.value = status;
        statusSelect.dispatchEvent(new Event('change'));
        
        modal.classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }

    // Status change handlers
    document.getElementById('status').addEventListener('change', function() {
        const rejectionField = document.getElementById('rejection-reason-field');
        const franchiseDetails = document.getElementById('franchise-details');
        
        rejectionField.classList.add('hidden');
        franchiseDetails.classList.add('hidden');
        
        if (this.value === 'rejected') {
            rejectionField.classList.remove('hidden');
        } else if (this.value === 'approved') {
            franchiseDetails.classList.remove('hidden');
        }
    });

    // Bulk update functionality
    function bulkUpdateStatus() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const status = document.getElementById('bulk-status').value;
        
        if (!status) {
            alert('Please select a status');
            return;
        }
        
        const applicationIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.franchise.bulk-update") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        applicationIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'application_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Close modal when clicking outside
    document.getElementById('status-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
</script>
@endpush
@endsection 