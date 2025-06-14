<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Driver Documents') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Driver Selection -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Select Driver</h2>
                @if($drivers->isEmpty())
                <div class="text-center py-8">
                    <div class="text-gray-500 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Drivers Found</h3>
                    <p class="text-gray-600 mb-4">You need to add drivers before uploading their documents.</p>
                    <x-button href="{{ route('operator.driver.create') }}">
                        Add Driver
                    </x-button>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($drivers as $driver)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">
                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                </h3>
                                <p class="text-sm text-gray-600">License: {{ $driver->license_no }}</p>
                            </div>
                            <x-button
                                href="{{ route('operator.documents.driver.create', ['driver' => $driver->id]) }}"
                                class="px-3 py-1 text-sm {{ $driverId == $driver->id ? 'bg-blue-800' : '' }}">
                                {{ $driverId == $driver->id ? 'Selected' : 'Select' }}
                            </x-button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Document Upload Form -->
            @if($driverId && $drivers->isNotEmpty())
            @php $selectedDriver = $drivers->where('id', $driverId)->first(); @endphp

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Documents for: {{ $selectedDriver->first_name }} {{ $selectedDriver->last_name }}
                    </h2>
                    <p class="text-sm text-gray-600">License: {{ $selectedDriver->license_no }}</p>
                </div>

                <form action="{{ route('operator.documents.driver.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{ $driverId }}">

                    <div class="space-y-6">
                        @foreach($documentTypes as $docType)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $docType->name }}
                                        <span class="text-red-500">*</span>
                                    </h3>
                                    @if(isset($submittedDocuments[$docType->document_id]))
                                    @php $doc = $submittedDocuments[$docType->document_id]; @endphp
                                    <div class="mt-2 flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($doc->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($doc->status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800
                                                        @endif">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $doc->document_name }} ({{ $doc->formatted_file_size }})
                                        </span>
                                        <a href="{{ $doc->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                    </div>
                                    @if($doc->status === 'rejected' && $doc->rejection_reason)
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                                        <strong>Rejection Reason:</strong> {{ $doc->rejection_reason }}
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3">
                                <input
                                    type="file"
                                    name="documents[{{ $docType->document_id }}]"
                                    id="doc_{{ $docType->document_id }}"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="block w-full text-sm text-gray-500
                                                   file:mr-4 file:py-2 file:px-4
                                                   file:rounded-full file:border-0
                                                   file:text-sm file:font-semibold
                                                   file:bg-blue-50 file:text-blue-700
                                                   hover:file:bg-blue-100"
                                    {{ isset($submittedDocuments[$docType->document_id]) ? '' : 'required' }}>
                                <p class="mt-1 text-xs text-gray-500">
                                    Supported formats: PDF, JPG, JPEG, PNG. Max size: 5MB
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <x-button
                            href="{{ route('operator.documents.driver.create') }}"
                            class="border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                            Back to Driver Selection
                        </x-button>
                        <x-button type="submit" class="bg-blue-600 text-white hover:bg-blue-700">
                            Upload Documents
                        </x-button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Instructions -->
            <div class="bg-blue-50 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-medium text-blue-900 mb-3">Important Instructions:</h3>
                <ul class="list-disc list-inside text-blue-800 space-y-1">
                    <li>All documents must be clear and readable</li>
                    <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                    <li>Maximum file size: 5MB per document</li>
                    <li>Documents must be current and valid</li>
                    <li>License must be valid and not expired</li>
                    <li>Medical certificate should be recent</li>
                    <li>You can replace documents by uploading new ones</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // File upload preview
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    if (fileSize > 5) {
                        alert('File size must be less than 5MB');
                        this.value = '';
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>