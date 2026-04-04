@extends('layouts.operator')

@section('title', 'Upload Documents')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Submit Operator Documents
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

        <!-- Document Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="operator-documents-form"
                action="{{ route('operator.documents.operator.store') }}"
                method="POST"
                enctype="multipart/form-data"
                data-upload-url="{{ route('operator.documents.operator.upload-ajax') }}">
                @csrf

                <!-- Document Uploads -->
                <div class="space-y-6">
                    @foreach($documentTypes as $docType)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            {{ $docType->name }} <span class="text-red-500">*</span>
                        </h3>

                        <!-- If already submitted -->
                        @if(isset($submittedDocuments[$docType->document_id]))
                        @php $doc = $submittedDocuments[$docType->document_id]; @endphp
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

                            <!-- Cloudinary file URL -->
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
                            <label for="dropzone-file-{{ $docType->document_id }}"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 relative">
                               
                                <!-- Preview Container -->
                                <div id="file-preview-container-{{ $docType->document_id }}"
                                    class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-95 rounded-lg overflow-hidden">
                                    <div id="file-preview-content-{{ $docType->document_id }}"
                                        class="w-full h-full flex items-center justify-center"></div>
                                </div>

                                <div class="flex flex-col items-center justify-center pointer-events-none" id="dropzone-content-{{ $docType->document_id }}">
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

                                <input id="dropzone-file-{{ $docType->document_id }}" name="documents[{{ $docType->document_id }}]"
                                    type="file" class="hidden" accept=".pdf,.png,.jpg,.jpeg"
                                    {{ isset($submittedDocuments[$docType->document_id]) ? '' : 'required' }} />
                            </label>
                        </div>
                        <div id="upload-progress-wrap-{{ $docType->document_id }}" class="hidden mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div id="upload-progress-bar-{{ $docType->document_id }}"
                                    class="bg-indigo-600 h-2.5 rounded-full transition-all duration-150 ease-out"
                                    style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 text-right"><span id="upload-progress-pct-{{ $docType->document_id }}">0%</span></p>
                        </div>
                        <p id="upload-error-{{ $docType->document_id }}" class="hidden mt-2 text-sm text-red-600"></p>
                    </div>
                    @endforeach
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('operator.dashboard') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <x-button type="submit">Upload Documents</x-button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
(function () {
    var documentTypeIds = @json($documentTypes->pluck('document_id')->values());
    var form = document.getElementById('operator-documents-form');
    var uploadUrl = form ? form.getAttribute('data-upload-url') : '';
    var redirectAfterSuccess = @json(route('operator.documents.driver.create'));

    function csrfToken() {
        var t = form && form.querySelector('input[name="_token"]');
        return t ? t.value : '';
    }

    function parseXhrError(xhr) {
        try {
            var j = JSON.parse(xhr.responseText);
            if (j.message) return j.message;
            if (j.errors) {
                var keys = Object.keys(j.errors);
                if (keys.length && j.errors[keys[0]][0]) return j.errors[keys[0]][0];
            }
        } catch (e) {}
        return 'Upload failed (' + xhr.status + ').';
    }

    function uploadWithProgress(url, formData, onProgress) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken());
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.upload.onprogress = function (ev) {
                if (ev.lengthComputable) onProgress(ev.loaded / ev.total);
            };
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        resolve(JSON.parse(xhr.responseText));
                    } catch (err) {
                        reject('Invalid server response');
                    }
                } else {
                    reject(parseXhrError(xhr));
                }
            };
            xhr.onerror = function () {
                reject('Network error.');
            };
            xhr.send(formData);
        });
    }

    function setProgress(docId, ratio) {
        var p = Math.max(0, Math.min(1, ratio));
        var bar = document.getElementById('upload-progress-bar-' + docId);
        var pct = document.getElementById('upload-progress-pct-' + docId);
        if (bar) bar.style.width = (p * 100) + '%';
        if (pct) pct.textContent = Math.round(p * 100) + '%';
    }

    documentTypeIds.forEach(function (docId) {
        var input = document.getElementById('dropzone-file-' + docId);
        var previewContainer = document.getElementById('file-preview-container-' + docId);
        var previewContent = document.getElementById('file-preview-content-' + docId);
        var dropzoneContent = document.getElementById('dropzone-content-' + docId);

        if (!input) return;

        input.addEventListener('change', function () {
            var file = input.files[0];
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
                var iframe = document.createElement('iframe');
                iframe.src = URL.createObjectURL(file);
                iframe.className = 'w-full h-full object-contain border rounded-lg';
                previewContent.appendChild(iframe);
            } else if (file.type.startsWith('image/')) {
                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full h-full object-contain border rounded-lg';
                previewContent.appendChild(img);
            } else {
                previewContent.innerHTML = '<span class="text-red-600">Unsupported file type</span>';
            }

            previewContainer.classList.remove('hidden');
            dropzoneContent.classList.add('opacity-30');
        });

        previewContainer.addEventListener('click', function () {
            previewContainer.classList.add('hidden');
            dropzoneContent.classList.remove('opacity-30');
            input.value = '';
            var err = document.getElementById('upload-error-' + docId);
            var wrap = document.getElementById('upload-progress-wrap-' + docId);
            if (err) {
                err.classList.add('hidden');
                err.textContent = '';
            }
            if (wrap) wrap.classList.add('hidden');
            setProgress(docId, 0);
        });
    });

    if (!form || !uploadUrl) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        for (var i = 0; i < documentTypeIds.length; i++) {
            var docId = documentTypeIds[i];
            var input = document.getElementById('dropzone-file-' + docId);
            if (!input) continue;
            if (input.required && (!input.files || !input.files.length)) {
                alert('Please select all required documents.');
                return;
            }
        }

        var promises = [];
        var submitBtn = form.querySelector('button[type="submit"]');

        documentTypeIds.forEach(function (docId) {
            var input = document.getElementById('dropzone-file-' + docId);
            if (!input || !input.files || !input.files.length) return;

            var errEl = document.getElementById('upload-error-' + docId);
            var wrap = document.getElementById('upload-progress-wrap-' + docId);
            if (errEl) {
                errEl.classList.add('hidden');
                errEl.textContent = '';
            }
            if (wrap) wrap.classList.remove('hidden');
            setProgress(docId, 0);

            var fd = new FormData();
            fd.append('file', input.files[0]);
            fd.append('document_type_id', docId);

            promises.push(
                uploadWithProgress(uploadUrl, fd, function (ratio) {
                    setProgress(docId, ratio);
                }).catch(function (msg) {
                    if (errEl) {
                        errEl.textContent = msg;
                        errEl.classList.remove('hidden');
                    }
                    return Promise.reject(msg);
                })
            );
        });

        if (promises.length === 0) {
            alert('Please choose at least one file to upload.');
            return;
        }

        if (submitBtn) submitBtn.disabled = true;

        Promise.all(promises)
            .then(function () {
                function go() {
                    window.location.href = redirectAfterSuccess;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Uploaded',
                        text: 'Documents uploaded successfully.'
                    }).then(go);
                } else {
                    alert('Documents uploaded successfully.');
                    go();
                }
            })
            .finally(function () {
                if (submitBtn) submitBtn.disabled = false;
            });
    });
})();
</script>
@endpush
@endsection