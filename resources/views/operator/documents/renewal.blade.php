@extends('layouts.operator')

@section('title', 'Renewal of Documents')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Renewal Document Submission
</h2>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">

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

        <!-- Instructions -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-blue-900 mb-3">Important Instructions:</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1">
                <li>All documents must be clear and readable</li>
                <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                <li>Maximum file size: 5MB per document</li>
                <li>Documents must be current and valid</li>
            </ul>
        </div>

        <form action="{{ route('operator.documents.renewal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- =================== OPERATOR SECTION =================== -->
            <div class="mb-10">
                <h3 class="text-2xl font-semibold text-primary-navy mb-4">Operator Documents</h3>
                <div class="space-y-6">
                    @foreach($operatorDocumentTypes as $docType)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            {{ $docType->name }} <span class="text-red-500">*</span>
                        </h3>
                        @if(isset($submittedOperatorDocuments[$docType->document_id]))
                        @php $doc = $submittedOperatorDocuments[$docType->document_id]; @endphp
                        <div class="mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                         @if($doc->status === 'approved') bg-green-100 text-green-800
                         @elseif($doc->status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($doc->status) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-600">
                                {{ $doc->document_name }} ({{ $doc->formatted_file_size }})
                            </span>
                            <a href="{{ $doc->file_url }}" target="_blank"
                                class="ml-2 text-blue-600 hover:text-blue-800 text-sm">View</a>
                        </div>

                        @if($doc->status === 'rejected' && $doc->rejection_reason)
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                            <strong>Rejection Reason:</strong> {{ $doc->rejection_reason }}
                        </div>
                        @endif
                        @endif

                        <!-- Dropzone Upload -->
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file-operator-{{ $docType->document_id }}"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 relative"
                                id="dropzone-label-operator-{{ $docType->document_id }}"
                            >
                                <!-- Preview Container -->
                                <div id="file-preview-container-operator-{{ $docType->document_id }}"
                                    class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-95 rounded-lg overflow-hidden">
                                    <div id="file-preview-content-operator-{{ $docType->document_id }}"
                                        class="w-full h-full flex items-center justify-center"></div>
                                </div>
                                <div class="flex flex-col items-center justify-center pointer-events-none" id="dropzone-content-operator-{{ $docType->document_id }}">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 
                                            5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 
                                            5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 
                                            15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-400">PDF, PNG, JPG, JPEG (Max 5MB)</p>
                                </div>
                                <input id="dropzone-file-operator-{{ $docType->document_id }}"
                                    name="operator_documents[{{ $docType->document_id }}]"
                                    type="file"
                                    class="hidden"
                                    accept=".pdf,.png,.jpg,.jpeg"
                                    {{ isset($submittedOperatorDocuments[$docType->document_id]) ? '' : 'required' }} />
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- =================== END OPERATOR SECTION =================== -->

            <!-- =================== DRIVER SECTION =================== -->
            <div>
                <h3 class="text-2xl font-semibold text-primary-navy mb-4">Driver Documents</h3>
                @foreach($drivers as $driver)
                <div class="mb-8 p-4 bg-gray-50 rounded-lg border">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        {{ $driver->last_name }}, {{ $driver->first_name }} {{ $driver->middle_initial ? $driver->middle_initial . '.' : '' }}
                        <span class="ml-3 px-3 py-0.5 rounded bg-blue-100 text-blue-700 text-xs font-semibold">Driver #{{ $driver->driver_id }}</span>
                    </h4>
                    <div class="space-y-6">
                        @foreach($driverDocumentTypes as $docType)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h5 class="text-md font-medium text-gray-900 mb-2">
                                {{ $docType->name }} <span class="text-red-500">*</span>
                            </h5>
                            @php
                                $submittedDocs = $submittedDriverDocuments[$driver->driver_id] ?? collect();
                                $doc = $submittedDocs[$docType->document_id] ?? null;
                            @endphp
                            @if($doc)
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($doc->status === 'approved') bg-green-100 text-green-800
                                    @elseif($doc->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($doc->status) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $doc->document_name }} ({{ $doc->formatted_file_size }})
                                </span>
                                <a href="{{ $doc->file_url }}" target="_blank"
                                    class="ml-2 text-blue-600 hover:text-blue-800 text-sm">View</a>
                            </div>
                            @if($doc->status === 'rejected' && $doc->rejection_reason)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                                <strong>Rejection Reason:</strong> {{ $doc->rejection_reason }}
                            </div>
                            @endif
                            @endif
                            <!-- Dropzone Upload -->
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}"
                                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 relative"
                                    id="dropzone-label-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}"
                                >
                                    <!-- Preview Container -->
                                    <div id="file-preview-container-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}"
                                        class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-95 rounded-lg overflow-hidden">
                                        <div id="file-preview-content-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}"
                                            class="w-full h-full flex items-center justify-center"></div>
                                    </div>
                                    <div class="flex flex-col items-center justify-center pointer-events-none" id="dropzone-content-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}">
                                        <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 
                                                5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 
                                                5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 
                                                15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-400">PDF, PNG, JPG, JPEG (Max 5MB)</p>
                                    </div>
                                    <input id="dropzone-file-driver-{{ $driver->driver_id }}-{{ $docType->document_id }}"
                                        name="driver_documents[{{ $driver->driver_id }}][{{ $docType->document_id }}]"
                                        type="file"
                                        class="hidden"
                                        accept=".pdf,.png,.jpg,.jpeg"
                                        {{ ($doc ? '' : 'required') }} />
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <!-- =================== END DRIVER SECTION =================== -->

            <!-- Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('operator.dashboard') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <x-button type="submit">Submit Renewal Documents</x-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Operator Documents Dropzone
    const operatorDocumentTypeIds = {!! json_encode($operatorDocumentTypes->pluck('document_id')) !!};
    operatorDocumentTypeIds.forEach(function(docId) {
        const input = document.getElementById('dropzone-file-operator-' + docId);
        const previewContainer = document.getElementById('file-preview-container-operator-' + docId);
        const previewContent = document.getElementById('file-preview-content-operator-' + docId);
        const dropzoneContent = document.getElementById('dropzone-content-operator-' + docId);
        const dropzoneLabel = document.getElementById('dropzone-label-operator-' + docId);

        if (!input) return;

        // Drag & drop support
        if (dropzoneLabel) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzoneLabel.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            dropzoneLabel.addEventListener('dragover', function() {
                dropzoneLabel.classList.add('ring-2', 'ring-indigo-300');
            });
            dropzoneLabel.addEventListener('dragleave', function() {
                dropzoneLabel.classList.remove('ring-2', 'ring-indigo-300');
            });
            dropzoneLabel.addEventListener('drop', function(e) {
                dropzoneLabel.classList.remove('ring-2', 'ring-indigo-300');
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        }

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if ((file.size / 1024 / 1024) > 5) {
                alert('File size must be less than 5MB');
                input.value = '';
                previewContainer.classList.add('hidden');
                dropzoneContent.classList.remove('opacity-30');
                return;
            }

            previewContent.innerHTML = '';
            if (file.type === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = URL.createObjectURL(file);
                iframe.className = "w-full h-full object-contain border rounded-lg";
                previewContent.appendChild(iframe);
            } else if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = "w-full h-full object-contain border rounded-lg";
                previewContent.appendChild(img);
            } else {
                previewContent.innerHTML = '<span class="text-red-600">Unsupported file type</span>';
            }

            previewContainer.classList.remove('hidden');
            dropzoneContent.classList.add('opacity-30');
        });

        previewContainer.addEventListener('click', function() {
            previewContainer.classList.add('hidden');
            dropzoneContent.classList.remove('opacity-30');
            input.value = '';
        });
    });

    // Driver Documents Dropzones
    const driverDocumentDropzones = [];
    @foreach($drivers as $driver)
        @foreach($driverDocumentTypes as $docType)
            driverDocumentDropzones.push({
                driverId: "{{ $driver->driver_id }}",
                docTypeId: "{{ $docType->document_id }}"
            });
        @endforeach
    @endforeach
    driverDocumentDropzones.forEach(function(item) {
        const inputId = 'dropzone-file-driver-' + item.driverId + '-' + item.docTypeId;
        const previewContainerId = 'file-preview-container-driver-' + item.driverId + '-' + item.docTypeId;
        const previewContentId = 'file-preview-content-driver-' + item.driverId + '-' + item.docTypeId;
        const dropzoneContentId = 'dropzone-content-driver-' + item.driverId + '-' + item.docTypeId;
        const dropzoneLabelId = 'dropzone-label-driver-' + item.driverId + '-' + item.docTypeId;

        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewContent = document.getElementById(previewContentId);
        const dropzoneContent = document.getElementById(dropzoneContentId);
        const dropzoneLabel = document.getElementById(dropzoneLabelId);

        if (!input) return;

        // Drag & drop support
        if (dropzoneLabel) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzoneLabel.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            dropzoneLabel.addEventListener('dragover', function() {
                dropzoneLabel.classList.add('ring-2', 'ring-indigo-300');
            });
            dropzoneLabel.addEventListener('dragleave', function() {
                dropzoneLabel.classList.remove('ring-2', 'ring-indigo-300');
            });
            dropzoneLabel.addEventListener('drop', function(e) {
                dropzoneLabel.classList.remove('ring-2', 'ring-indigo-300');
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        }

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if ((file.size / 1024 / 1024) > 5) {
                alert('File size must be less than 5MB');
                input.value = '';
                previewContainer.classList.add('hidden');
                dropzoneContent.classList.remove('opacity-30');
                return;
            }

            previewContent.innerHTML = '';
            if (file.type === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = URL.createObjectURL(file);
                iframe.className = "w-full h-full object-contain border rounded-lg";
                previewContent.appendChild(iframe);
            } else if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = "w-full h-full object-contain border rounded-lg";
                previewContent.appendChild(img);
            } else {
                previewContent.innerHTML = '<span class="text-red-600">Unsupported file type</span>';
            }

            previewContainer.classList.remove('hidden');
            dropzoneContent.classList.add('opacity-30');
        });

        previewContainer.addEventListener('click', function() {
            previewContainer.classList.add('hidden');
            dropzoneContent.classList.remove('opacity-30');
            input.value = '';
        });
    });
</script>
@endpush
@endsection
