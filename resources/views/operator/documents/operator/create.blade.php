@extends('layouts.operator')

@php
    $hasSubmitted = false;

    if(isset($submittedDocuments) && $submittedDocuments->count()) {
        foreach($submittedDocuments as $doc) {
            if($doc->status === 'pending') {
                $hasSubmitted = true;
                break;
            }
        }
    }
@endphp

@if($hasSubmitted)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.location.href = "{{ route('operator.documents.driver.create') }}";
        });
    </script>
@endif

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Submit Operator Documents') }}
    </h2>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
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

        <!-- Document Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('operator.documents.operator.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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

                                <!-- Document Preview -->
                                <div class="mt-4 w-full">
                                    @if(str_ends_with(strtolower($doc->file_url), '.pdf'))
                                    <iframe src="{{ $doc->file_url }}" class="w-full h-[500px] border border-gray-200 rounded-lg"></iframe>
                                    @else
                                    <img src="{{ $doc->file_url }}" alt="Document Preview" class="w-full h-auto max-h-[500px] object-contain border border-gray-200 rounded-lg">
                                    @endif
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

                        <!-- New File Preview -->
                        <div id="preview_{{ $docType->document_id }}" class="mt-4 w-full hidden">
                            <img id="preview_img_{{ $docType->document_id }}" src="" alt="Preview" class="w-full h-auto max-h-[500px] object-contain border border-gray-200 rounded-lg hidden">
                            <iframe id="preview_pdf_{{ $docType->document_id }}" src="" class="w-full h-[500px] border border-gray-200 rounded-lg hidden"></iframe>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('operator.dashboard') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-button type="submit">
                        Upload Documents
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-medium text-blue-900 mb-3">Important Instructions:</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1">
                <li>All documents must be clear and readable</li>
                <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                <li>Maximum file size: 5MB per document</li>
                <li>Documents must be current and valid</li>
                <li>You can replace documents by uploading new ones</li>
            </ul>
        </div>
    </div>
</div>
@endsection

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
                    return;
                }

                const docId = this.id.split('_')[1];
                const previewContainer = document.getElementById(`preview_${docId}`);
                const previewImg = document.getElementById(`preview_img_${docId}`);
                const previewPdf = document.getElementById(`preview_pdf_${docId}`);

                previewContainer.classList.remove('hidden');

                if (file.type === 'application/pdf') {
                    previewImg.classList.add('hidden');
                    previewPdf.classList.remove('hidden');
                    previewPdf.src = URL.createObjectURL(file);
                } else {
                    previewPdf.classList.add('hidden');
                    previewImg.classList.remove('hidden');
                    previewImg.src = URL.createObjectURL(file);
                }
            }
        });
    });
</script>
@endpush

