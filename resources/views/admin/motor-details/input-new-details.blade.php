@extends('layouts.admin')

@section('title', 'Input Details')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Input New Motor Details
</h2>
@endsection

@section('content')
<div class="w-full mt-4">
    <a href="{{ route('admin.motor-change.index') }}" class="inline-block mb-4 bg-primary-navy hover:bg-primary-navy/90 text-white px-4 py-2 rounded">
         Back to Motor Change Requests
    </a>
    <div class="bg-white shadow-sm rounded-lg p-6">


        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Franchise Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Franchise Number</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $motorChange->franchiseApplication->franchise_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Application Number</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $motorChange->franchiseApplication->id ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Motor Details</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $motorChange->old_unit_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Make</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motorChange->oldUnitMake->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Motor Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motorChange->old_motorno }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Chassis Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motorChange->old_chasisno }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $motorChange->old_platenumber }}</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.motor-change.input-details', $motorChange->id) }}" method="POST" id="new-details-form">
            @csrf
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">New Motor Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_unit_type" class="block text-sm font-medium text-gray-700">Unit Type *</label>
                        <select name="new_unit_type" id="new_unit_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                            <option value="">-- Select Unit Type --</option>
                            <option value="tricycle" {{ old('new_unit_type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
                            <option value="motocab" {{ old('new_unit_type') == 'motocab' ? 'selected' : '' }}>Motocab</option>
                        </select>
                        @error('new_unit_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_unit_make_id" class="block text-sm font-medium text-gray-700">Unit Make *</label>
                        <select name="new_unit_make_id" id="new_unit_make_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                            <option value="">-- Select Unit Make --</option>
                            @foreach($unitMakes as $make)
                            <option value="{{ $make->id }}" {{ old('new_unit_make_id') == $make->id ? 'selected' : '' }}>{{ $make->name }}</option>
                            @endforeach
                        </select>
                        @error('new_unit_make_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_motorno" class="block text-sm font-medium text-gray-700">Motor Number *</label>
                        <input type="text" name="new_motorno" id="new_motorno" value="{{ old('new_motorno') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        @error('new_motorno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_chasisno" class="block text-sm font-medium text-gray-700">Chassis Number *</label>
                        <input type="text" name="new_chasisno" id="new_chasisno" value="{{ old('new_chasisno') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        @error('new_chasisno')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_platenumber" class="block text-sm font-medium text-gray-700">Plate Number *</label>
                        <input type="text" name="new_platenumber" id="new_platenumber" value="{{ old('new_platenumber') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy">
                        @error('new_platenumber')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.motor-change.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </a>
                <button type="button" onclick="submitForm()" class="bg-primary-navy hover:bg-primary-gold text-white px-4 py-2 rounded">
                    Save New Details
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitForm() {
        Swal.fire({
            title: 'Save New Motor Details?',
            text: 'Are you sure you want to save these new motor details?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1D2761',
            cancelButtonColor: '#E63946',
            confirmButtonText: 'Yes, Save',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('new-details-form').submit();
            }
        });
    }

    // Flash messages
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: {
            !!json_encode(session('success')) !!
        },
        icon: 'success',
        confirmButtonColor: '#1D2761'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: {
            !!json_encode(session('error')) !!
        },
        icon: 'error',
        confirmButtonColor: '#E63946'
    });
    @endif
</script>
@endsection