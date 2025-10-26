@extends('layouts.admin')

@section('title', 'All Documents')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        All Documents
    </h2>
@endsection

@section('content')

    {{-- Statistics Cards --}}
    @php
        $statusCounts = [
            'approved' => $documents->where('status', 'approved')->count(),
            'pending' => $documents->where('status', 'pending')->count(),
            'rejected' => $documents->where('status', 'rejected')->count(),
        ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
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
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statusCounts['pending'] }}</p>
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

    <div class="p-4 border-b border-gray-200 ">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full sm:w-auto">

                <!-- Type Filter -->
                <select id="filter-user-type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                    <option value="">All Types</option>
                    <option value="Operator">Operator</option>
                    <option value="Driver">Driver</option>
                </select>

                <!-- Document Filter -->
                <select id="filter-document-type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                    <option value="">All Documents</option>
                    @php
                        $docTypes = collect($documents)->pluck('document_type')->unique()->filter();
                    @endphp
                    @foreach($docTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select id="filter-status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                    <option value="">All Statuses</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>

                <!-- Date Range Picker -->
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">From:</span>
                    <input type="date" id="filter-date-start"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                    <span class="text-gray-600">to</span>
                    <input type="date" id="filter-date-end"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table id="documents-table" class="min-w-full divide-y divide-gray-200 row-border">
                <thead class="bg-gray-50">
                    <tr class="tracking-wider text-gray-500  text-left text-md font-medium">
                        <th>User</th>
                        <th>Type</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th class="text-gray-500">Uploaded</th>
                        <th class="text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($documents as $i => $doc)
                        @php
                            $encryptedId = encrypt($doc['id']);
                        @endphp
                        <tr class="px-4 py-2 whitespace-nowrap">
                            <td>{{ $doc['user_name'] }}</td>
                            <td>{{ $doc['user_type'] }}</td>
                            <td>{{ $doc['document_type'] }}</td>
                            <td>
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($doc['status'] === 'approved') bg-green-100 text-green-800
                                    @elseif($doc['status'] === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($doc['status'] === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($doc['status']) }}
                                </span>
                            </td>
                            <td data-date="{{ $doc['created_at']->format('Y-m-d') }}">
                                {{ $doc['created_at']->format('M d, Y') }}</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   onclick="openDocumentModal('{{ $doc['url'] }}', '{{ $doc['document_type'] }}', '{{ $encryptedId }}', '{{ $doc['user_type'] }}')" 
                                   class="text-blue-600 hover:text-blue-800" 
                                   title="View Document">
                                   <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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

    <!-- Document Modal -->
    <div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50 hidden">
        <div class="relative mx-auto my-12 w-full max-w-6xl bg-white rounded-md shadow-lg">
            <div class="flex flex-col h-[90vh] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
                    <button onclick="closeDocumentModal()"
                        class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-hidden" id="documentViewerContainer">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <div class="flex justify-end mt-4 space-x-3">
                    <form id="verifyForm" method="POST">
                        @csrf
                        <input type="hidden" name="status" id="documentStatus">
                        <div id="rejectionReasonBox" class="hidden mt-2">
                            <textarea name="rejection_reason" id="rejectionReason" rows="2"
                                placeholder="Enter reason for rejection..."
                                class="w-full border rounded p-2 text-sm text-gray-800"></textarea>
                        </div>
                        <div class="flex gap-2 mt-2 justify-end">
                            <x-button type="button" onclick="submitVerification('approved')">Approve</x-button>
                            <x-button type="button" color="red" onclick="showRejectReason()">Reject</x-button>
                            <x-button type="button" class="hidden" id="submitButton"
                                onclick="submitVerification('rejected')">Submit</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDocumentId = null;
        let currentUserType = null;

        function openDocumentModal(fileUrl, documentName, documentId, userType) {
            document.getElementById('modalTitle').textContent = documentName + ' (' + userType + ')';
            currentDocumentId = documentId;
            currentUserType = userType;

            const container = document.getElementById('documentViewerContainer');

            // Check if it's a PDF or image based on file extension or URL
            const isPdf = fileUrl.toLowerCase().includes('.pdf') || fileUrl.toLowerCase().includes('image/upload') === false;
            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(fileUrl) || fileUrl.includes('image/upload');

            if (isImage && !isPdf) {
                // Display as image
                container.innerHTML = `
                    <img src="${fileUrl}" 
                         alt="${documentName}" 
                         class="w-full h-full object-contain rounded-md border border-gray-300" 
                         style="min-height:600px;" />
                `;
            } else {
                // Display as iframe (works for PDFs)
                container.innerHTML = `
                    <iframe src="${fileUrl}" 
                            class="w-full h-full rounded-md border border-gray-300" 
                            style="min-height:600px;" 
                            frameborder="0">
                        <p>Your browser does not support iframes. 
                           <a href="${fileUrl}" target="_blank">Click here to view the document</a>
                        </p>
                    </iframe>
                `;
            }

            document.getElementById('documentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentViewerContainer').innerHTML = '';
            document.getElementById('rejectionReasonBox').classList.add('hidden');
            document.getElementById('rejectionReason').value = '';
            document.getElementById('submitButton').classList.add('hidden');
            document.getElementById('documentStatus').value = '';
            document.body.style.overflow = 'auto';
        }

        function showRejectReason() {
            document.getElementById('rejectionReasonBox').classList.remove('hidden');
            document.getElementById('submitButton').classList.remove('hidden');
            document.getElementById('documentStatus').value = 'rejected';
        }

        function submitVerification(status) {
            document.getElementById('documentStatus').value = status;
            const form = document.getElementById('verifyForm');
            const baseUrl = '{{ url("/") }}';

            if (currentDocumentId && currentUserType) {
                if (currentUserType === 'Driver') {
                    form.action = `${baseUrl}/admin/documents/driver/${currentDocumentId}/verify`;
                } else if (currentUserType === 'Operator') {
                    form.action = `${baseUrl}/admin/documents/operator/${currentDocumentId}/verify`;
                }
            }

            if (status === 'rejected') {
                const reason = document.getElementById('rejectionReason').value.trim();
                if (!reason) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Rejection Reason Required',
                        text: 'Please provide a reason for rejection.',
                    });
                    return;
                }
            }

            form.submit();
        }

        $(document).ready(function () {
            var table = $('#documents-table').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [5, 'desc']
                ],
                columnDefs: [{
                    targets: 5,
                    orderable: false,
                    searchable: false
                }],
                language: {
                    search: "Search documents:",
                    lengthMenu: "Show _MENU_ documents per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ documents",
                    infoEmpty: "Showing 0 to 0 of 0 documents",
                    infoFiltered: "(filtered from _MAX_ total documents)",
                    zeroRecords: "No documents have been submitted yet.",
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

                    $controls.insertBefore($('#documents-table').closest('.overflow-x-auto'));
                }
            });

            // Custom filtering function for all filters
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                // Only apply to our table
                if (settings.nTable.id !== 'documents-table') return true;

                // Get filter values
                var userType = $('#filter-user-type').val();
                var docType = $('#filter-document-type').val();
                var status = $('#filter-status').val();
                var dateStart = $('#filter-date-start').val();
                var dateEnd = $('#filter-date-end').val();

                // Get row values
                var rowUserType = data[1] || ''; // Type column
                var rowDocType = data[2] || ''; // Document column
                var rowStatus = (data[3] || '').replace(/<[^>]+>/g, '').trim().toLowerCase(); // Status column, strip HTML

                // For date, get the raw date from the cell's data-date attribute
                var rowDateCell = table.row(dataIndex).node().querySelector('td[data-date]');
                var rowDate = rowDateCell ? rowDateCell.getAttribute('data-date') : '';

                // Filter by user type
                if (userType && rowUserType !== userType) return false;

                // Filter by document type
                if (docType && rowDocType !== docType) return false;

                // Filter by status
                if (status && rowStatus !== status.toLowerCase()) return false;

                // Filter by date range
                if (rowDate) {
                    var rowDateObj = new Date(rowDate);
                    if (dateStart) {
                        var startDateObj = new Date(dateStart);
                        if (rowDateObj < startDateObj) return false;
                    }
                    if (dateEnd) {
                        var endDateObj = new Date(dateEnd);
                        // Add 1 day to end date to make it inclusive
                        endDateObj.setDate(endDateObj.getDate() + 1);
                        if (rowDateObj >= endDateObj) return false;
                    }
                }

                return true;
            });

            // Redraw table on filter change
            $('#filter-user-type, #filter-document-type, #filter-status, #filter-date-start, #filter-date-end').on('change', function () {
                table.draw();
            });
        });
    </script>

    @if (session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('status') }}",
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif
@endsection