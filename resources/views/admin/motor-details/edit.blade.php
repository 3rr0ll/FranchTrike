@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Edit Motor Details</h2>

@endsection


@section('content')


<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 mb-4">
        <div class="flex items-center mb-4 sm:mb-0">
            <a href="{{ route('admin.motor-details.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Motor Details
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.motor-details.update', $motorDetail) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Unit Type -->
                    <div>
                        <label for="unit_type" class="block text-sm font-medium text-gray-700">Unit Type</label>
                        <select name="unit_type" id="unit_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                            <option value="">Select Unit Type</option>
                            @foreach($unitTypes as $type)
                            <option value="{{ $type }}" {{ $motorDetail->unit_type == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                            @endforeach
                        </select>
                        @error('unit_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Make -->
                    <div>
                        <label for="unit_make_id" class="block text-sm font-medium text-gray-700">Unit Make</label>
                        <select name="unit_make_id" id="unit_make_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                            <option value="">Select Unit Make</option>
                            @foreach($unitMakes as $make)
                            <option value="{{ $make->id }}" {{ $motorDetail->unit_make_id == $make->id ? 'selected' : '' }}>
                                {{ $make->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('unit_make_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motor Number -->
                    <div>
                        <label for="motorno" class="block text-sm font-medium text-gray-700">Motor Number</label>
                        <input type="text" name="motorno" id="motorno" value="{{ $motorDetail->motorno }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        @error('motorno')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chasis Number -->
                    <div>
                        <label for="chasisno" class="block text-sm font-medium text-gray-700">Chasis Number</label>
                        <input type="text" name="chasisno" id="chasisno" value="{{ $motorDetail->chasisno }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        @error('chasisno')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plate Number -->
                    <div>
                        <label for="platenumber" class="block text-sm font-medium text-gray-700">Plate Number</label>
                        <input type="text" name="platenumber" id="platenumber" value="{{ $motorDetail->platenumber }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                        @error('platenumber')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Application Information (Read-only) -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Application Information</h3>
                        <dl class="grid grid-cols-1 gap-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Application Number:</dt>
                                <dd class="text-gray-900 font-medium">{{ $motorDetail->franchiseApplication->application_number ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Operator:</dt>
                                <dd class="text-gray-900">{{ $motorDetail->franchiseApplication->operator->last_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Driver:</dt>
                                <dd class="text-gray-900">{{ $motorDetail->franchiseApplication->driver->last_name ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.motor-details.index')}}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Update Motor Details
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection