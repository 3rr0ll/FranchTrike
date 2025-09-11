@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-4xl text-primary-navy flex items-center gap-2 mb-2">
    Franchise #{{ $franchiseApplication->franchise_no ?? '-' }} Details
</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    <div class="bg-white shadow-lg p-10 rounded-2xl space-y-10">
        <a href="{{ route('operator.franchise.index') }}" class="inline-block bg-gray-100 text-lg text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-200 font-semibold mb-4">Back</a>
        
        {{-- Motor Change Request Status --}}
        @php
        $motorChangeRequest = \App\Models\MotorChangeRequest::where('franchise_application_id', $franchiseApplication->id)->latest()->first();
        @endphp
        @if($motorChangeRequest)
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-4">
            <span class="font-semibold text-blue-900 text-lg">Motor Change Request Status:</span>
            <span class="ml-2 text-blue-800 text-lg">
                {{ ucfirst($motorChangeRequest->status ?? 'pending') }}
            </span>
            @if($motorChangeRequest->created_at)
            <span class="ml-4 text-base text-gray-500">(Requested: {{ $motorChangeRequest->created_at->format('M d, Y') }})</span>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4 text-primary-navy">Application Info</h3>
                <ul class="text-lg space-y-3">
                    <li>
                        <span class="text-gray-700 font-semibold">Status:</span> 
                        <span class="px-3 py-1 text-base font-semibold rounded-full
                            @if($franchiseApplication->status == 'approved') bg-green-100 text-green-800
                            @elseif($franchiseApplication->status == 'rejected') bg-red-100 text-red-800
                            @elseif($franchiseApplication->status == 'expired') bg-red-100 text-red-800
                            @elseif($franchiseApplication->status == 'under_review' || $franchiseApplication->status == 'submitted') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($franchiseApplication->status === 'under_review')
                                Under review
                            @else
                                {{ ucfirst($franchiseApplication->status) }}
                            @endif
                        </span>
                    </li>
                    <li>
                        <span class="text-gray-700 font-semibold">Application Type:</span>
                        <span class="text-gray-900">{{ ucfirst($franchiseApplication->application_type) }}</span>
                    </li>
                    <li>
                        <span class="text-gray-700 font-semibold">Franchise No:</span>
                        <span class="text-gray-900">{{ $franchiseApplication->franchise_no ?? '-' }}</span>
                    </li>
                    <li>
                        <span class="text-gray-700 font-semibold">Submitted:</span>
                        <span class="text-gray-900">{{ optional($franchiseApplication->submitted_at)->format('M d, Y') ?? '-' }}</span>
                    </li>
                    @if($franchiseApplication->franchise_end_date)
                    <li>
                        <span class="text-gray-700 font-semibold">Expiry Date:</span>
                        <span class="text-gray-900">{{ $franchiseApplication->franchise_end_date->format('M d, Y') }}</span>
                    </li>
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4 text-primary-navy">Parties</h3>
                <ul class="text-lg space-y-3">
                    <li>
                        <span class="text-gray-700 font-semibold">Operator:</span> 
                        <span class="text-gray-900">
                        {{ 
                            $franchiseApplication->operator 
                                ? trim(
                                    $franchiseApplication->operator->first_name . 
                                    ' ' . 
                                    ($franchiseApplication->operator->middle_initial ? $franchiseApplication->operator->middle_initial . ' ' : '') . 
                                    $franchiseApplication->operator->last_name
                                )
                                : 'N/A' 
                        }}
                        </span>
                    </li>
                    <li>
                        <span class="text-gray-700 font-semibold">Driver:</span> 
                        <span class="text-gray-900">
                        {{ 
                            $franchiseApplication->driver 
                                ? trim(
                                    $franchiseApplication->driver->first_name . 
                                    ' ' . 
                                    ($franchiseApplication->driver->middle_initial ? $franchiseApplication->driver->middle_initial . ' ' : '') . 
                                    $franchiseApplication->driver->last_name
                                )
                                : 'N/A' 
                        }}
                        </span>
                    </li>
                    <li>
                        <span class="text-gray-700 font-semibold">Route:</span>
                        <span class="text-gray-900">{{ $franchiseApplication->route->name ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-bold mb-4 text-primary-navy">Motor Details</h3>
            @if($franchiseApplication->motorDetail)
            <ul class="text-lg space-y-3">
                <li>
                    <span class="text-gray-700 font-semibold">Unit Type:</span>
                    <span class="text-gray-900">{{ ucfirst($franchiseApplication->motorDetail->unit_type) }}</span>
                </li>
                <li>
                    <span class="text-gray-700 font-semibold">Unit Make:</span>
                    <span class="text-gray-900">{{ $franchiseApplication->motorDetail->unitMake->name ?? 'N/A' }}</span>
                </li>
                <li>
                    <span class="text-gray-700 font-semibold">Motor No:</span>
                    <span class="text-gray-900">{{ $franchiseApplication->motorDetail->motorno }}</span>
                </li>
                <li>
                    <span class="text-gray-700 font-semibold">Chasis No:</span>
                    <span class="text-gray-900">{{ $franchiseApplication->motorDetail->chasisno }}</span>
                </li>
                <li>
                    <span class="text-gray-700 font-semibold">Plate No:</span>
                    <span class="text-gray-900">{{ $franchiseApplication->motorDetail->platenumber }}</span>
                </li>
            </ul>
            @else
            <p class="text-lg text-gray-500">No motor details recorded.</p>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
            @if($franchiseApplication->status === 'approved' && $franchiseApplication->motorDetail)
            <a href="{{ route('operator.franchise.motor-change.create', $franchiseApplication->id) }}" class="inline-block bg-primary-navy text-lg font-semibold text-white px-6 py-3 rounded-lg hover:bg-primary-gold hover:text-primary-navy transition">Request Motor Change</a>
            @endif
            
            @if($franchiseApplication->status === 'expired')
            <button onclick="confirmRenewal()" class="inline-block bg-green-600 text-lg font-semibold text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                Renew Franchise
            </button>
            @endif
        </div>
    </div>
</div>

{{-- Hidden form for renewal submission --}}
<form id="renewalForm" method="POST" action="{{ route('operator.franchise.renew', $franchiseApplication->id) }}" style="display: none;">
    @csrf
</form>

{{-- Renewal Confirmation Script --}}
<script>
function confirmRenewal() {
    Swal.fire({
        title: 'Confirm Franchise Renewal',
        text: 'Are you sure you want to renew this franchise?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Renew Franchise',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('renewalForm');

            // Show loading
            Swal.fire({
                title: 'Processing Renewal...',
                text: 'Please wait while we create your renewal application.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: `{{ session('success') }}`,
            confirmButtonColor: '#10B981'
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `{{ session('error') }}`,
            confirmButtonColor: '#EF4444'
        });
    @endif
});
</script>
@endsection