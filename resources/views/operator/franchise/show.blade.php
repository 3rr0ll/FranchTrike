@extends('layouts.operator')

@section('header')
<h2 class="text-xl font-semibold text-gray-800">Franchise #{{ $franchiseApplication->id }} Details</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto mt-6">
    <div class="bg-white shadow p-6 rounded-lg space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-bold mb-2">Application Info</h3>
                <ul class="text-sm space-y-1">
                    <li><span class="text-gray-600">Status:</span> {{ ucfirst($franchiseApplication->status) }}</li>
                    <li><span class="text-gray-600">Application Type:</span> {{ ucfirst($franchiseApplication->application_type) }}</li>
                    <li><span class="text-gray-600">Franchise No:</span> {{ $franchiseApplication->franchise_no ?? '-' }}</li>
                    <li><span class="text-gray-600">Submitted:</span> {{ optional($franchiseApplication->submitted_at)->format('M d, Y') ?? '-' }}</li>
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

        <div class="flex items-center gap-2">
            <a href="{{ route('operator.franchise.index') }}" class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded hover:bg-gray-200">Back</a>
            @if($franchiseApplication->status === 'approved' && $franchiseApplication->motorDetail)
            <a href="{{ route('operator.franchise.motor-change.create', $franchiseApplication->id) }}" class="inline-block bg-primary-navy text-white px-4 py-2 rounded hover:bg-primary-gold hover:text-primary-navy">Request Motor Change</a>
            @endif
        </div>
    </div>
</div>
@endsection
