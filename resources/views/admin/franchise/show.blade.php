@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
Franchise Application Details
</h2>
@endsection

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5">
    <div class="w-full mb-1">

        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100">
            <div class="flex items-center mb-4 sm:mb-0">
                <a href="{{ route('admin.franchise.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Application Status -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Application #{{ $franchiseApplication->application_number }}</h2>
                        <p class="text-sm text-gray-500">Submitted on {{ $franchiseApplication->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            @if($franchiseApplication->status == 'approved') bg-green-100 text-green-800
                            @elseif($franchiseApplication->status == 'rejected') bg-red-100 text-red-800
                            @elseif($franchiseApplication->status == 'under_review') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $franchiseApplication->status)) }}
                        </span>
                        <button onclick="openStatusModal()" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>

            <!-- Application Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Operator Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Operator Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->operator->full_name ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->operator->contact_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->operator->full_address ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Driver Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Driver Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->driver->full_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">License Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->driver->license_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->driver->contact_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->driver->full_address ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Application Details -->
            <div class="mt-6 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Application Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($franchiseApplication->application_type ?? 'N/A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Route</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->route->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CTC Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->ctc_no ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Operator Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->operator_name ?? 'N/A' }}</dd>
                    </div>
                    @if($franchiseApplication->status == 'approved')
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Franchise Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $franchiseApplication->franchise_no ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sticker Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->sticker_no ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Franchise Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->franchise_start_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_start_date)->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Franchise End Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->franchise_end_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_end_date)->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Franchise Fee</dt>
                        <dd class="mt-1 text-sm text-gray-900">₱{{ number_format($franchiseApplication->franchise_fee ?? 0, 2) }}</dd>
                    </div>
                    @endif
                    @if($franchiseApplication->status == 'rejected')
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $franchiseApplication->rejection_reason ?? 'N/A' }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Certificate Generation Section -->
            @if($franchiseApplication->status == 'approved' && $franchiseApplication->motorDetail)
            <div class="mt-6 bg-gray-50 rounded-lg p-6">    
                <h3 class="text-lg font-medium text-gray-900 mb-4">Certificate Generation</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- MTOP Certificate -->
                    <div class="bg-white rounded-lg p-4 shadow-sm border">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">MTOP Certificate</h4>
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="previewMTOP({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                            <button onclick="generateMTOP({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </div>

                    <!-- Mayor's Permit -->
                    <div class="bg-white rounded-lg p-4 shadow-sm border">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">Mayor's Permit</h4>
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="previewMayorsPermit({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                            <button onclick="generateMayorsPermit({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </div>

                    <!-- Application Certificate -->
                    <div class="bg-white rounded-lg p-4 shadow-sm border">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">Application</h4>
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="previewApplication({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview
                            </button>
                            <button onclick="generateApplication({{ $franchiseApplication->motorDetail->id }})" class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 mt-4">
                    <!-- Generate All Certificates -->
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-primary-navy">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900">All Certificates</h4>
                            <div class="w-8 h-8 bg-primary-navy rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="generateAllCertificates({{ $franchiseApplication->motorDetail->id }})" class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generate All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Motor Details -->
            @if($franchiseApplication->motorDetail)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Motor Details</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Make</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chasis No</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($franchiseApplication->motorDetail->unit_type == 'motocab') bg-blue-100 text-blue-800
                                        @elseif($franchiseApplication->motorDetail->unit_type == 'tricycle') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($franchiseApplication->motorDetail->unit_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $franchiseApplication->motorDetail->unitMake->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $franchiseApplication->motorDetail->platenumber }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $franchiseApplication->motorDetail->motorno }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $franchiseApplication->motorDetail->chasisno }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Application Status</h3>
            <form action="{{ route('admin.franchise.update-status', $franchiseApplication) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                            <option value="">Select Status</option>
                            <option value="under_review" {{ $franchiseApplication->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved" {{ $franchiseApplication->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $franchiseApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div id="rejection-reason" class="hidden">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                        <textarea name="rejection_reason" id="rejection_reason_input" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" placeholder="Enter rejection reason..."></textarea>
                    </div>

                    <div id="franchise-details" class="hidden space-y-4">
                        <div>
                            <label for="franchise_no" class="block text-sm font-medium text-gray-700">Franchise Number</label>
                            <input type="text" name="franchise_no" id="franchise_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        </div>
                        <div>
                            <label for="sticker_no" class="block text-sm font-medium text-gray-700">Sticker Number</label>
                            <input type="text" name="sticker_no" id="sticker_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        </div>
                        <div>
                            <label for="franchise_start_date" class="block text-sm font-medium text-gray-700">Franchise Start Date</label>
                            <input type="date" name="franchise_start_date" id="franchise_start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        </div>
                        <div>
                            <label for="franchise_end_date" class="block text-sm font-medium text-gray-700">Franchise End Date</label>
                            <input type="date" name="franchise_end_date" id="franchise_end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        </div>
                        <div>
                            <label for="franchise_fee" class="block text-sm font-medium text-gray-700">Franchise Fee</label>
                            <input type="number" name="franchise_fee" id="franchise_fee" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeStatusModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStatusModal() {
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    // Show/hide fields based on status selection
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const rejectionReason = document.getElementById('rejection-reason');
        const franchiseDetails = document.getElementById('franchise-details');
        const rejectionReasonInput = document.getElementById('rejection_reason_input');

        // Hide all conditional fields
        rejectionReason.classList.add('hidden');
        franchiseDetails.classList.add('hidden');

        // Show relevant fields based on status
        if (status === 'rejected') {
            rejectionReason.classList.remove('hidden');
            rejectionReasonInput.required = true;
        } else {
            rejectionReasonInput.required = false;
        }

        if (status === 'approved') {
            franchiseDetails.classList.remove('hidden');
        }
    });

    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });

    // Certificate generation functions
    function previewMTOP(motorDetailId) {
        window.open(`http://localhost/Franchise/franchtrike/public/admin/certificates/mtop/${motorDetailId}/preview`, '_blank');
    }

    function generateMTOP(motorDetailId) {
        window.location.href = `http://localhost/Franchise/franchtrike/public/admin/certificates/mtop/${motorDetailId}/generate`;
    }

    function previewMayorsPermit(motorDetailId) {
        window.open(`http://localhost/Franchise/franchtrike/public/admin/certificates/mayors-permit/${motorDetailId}/preview`, '_blank');
    }

    function generateMayorsPermit(motorDetailId) {
        window.location.href = `http://localhost/Franchise/franchtrike/public/admin/certificates/mayors-permit/${motorDetailId}/generate`;
    }

    function previewApplication(motorDetailId) {
        window.open(`http://localhost/Franchise/franchtrike/public/admin/certificates/application/${motorDetailId}/preview`, '_blank');
    }

    function generateApplication(motorDetailId) {
        window.location.href = `http://localhost/Franchise/franchtrike/public/admin/certificates/application/${motorDetailId}/generate`;
    }

    function generateAllCertificates(motorDetailId) {
        window.location.href = `http://localhost/Franchise/franchtrike/public/admin/certificates/all/${motorDetailId}/generate`;
    }
</script>
@endsection