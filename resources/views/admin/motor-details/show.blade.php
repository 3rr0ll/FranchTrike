@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
Motor Details
</h2>
@endsection

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5">
    <div class="w-full mb-1">
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100">
            <div class="flex items-center mb-4 sm:mb-0">
                <a href="{{ route('admin.motor-details.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Motor Details
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Motor Details Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Motor Details -->
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
                <a href="{{ route('admin.motor-details.edit', $motorDetail) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Motor Details
                </a>

                @if($motorDetail->franchiseApplication)
                <a href="{{ route('admin.franchise.show', $motorDetail->franchiseApplication) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Application
                </a>
                @endif

                <div class="flex space-x-2">
                    <button onclick="previewMTOP({{ $motorDetail->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview MTOP
                    </button>
                    <button onclick="generateMTOP({{ $motorDetail->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </button>
                </div>
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

    function previewMTOP(motorDetailId) {
        // Open preview in a new window
        window.open(`{{ url('/admin/certificates/mtop') }}/${motorDetailId}/preview`, '_blank');
    }

    function generateMTOP(motorDetailId) {
        // Download PDF directly
        window.location.href = `{{ url('/admin/certificates/mtop') }}/${motorDetailId}/generate`;
    }
</script>
@endsection