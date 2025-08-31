@extends('layouts.operator')

@section('header')
<h2 class="text-xl font-semibold text-gray-800">Franchise #{{ $franchiseApplication->id }} Details</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <div class="bg-white shadow p-6 rounded-lg space-y-6">
        <a href="{{ route('operator.franchise.index') }}" class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded hover:bg-gray-200">Back</a>
        
        {{-- Motor Change Request Status --}}
        @php
        $motorChangeRequest = \App\Models\MotorChangeRequest::where('franchise_application_id', $franchiseApplication->id)->latest()->first();
        @endphp
        @if($motorChangeRequest)
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
            <span class="font-semibold text-blue-900">Motor Change Request Status:</span>
            <span class="ml-2 text-blue-800">
                {{ ucfirst($motorChangeRequest->status ?? 'pending') }}
            </span>
            @if($motorChangeRequest->created_at)
            <span class="ml-4 text-xs text-gray-500">(Requested: {{ $motorChangeRequest->created_at->format('M d, Y') }})</span>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-bold mb-2">Application Info</h3>
                <ul class="text-sm space-y-1">
                    <li>
                        <span class="text-gray-600">Status:</span> 
                        <span class="px-2 py-1 text-xs font-medium rounded-full
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
                    <li><span class="text-gray-600">Application Type:</span> {{ ucfirst($franchiseApplication->application_type) }}</li>
                    <li><span class="text-gray-600">Franchise No:</span> {{ $franchiseApplication->franchise_no ?? '-' }}</li>
                    <li><span class="text-gray-600">Submitted:</span> {{ optional($franchiseApplication->submitted_at)->format('M d, Y') ?? '-' }}</li>
                    @if($franchiseApplication->franchise_end_date)
                    <li><span class="text-gray-600">Expiry Date:</span> {{ $franchiseApplication->franchise_end_date->format('M d, Y') }}</li>
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-2">Parties</h3>
                <ul class="text-sm space-y-1">
                    <li><span class="text-gray-600">Operator:</span> {{ $franchiseApplication->operator->last_name ?? 'N/A' }}</li>
                    <li><span class="text-gray-600">Driver:</span> {{ $franchiseApplication->driver->last_name ?? 'N/A' }}</li>
                    <li><span class="text-gray-600">Route:</span> {{ $franchiseApplication->route->name ?? 'N/A' }}</li>
                </ul>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-2">Motor Details</h3>
            @if($franchiseApplication->motorDetail)
            <ul class="text-sm space-y-1">
                <li><span class="text-gray-600">Unit Type:</span> {{ ucfirst($franchiseApplication->motorDetail->unit_type) }}</li>
                <li><span class="text-gray-600">Unit Make:</span> {{ $franchiseApplication->motorDetail->unitMake->name ?? 'N/A' }}</li>
                <li><span class="text-gray-600">Motor No:</span> {{ $franchiseApplication->motorDetail->motorno }}</li>
                <li><span class="text-gray-600">Chasis No:</span> {{ $franchiseApplication->motorDetail->chasisno }}</li>
                <li><span class="text-gray-600">Plate No:</span> {{ $franchiseApplication->motorDetail->platenumber }}</li>
            </ul>
            @else
            <p class="text-gray-500">No motor details recorded.</p>
            @endif

        </div>

        <div class="flex items-center justify-end gap-2">
            @if($franchiseApplication->status === 'approved' && $franchiseApplication->motorDetail)
            <a href="{{ route('operator.franchise.motor-change.create', $franchiseApplication->id) }}" class="inline-block bg-primary-navy text-white px-4 py-2 rounded hover:bg-primary-gold hover:text-primary-navy">Request Motor Change</a>
            @endif
            
            @if($franchiseApplication->status === 'expired')
            <button onclick="confirmRenewal()" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
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
            
            // Add error handling
            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted');
            });
            
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
</script>
@endsection