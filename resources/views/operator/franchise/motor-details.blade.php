<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add Motor Details</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        @if (session('error'))
        <div class="mb-4 text-red-600 font-semibold">{{ session('error') }}</div>
        @endif
        @if (session('success'))
        <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
        @endif

        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Franchise Application Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Application ID:</span> {{ $franchiseApplication->id }}
                </div>
                <div>
                    <span class="font-medium">Operator:</span> {{ $franchiseApplication->operator_name }}
                </div>
              
                <div>
                    <span class="font-medium">Route:</span> {{ $franchiseApplication->route->name }}
                </div>
            </div>
        </div>

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

            <div class="flex justify-between items-center">
                <div class="flex gap-4">
                    <a href="{{ route('operator.franchise.index') }}" class="text-gray-600 hover:text-gray-800">
                        ← Back to Applications
                    </a>
                    <a href="{{ route('operator.franchise.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create New Franchise
                    </a>
                </div>
                <x-button type="submit" class="justify-center">
                    Submit Motor Details
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout> 