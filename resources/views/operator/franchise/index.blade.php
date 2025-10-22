@extends('layouts.operator')

@section('title', 'Franchise Applications')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Franchise Applications
</h2>
@endsection

@section('content')

<div class="w-full mt-6">
    <div class="bg-white shadow p-6 rounded-lg">
        @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
            @if ($canApply)
            <a href="{{ route('operator.franchise.create') }}">
                <x-button>Submit New Application</x-button>
            </a>
            @else
            <x-button disabled>Complete Document Approval to Apply</x-button>
            @endif
        </div>

        {{-- Active Franchise Cards --}}
        @php
        $activeFranchises = $applications->where('status', 'approved')->sortByDesc('submitted_at')->take(2);
        $renewedFranchises = $applications->filter(function($app) {
            return $app->status !== 'approved';
        });
        @endphp

        @if($activeFranchises->count())
        <div class="mb-8">
            <h4 class="text-md font-semibold mb-3 text-primary-navy">Active Franchise(s)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($activeFranchises as $app)
                <div class="border rounded-lg shadow p-5 bg-white flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-primary-navy mb-1">Franchise #{{ $app->franchise_no ?? '-' }}</div>
                        <div class="text-sm text-gray-600 mb-2">Submitted: {{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</div>
                        <div class="text-sm text-gray-700 mb-2">
                            Expiration:
                            {{ $app->franchise_end_date ? \Carbon\Carbon::parse($app->franchise_end_date)->format('M d, Y') : '-' }}
                        </div>
                    </div>
                    <div class="flex gap-2 mt-auto">

                    </div>
                    <div class="flex gap-2 mt-2 justify-end">
                        @php
                            $encryptedId = encrypt($app->id);
                        @endphp
                        <x-button
                            size="sm"
                            type="button"
                            class="open-franchise-details-modal"
                            data-status="{{ $app->status ?? '' }}"
                            data-application-type="{{ ucfirst($app->application_type ?? '') }}"
                            data-franchise-no="{{ $app->franchise_no ?? '-' }}"
                            data-submitted="{{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}"
                            data-expiry="{{ $app->franchise_end_date ? \Carbon\Carbon::parse($app->franchise_end_date)->format('M d, Y') : '-' }}"
                            data-operator="{{ $app->operator ? trim($app->operator->first_name . ' ' . $app->operator->middle_initial . ' ' . $app->operator->last_name) : '-' }}"
                            data-driver="{{ $app->driver ? trim($app->driver->first_name . ' ' . $app->driver->middle_initial . ' ' . $app->driver->last_name) : '-' }}"
                            data-route="{{ $app->route->name ?? 'N/A' }}"
                            data-unit-type="{{ $app->motorDetail->unit_type ?? '' }}"
                            data-unit-make="{{ $app->motorDetail && $app->motorDetail->unitMake ? $app->motorDetail->unitMake->name : '-' }}"
                            data-motor-no="{{ $app->motorDetail ? $app->motorDetail->motorno : '-' }}"
                            data-chasis-no="{{ $app->motorDetail ? $app->motorDetail->chasisno : '-' }}"
                            data-plate-no="{{ $app->motorDetail ? $app->motorDetail->platenumber : '-' }}"
                            data-id="{{ $encryptedId }}">
                            View Details
                        </x-button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Renewed/Other Franchises Table --}}
        <div>
            <h4 class="text-md font-semibold mb-3 text-primary-navy">Renewed/Other Franchise Applications</h4>
            <div class="overflow-x-auto">
                <table id="renewedFranchiseTable" class="w-full table-auto row-border text-sm">
                    <thead class="bg-gray-50">
                        <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                            <th>Application #</th>
                            <th>Application Type</th>
                            <th>Status</th>
                            <th>Franchise No</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($renewedFranchises as $app)
                        @php
                            $encryptedId = encrypt($app->id);
                        @endphp
                        <tr class="border-t">
                            <td class="p-2">{{ $app->id }}</td>
                            <td class="p-2 capitalize">{{ $app->application_type }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($app->status == 'approved') bg-green-100 text-green-800
                                        @elseif($app->status == 'rejected') bg-red-100 text-red-800
                                        @elseif($app->status == 'expired') bg-red-100 text-red-800
                                        @elseif($app->status == 'under_review' || $app->status == 'submitted') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                    @if($app->status === 'under_review')
                                    Under review
                                    @else
                                    {{ ucfirst($app->status ?? 'pending') }}
                                    @endif
                                </span>
                            </td>
                            <td class="p-2">{{ $app->franchise_no ?? '-' }}</td>
                            <td class="p-2">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</td>
                            <td class="p-2">
                                <button size="sm" type="button" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900 open-franchise-details-modal" data-status="{{ $app->status ?? '' }}"
                                    data-application-type="{{ ucfirst($app->application_type ?? '') }}"
                                    data-franchise-no="{{ $app->franchise_no ?? '-' }}"
                                    data-submitted="{{ optional($app->submitted_at)->format('F d, Y') ?? '-' }}"
                                    data-expiry="{{ $app->franchise_end_date ? $app->franchise_end_date->format('F d, Y') : '' }}" data-operator="{{ $app->operator 
                            ? trim(
                                $app->operator->first_name . 
                                ' ' . 
                                ($app->operator->middle_initial ? $app->operator->middle_initial . ' ' : '') . 
                                $app->operator->last_name
                            ) 
                            : 'N/A' }}"
                                    data-driver="{{ $app->driver 
                            ? trim(
                                $app->driver->first_name . 
                                ' ' . 
                                ($app->driver->middle_initial ? $app->driver->middle_initial . ' ' : '') . 
                                $app->driver->last_name
                            ) 
                            : 'N/A' }}"
                                    data-route="{{ $app->route->name ?? 'N/A' }}"

                                    data-unit-type="{{ $app->motorDetail->unit_type ?? '' }}"
                                    data-unit-make="{{ $app->motorDetail->unitMake->name ?? 'N/A' }}"
                                    data-motor-no="{{ $app->motorDetail->motorno ?? '' }}"
                                    data-chasis-no="{{ $app->motorDetail->chasisno ?? '' }}"
                                    data-plate-no="{{ $app->motorDetail->platenumber ?? '' }}"
                                    data-id="{{ $encryptedId }}">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                       
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Franchise Details Modal -->
<div id="franchiseDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative border border-gray-100">
        <!-- Close button -->
        <button type="button" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 z-10 transition-colors duration-200 bg-white rounded-full p-2 shadow-md hover:shadow-lg" onclick="closeFranchiseDetailsModal()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="p-6 sm:p-8 lg:p-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-3 bg-primary-navy rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-primary-navy pr-12">Franchise Details</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-base sm:text-lg text-gray-800">
                <!-- Application Info -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Application Info</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Status:</span>
                            <span id="franchise-status" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Application Type:</span>
                            <span id="franchise-application-type" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Franchise No:</span>
                            <span id="franchise-no" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Submitted:</span>
                            <span id="franchise-submitted" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Expiry Date:</span>
                            <span id="franchise-expiry" class="text-sm sm:text-base font-medium"></span>
                        </li>
                    </ul>
                </div>

                <!-- Parties -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Parties</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Operator:</span>
                            <span id="franchise-operator" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Driver:</span>
                            <span id="franchise-driver" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Route:</span>
                            <span id="franchise-route" class="text-sm sm:text-base font-medium"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Motor Details -->
            <div class="mt-8">
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Motor Details</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Unit Type:</span>
                            <span id="franchise-unit-type" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Unit Make:</span>
                            <span id="franchise-unit-make" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Motor No:</span>
                            <span id="franchise-motor-no" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Chasis No:</span>
                            <span id="franchise-chasis-no" class="text-sm sm:text-base font-medium"></span>
                        </li>
                        <li class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-sm sm:text-base text-gray-600 min-w-[120px]">Plate No:</span>
                            <span id="franchise-plate-no" class="text-sm sm:text-base font-medium"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4" id="franchise-modal-actions">
            </div>
            {{-- Hidden form for renewal submission --}}
            <form id="renewalForm" method="POST" style="display: none;">
                @csrf
            </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#renewedFranchiseTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: 5,
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

                    $controls.insertBefore($('#renewedFranchiseTable').closest('.overflow-x-auto'));
                }
            });
        });

        // Flash messages
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: {!! json_encode(session('success')) !!},
            icon: 'success',
            confirmButtonColor: '#1D2761',
            confirmButtonText: 'OK'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: {!! json_encode(session('error')) !!},
            icon: 'error',
            confirmButtonColor: '#E63946',
            confirmButtonText: 'OK'
        });
        @endif

        @if($errors->any())
        Swal.fire({
            title: 'Error!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            icon: 'error',
            confirmButtonColor: '#E63946',
            confirmButtonText: 'OK'
        });
        @endif

        // Open modal and populate data
        document.addEventListener('click', function(e) {
            if (e.target.closest('.open-franchise-details-modal')) {
                const button = e.target.closest('.open-franchise-details-modal');

                document.getElementById('franchise-status').textContent = button.getAttribute('data-status') || '-';
                document.getElementById('franchise-application-type').textContent = button.getAttribute('data-application-type') || '-';
                document.getElementById('franchise-no').textContent = button.getAttribute('data-franchise-no') || '-';
                document.getElementById('franchise-submitted').textContent = button.getAttribute('data-submitted') || '-';
                document.getElementById('franchise-expiry').textContent = button.getAttribute('data-expiry') || '-';

                document.getElementById('franchise-operator').textContent = button.getAttribute('data-operator') || '-';
                document.getElementById('franchise-driver').textContent = button.getAttribute('data-driver') || '-';
                document.getElementById('franchise-route').textContent = button.getAttribute('data-route') || '-';

                document.getElementById('franchise-unit-type').textContent = button.getAttribute('data-unit-type') || '-';
                document.getElementById('franchise-unit-make').textContent = button.getAttribute('data-unit-make') || '-';
                document.getElementById('franchise-motor-no').textContent = button.getAttribute('data-motor-no') || '-';
                document.getElementById('franchise-chasis-no').textContent = button.getAttribute('data-chasis-no') || '-';
                document.getElementById('franchise-plate-no').textContent = button.getAttribute('data-plate-no') || '-';

                const status = (button.getAttribute('data-status') || '').toLowerCase();
                const encryptedId = button.getAttribute('data-id');
                const hasMotorDetail =
                    button.getAttribute('data-unit-type') ||
                    button.getAttribute('data-unit-make') ||
                    button.getAttribute('data-motor-no') ||
                    button.getAttribute('data-chasis-no') ||
                    button.getAttribute('data-plate-no');

                let actionsHtml = '';

                if (status === 'approved' && hasMotorDetail) {
                    actionsHtml += `
                        <button type="button"
                            class="inline-block bg-primary-navy text-lg font-semibold text-white px-6 py-3 rounded-lg hover:bg-primary-gold hover:text-primary-navy transition"
                            onclick="requestMotorChange('${encryptedId}')">
                            Request Motor Change
                        </button>`;
                }

                if (status === 'expired') {
                    actionsHtml += `
                        <button type="button"
                            class="inline-block bg-green-600 text-lg font-semibold text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
                            onclick="confirmRenewal('${encryptedId}')">
                            Renew Franchise
                        </button>`;
                }

                document.getElementById('franchise-modal-actions').innerHTML = actionsHtml;

                document.getElementById('renewalForm')
                    .setAttribute('action', `{{ url('operator/franchise') }}/${encryptedId}/renew`);

                document.getElementById('franchiseDetailsModal').classList.remove('hidden');
            }
        });

        function closeFranchiseDetailsModal() {
            document.getElementById('franchiseDetailsModal').classList.add('hidden');
        }

        // Request Motor Change function
        function requestMotorChange(encryptedId) {
            Swal.fire({
                title: 'Request Motor Change?',
                html: `
                <div class="text-left">
                    <p class="mb-3">Are you sure you want to request a motor change for this franchise?</p>
                    <div class="bg-blue-50 p-3 rounded mb-3">
                        <h4 class="font-semibold text-blue-800 mb-2">Physical Evaluation Requirements:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Valid Driver's License</li>
                            <li>• Vehicle Registration (OR/CR)</li>
                            <li>• Insurance Certificate</li>
                            <li>• LTO Certificate of Registration</li>
                            <li>• Valid Franchise Permit</li>
                            <li>• Vehicle Inspection Report</li>
                            <li>• Tax Clearance</li>
                            <li>• Barangay Clearance</li>
                        </ul>
                    </div>
                    <p class="text-sm text-gray-600">Please bring all required documents for physical evaluation.</p>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1D2761',
                cancelButtonColor: '#E63946',
                confirmButtonText: 'Yes, Submit Request',
                cancelButtonText: 'Cancel',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Submitting Request...',
                        text: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    let url = "{{ route('operator.franchise.motor-change.create', ':id') }}";
                    url = url.replace(':id', encryptedId);

                    window.location.href = url;
                }
            });
        }

        // Renew Franchise function
        function confirmRenewal(franchiseId) {
            Swal.fire({
                title: 'Renew Franchise?',
                text: 'Are you sure you want to renew this franchise?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1D2761',
                cancelButtonColor: '#E63946',
                confirmButtonText: 'Yes, Renew',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('renewalForm')
                        .setAttribute('action', `{{ url('operator/franchise') }}/${franchiseId}/renew`);
                    document.getElementById('renewalForm').submit();
                }
            });
        }

    </script>
@endpush