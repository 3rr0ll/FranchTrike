@extends('layouts.operator')

@section('title', 'Motor Details')

@section('content')
<div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
    <form method="POST" action="{{ route('operator.franchise.store-motor-details', $franchiseApplication->id) }}">
        @csrf
        {{-- Motor Details --}}
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Motor Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                    <select name="unit_type" class="mt-1 block w-full border rounded p-2" required>
                        <option value="">Select unit type</option>
                        <option value="tricycle" {{ old('unit_type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
                        <option value="motocab" {{ old('unit_type') == 'motocab' ? 'selected' : '' }}>Motocab</option>
                    </select>
                    @error('unit_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Unit Make</label>
                    <select name="unit_make_id" class="mt-1 block w-full border rounded p-2" required>
                        <option value="">Select unit make</option>
                        @foreach ($unitMakes as $unitMake)
                            <option value="{{ $unitMake->id }}" {{ old('unit_make_id') == $unitMake->id ? 'selected' : '' }}>
                                {{ $unitMake->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_make_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Motor Number</label>
                    <input type="text" name="motorno" class="mt-1 block w-full border rounded p-2" placeholder="Enter motor number" required>
                    @error('motorno')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Chassis Number</label>
                    <input type="text" name="chasisno" class="mt-1 block w-full border rounded p-2" placeholder="Enter chassis number" required>
                    @error('chasisno')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                    <input type="text" name="platenumber" class="mt-1 block w-full border rounded p-2" placeholder="Enter plate number" required>
                    @error('platenumber')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <x-button type="submit">
                Submit Motor Details
            </x-button>
        </div>
    </form>
</div>
@endsection