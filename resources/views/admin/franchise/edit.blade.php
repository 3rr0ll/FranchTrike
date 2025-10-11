@extends('layouts.admin')

@section('title', 'Edit Franchise Application')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Edit Franchise Application
</h2>
@endsection

@section('content')
<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">

            <form action="{{ route('admin.franchise.update', $franchiseApplication->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Operator Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Operator Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input name="operator_name" value="{{ old('operator_name', $franchiseApplication->operator->full_name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input name="operator_contact_no" value="{{ old('operator_contact_no', $franchiseApplication->operator->contact_no ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <input name="operator_address" value="{{ old('operator_address', $franchiseApplication->operator->full_address ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                        </div>
                    </div>


                    <!-- Driver Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Driver Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input name="driver_name" value="{{ old('driver_name', $franchiseApplication->driver->full_name ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">License Number</label>
                                <input name="driver_license_no" value="{{ old('driver_license_no', $franchiseApplication->driver->license_no ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input name="driver_contact_no" value="{{ old('driver_contact_no', $franchiseApplication->driver->contact_no ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <input name="driver_address" value="{{ old('driver_address', $franchiseApplication->driver->full_address ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Application Details -->
                <div class="mt-6 bg-gray-50 rounded-lg p-6 border">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Application Type</label>
                            <select name="application_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                                <option value="new" {{ old('application_type', $franchiseApplication->application_type) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="renewal" {{ old('application_type', $franchiseApplication->application_type) == 'renewal' ? 'selected' : '' }}>Renewal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Route</label>
                            <select name="route_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id', $franchiseApplication->route_id) == $route->id ? 'selected' : '' }}>
                                    {{ $route->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CTC Number</label>
                            <input name="ctc_no" value="{{ old('ctc_no', $franchiseApplication->ctc_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Operator Name (as shown in docs)</label>
                            <input name="operator_name_document" value="{{ old('operator_name_document', $franchiseApplication->operator_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required />
                        </div>
                        @if($franchiseApplication->status == 'approved')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise Number</label>
                            <input name="franchise_no" value="{{ old('franchise_no', $franchiseApplication->franchise_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sticker Number</label>
                            <input name="sticker_no" value="{{ old('sticker_no', $franchiseApplication->sticker_no) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise Start Date</label>
                            <input 
                                type="date" 
                                name="franchise_start_date"
                                value="{{ old('franchise_start_date', $franchiseApplication->franchise_start_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_start_date)->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" 
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Franchise End Date</label>
                            <input 
                                type="date" 
                                name="franchise_end_date"
                                value="{{ old('franchise_end_date', $franchiseApplication->franchise_end_date ? \Carbon\Carbon::parse($franchiseApplication->franchise_end_date)->format('Y-m-d') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" 
                            />
                        </div>
                        @endif
                        @if($franchiseApplication->status == 'rejected')
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                            <textarea name="rejection_reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" rows="2">{{ old('rejection_reason', $franchiseApplication->rejection_reason) }}</textarea>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Motor Details -->
                @if($franchiseApplication->motorDetail)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Motor Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                            <input value="{{ ucfirst($franchiseApplication->motorDetail->unit_type) }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Make</label>
                            <input value="{{ $franchiseApplication->motorDetail->unitMake->name ?? 'N/A' }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                            <input value="{{ $franchiseApplication->motorDetail->platenumber }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Motor No</label>
                            <input value="{{ $franchiseApplication->motorDetail->motorno }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chassis No</label>
                            <input value="{{ $franchiseApplication->motorDetail->chasisno }}" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" />
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-end mt-8">
                    <a href="{{ route('admin.franchise.show', $franchiseApplication->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-primary-navy hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition ease-in-out duration-150">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
