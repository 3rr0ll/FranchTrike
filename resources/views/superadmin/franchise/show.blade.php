@extends('layouts.superadmin')

@section('title', 'Franchise Details')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Franchise Application Details
</h2>
@endsection

@section('content')


<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">
    <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="flex items-center mb-4 sm:mb-0">
            <a href="{{ route('superadmin.franchise.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Applications
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Application Status -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Application # {{ $franchiseApplication->id }}</h2>
                        <p class="text-sm text-gray-500">
                            Submitted on 
                            {{ $franchiseApplication->created_at ? $franchiseApplication->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}
                        </p>
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


<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">
    <div class="overflow-x-auto rounded-lg shadow bg-white p-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Change History</h3>
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Before</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status After</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated By</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($franchiseApplication->logs as $log)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                            @if($log->status_before == 'approved') bg-green-100 text-green-800
                            @elseif($log->status_before == 'rejected') bg-red-100 text-red-800
                            @elseif($log->status_before == 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($log->status_before == 'renewed') bg-blue-100 text-blue-800
                            @elseif($log->status_before == 'expired') bg-gray-300 text-gray-700
                            @elseif($log->status_before == 'submitted') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $log->status_before ? ucfirst(str_replace('_', ' ', $log->status_before)) : '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                            @if($log->status_after == 'approved') bg-green-100 text-green-800
                            @elseif($log->status_after == 'rejected') bg-red-100 text-red-800
                            @elseif($log->status_after == 'under_review') bg-yellow-100 text-yellow-800
                            @elseif($log->status_after == 'renewed') bg-blue-100 text-blue-800
                            @elseif($log->status_after == 'expired') bg-gray-300 text-gray-700
                            @elseif($log->status_after == 'submitted') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $log->status_after)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->updatedBy->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No status changes recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</script>
@endsection