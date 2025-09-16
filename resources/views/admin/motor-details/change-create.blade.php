@extends('layouts.admin')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        Create Motor Change Request for Client
    </h2>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <form action="{{ url('admin/motor-change/store-for-client') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Franchise Application --}}
            <div>
                <label for="franchise_application_id" class="block font-medium mb-2">Select Franchise Application <span class="text-red-500">*</span></label>
                <select name="franchise_application_id" id="franchise_application_id" class="w-full border border-gray-300 rounded-lg p-2.5" required>
                    <option value="">-- Select Application --</option>
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}" {{ old('franchise_application_id') == $application->id ? 'selected' : '' }}>
                            #{{ $application->id }} - {{ $application->operator->last_name ?? 'N/A' }}, {{ $application->operator->first_name ?? '' }} ({{ $application->motorDetail->platenumber ?? 'No Plate' }})
                        </option>
                    @endforeach
                </select>
                @error('franchise_application_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>          

            {{-- New Unit Type --}}
            <div>
                <label for="new_unit_type" class="block font-medium mb-2">New Unit Type <span class="text-red-500">*</span></label>
                <select name="new_unit_type" id="new_unit_type" class="w-full border border-gray-300 rounded-lg p-2.5" required>
                    <option value="">-- Select Type --</option>
                    <option value="motocab" {{ old('new_unit_type') == 'motocab' ? 'selected' : '' }}>Motocab</option>
                    <option value="tricycle" {{ old('new_unit_type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
                </select>
                @error('new_unit_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Unit Make --}}
            <div>
                <label for="new_unit_make_id" class="block font-medium mb-2">New Unit Make <span class="text-red-500">*</span></label>
                <select name="new_unit_make_id" id="new_unit_make_id" class="w-full border border-gray-300 rounded-lg p-2.5" required>
                    <option value="">-- Select Make --</option>
                    @foreach($unitMakes as $make)
                        <option value="{{ $make->id }}" {{ old('new_unit_make_id') == $make->id ? 'selected' : '' }}>
                            {{ $make->name }}
                        </option>
                    @endforeach
                </select>
                @error('new_unit_make_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Motor Number --}}
            <div>
                <label for="new_motorno" class="block font-medium mb-2">New Motor Number <span class="text-red-500">*</span></label>
                <input type="text" name="new_motorno" id="new_motorno" class="w-full border border-gray-300 rounded-lg p-2.5" value="{{ old('new_motorno') }}" required>
                @error('new_motorno')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Chasis Number --}}
            <div>
                <label for="new_chasisno" class="block font-medium mb-2">New Chasis Number <span class="text-red-500">*</span></label>
                <input type="text" name="new_chasisno" id="new_chasisno" class="w-full border border-gray-300 rounded-lg p-2.5" value="{{ old('new_chasisno') }}" required>
                @error('new_chasisno')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Plate Number --}}
            <div>
                <label for="new_platenumber" class="block font-medium mb-2">New Plate Number <span class="text-red-500">*</span></label>
                <input type="text" name="new_platenumber" id="new_platenumber" class="w-full border border-gray-300 rounded-lg p-2.5" value="{{ old('new_platenumber') }}" required>
                @error('new_platenumber')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary-navy text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-900 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
@endsection
