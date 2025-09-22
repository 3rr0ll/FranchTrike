@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    Operator & Driver Documents
</h2>
@endsection

@section('content')
<div class="w-full px-0 sm:px-0 lg:px-0 py-8 space-y-12">
    <!-- Operator Documents Table -->

    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-bold text-gray-800">Operator Documents</h2>
        @if($operatorDocuments->isEmpty())
        <div class="flex justify-end mb-4">
            <a href="{{ route('operator.documents.operator.create') }}">
                <x-button size="md" class="bg-primary-navy text-white hover:bg-primary-gold">
                    Upload Operator Document
                </x-button>
            </a>
        </div>
        @endif
        <div class="overflow-x-auto">
            <table id="operator-documents-table" class="min-w-full bg-white row-border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Document Type</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                        <th class="px-4 py-2 text-left">Rejection Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operatorDocuments as $doc)
                    <tr>
                        <td class="px-4 py-2">{{ $doc->documentType->name }}</td>
                        <td class="px-4 py-2">
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($doc->status === 'approved') bg-green-100 text-green-700
                                @elseif($doc->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($doc->status === 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            {{-- Use file_url for Cloudinary files --}}
                            <x-button size="sm" onclick="openDocumentModal('{{ $doc->file_url ?: $doc->full_file_url }}', '{{ $doc->documentType->name }}')">
                                View
                            </x-button>
                            @if($doc->status === 'rejected')
                            <x-button size="sm" class="bg-red-500 hover:bg-red-600" onclick="openResubmitModal('operator', {{ $doc->id }}, '{{ $doc->documentType->name }}', '{{ route('operator.documents.operator.resubmit', $doc->id) }}')">
                                Resubmit
                            </x-button>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-xs text-red-600">
                            @if($doc->status === 'rejected' && $doc->rejection_reason)
                            {{ $doc->rejection_reason }}
                            @endif
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Driver Documents Table -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-bold text-gray-800">Driver Documents</h2>

        <div class="flex justify-end mb-4">
            <a href="{{ route('operator.documents.driver.create') }}">
                <x-button size="md" class="bg-primary-navy text-white hover:bg-primary-gold">
                    Upload Driver Document
                </x-button>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table id="driver-documents-table" class="min-w-full bg-white rounded row-border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Document Type</th>
                        <th class="px-4 py-2 text-left">Driver</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                        <th class="px-4 py-2 text-left">Rejection Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($driverDocuments as $doc)
                    <tr>
                        <td class="px-4 py-2">{{ $doc->documentType->name }}</td>
                        <td class="px-4 py-2">
                            @if(isset($doc->driver))
                            @php
                            $driver = $doc->driver;
                            $middleInitial = $driver->middle_initial ? ' ' . $driver->middle_initial . '.' : '';
                            $driverName = $driver->first_name . $middleInitial . ' ' . $driver->last_name;
                            @endphp
                            {{ $driverName }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($doc->status === 'approved') bg-green-100 text-green-700
                                @elseif($doc->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($doc->status === 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            {{-- Use file_url directly for Cloudinary, or full_file_url as fallback --}}
                            <x-button size="sm" onclick="openDocumentModal('{{ $doc->file_url ?: $doc->full_file_url }}', '{{ $doc->documentType->name }}')">
                                View
                            </x-button>
                            @if($doc->status === 'rejected')
                            <x-button size="sm" class="bg-red-500 hover:bg-red-600" onclick="openResubmitModal('driver', {{ $doc->id }}, '{{ $doc->documentType->name }}', '{{ route('operator.documents.driver.resubmit', $doc->id) }}')">
                                Resubmit
                            </x-button>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-xs text-red-600">
                            @if($doc->status === 'rejected' && $doc->rejection_reason)
                            {{ $doc->rejection_reason }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Document Modal -->
<div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50 hidden">
    <div class="relative mx-auto my-12 w-full max-w-6xl bg-white rounded-md shadow-lg">
        <div class="flex flex-col h-[90vh] p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
                <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden" id="documentViewerContainer">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Resubmit Modal -->
<div id="resubmitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50 hidden">
    <div class="relative mx-auto my-12 w-full max-w-5xl bg-white rounded-md shadow-lg">
        <div class="flex flex-col p-6" style="min-height: 60vh;">
            <div class="flex items-center justify-between mb-4">
                <h3 id="resubmitModalTitle" class="text-xl font-semibold text-gray-900">Resubmit Document</h3>
                <button onclick="closeResubmitModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="resubmitForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="doc_id" id="resubmitDocId" value="">
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 relative">
                        <div id="file-preview-container" class="absolute inset-0 flex items-center justify-center z-10 hidden bg-white bg-opacity-90 rounded-lg">
                            <div id="file-preview-content" class="flex flex-col items-center"></div>
                        </div>
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropzone-content">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF, PNG, JPG, JPEG (Max 5MB)</p>
                        </div>
                        <input id="dropzone-file" name="document" type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg" required />
                    </label>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeResubmitModal()" class="mr-2 px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-primary-navy text-white hover:bg-primary-gold">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function () {
        // Operator Documents Table
        $('#operator-documents-table').DataTable({
            order: [],
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [2, 3] }
            ],
            language: {
                emptyTable: "No operator documents submitted yet."
            }
        });

        // Driver Documents Table
        $('#driver-documents-table').DataTable({
            order: [],
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [3, 4] }
            ],
            language: {
                emptyTable: "No driver documents submitted yet."
            }
        });
    });

    // ---------------------------
    // Document Modal Functions
    // ---------------------------
    function openDocumentModal(fileUrl, documentName) {
        document.getElementById('modalTitle').textContent = documentName;
        const container = document.getElementById('documentViewerContainer');

        const isPdf = fileUrl.toLowerCase().includes('.pdf') || fileUrl.toLowerCase().includes('image/upload') === false;
        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(fileUrl) || fileUrl.includes('image/upload');

        if (isImage && !isPdf) {
            container.innerHTML = `
                <img src="${fileUrl}" 
                     alt="${documentName}" 
                     class="w-full h-full object-contain rounded-md border border-gray-300" 
                     style="min-height:600px;" />
            `;
        } else {
            container.innerHTML = `
                <iframe src="${fileUrl}" 
                        class="w-full h-full rounded-md border border-gray-300" 
                        style="min-height:600px;" 
                        frameborder="0">
                    <p>Your browser does not support iframes. 
                       <a href="${fileUrl}" target="_blank">Click here to view the document</a>
                    </p>
                </iframe>
            `;
        }

        document.getElementById('documentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDocumentModal() {
        document.getElementById('modalTitle').textContent = '';
        document.getElementById('documentViewerContainer').innerHTML = '';
        document.getElementById('documentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ---------------------------
    // Resubmit Modal Functions
    // ---------------------------
    function openResubmitModal(type, docId, docName, actionUrl) {
        document.getElementById('resubmitModalTitle').textContent = 'Resubmit ' + docName;
        document.getElementById('resubmitForm').action = actionUrl;
        document.getElementById('resubmitDocId').value = docId;
        document.getElementById('dropzone-file').value = '';
        document.getElementById('resubmitModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeResubmitModal() {
        document.getElementById('resubmitModalTitle').textContent = '';
        document.getElementById('resubmitForm').action = '';
        document.getElementById('resubmitDocId').value = '';
        document.getElementById('dropzone-file').value = '';
        document.getElementById('resubmitModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ---------------------------
    // File Upload Preview + Validation
    // ---------------------------
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('dropzone-file');
        const previewContainer = document.getElementById('file-preview-container');
        const previewContent = document.getElementById('file-preview-content');
        const dropzoneContent = document.getElementById('dropzone-content');

        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                previewContent.innerHTML = '';
                if (!file) {
                    previewContainer.classList.add('hidden');
                    dropzoneContent.classList.remove('opacity-30');
                    return;
                }

                const fileType = file.type;
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                const width = '450px';
                const height = '360px';

                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    e.target.value = '';
                    return;
                }

                if (fileType === 'application/pdf') {
                    previewContent.innerHTML = `
                        <div style="width:${width};height:${height};display:flex;flex-direction:column;align-items:center;justify-content:center;">
                            <svg class="w-16 h-16 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm font-semibold text-gray-700 mb-1">${fileName}</p>
                            <p class="text-xs text-gray-500 mb-2">${fileSize}</p>
                            <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-700 rounded">PDF Preview not available</span>
                            <button type="button" class="mt-3 px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" onclick="removeFilePreview()">Remove</button>
                        </div>
                    `;
                } else if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        previewContent.innerHTML = `
                            <div style="width:${width};height:${height};display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                <img src="${ev.target.result}" alt="Preview" style="max-width:100%;max-height:70%;object-fit:contain;display:block;margin-bottom:0.5rem;border-radius:0.5rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);" />
                                <p class="text-sm font-semibold text-gray-700 mb-1">${fileName}</p>
                                <p class="text-xs text-gray-500 mb-2">${fileSize}</p>
                                <button type="button" class="mt-3 px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" onclick="removeFilePreview()">Remove</button>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContent.innerHTML = `
                        <div style="width:${width};height:${height};display:flex;flex-direction:column;align-items:center;justify-content:center;">
                            <span class="text-red-600">Unsupported file type</span>
                            <button type="button" class="mt-3 px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" onclick="removeFilePreview()">Remove</button>
                        </div>
                    `;
                }

                previewContainer.classList.remove('hidden');
                dropzoneContent.classList.add('opacity-30');
            });
        }

        window.removeFilePreview = function () {
            if (fileInput) fileInput.value = '';
            previewContent.innerHTML = '';
            previewContainer.classList.add('hidden');
            dropzoneContent.classList.remove('opacity-30');
        };
    });
</script>
@endpush
