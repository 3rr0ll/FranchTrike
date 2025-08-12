<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Request Motor Change for Franchise #{{ $application->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('operator.franchise.index') }}" class="inline-block mb-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    &larr; Back to Franchise List
                </a>
                <form id="motor-change-form" action="{{ route('operator.franchise.motor-change.store', $application->id) }}" method="POST">
                    @csrf
                    <p><strong>Old Motor Details</strong></p>
                    @if($motorDetail)
                    <ul>
                        <li>Type: {{ $motorDetail->unit_type }}</li>
                        <li>Make: {{ $motorDetail->unitMake->name }}</li>
                        <li>Motor No: {{ $motorDetail->motorno }}</li>
                        <li>Chasis No: {{ $motorDetail->chasisno }}</li>
                        <li>Plate No: {{ $motorDetail->platenumber }}</li>
                    </ul>
                    @else
                    <p>No motor details available.</p>
                    @endif

                    <hr class="my-4">
                    <p><strong>New Motor Details</strong></p>
                    <div class="mb-4">
                        <label class="block">Unit Type</label>
                        <select name="new_unit_type" required class="border rounded w-full py-2 px-3">
                            <option value="">-- Select Unit Type --</option>
                            <option value="tricycle">Tricycle</option>
                            <option value="motocab">Motocab</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block">Unit Make</label>
                        <select name="new_unit_make_id" required class="border rounded w-full py-2 px-3">
                            @foreach($unitMakes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block">Motor No</label>
                        <input type="text" name="new_motorno" required class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="mb-4">
                        <label class="block">Chasis No</label>
                        <input type="text" name="new_chasisno" required class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="mb-4">
                        <label class="block">Plate No</label>
                        <input type="text" name="new_platenumber" required class="border rounded w-full py-2 px-3">
                    </div>

                    <button type="button" id="submit-btn" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Request</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('motor-change-form');
            const submitBtn = document.getElementById('submit-btn');

            if (submitBtn) {
                submitBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Submit request?',
                        text: 'Please confirm the new motor details before submitting.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#1D2761',
                        cancelButtonColor: '#E63946',
                        confirmButtonText: 'Yes, submit',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            // Flash messages
            @if(session('success'))
                Swal.fire({
                    title: 'Success',
                    text: @json(session('success')),
                    icon: 'success',
                    confirmButtonColor: '#1D2761'
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    title: 'Error',
                    text: @json(session('error')),
                    icon: 'error',
                    confirmButtonColor: '#E63946'
                });
            @endif
        });
    </script>
</x-guest-layout>