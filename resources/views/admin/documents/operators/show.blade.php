@extends('layouts.admin')

@section('title', 'Operator Details')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Operator Document
</h2>
@endsection

@section('content')
<div class="w-full mt-4">
    <div class="flex items-center mb-4 sm:mb-0">
        <a href="{{ route('admin.operators.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-navy border border-transparent rounded-lg hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-navy">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Operators
        </a>
    </div>
    <div class="mb-8 bg-white p-4 rounded-lg shadow border border-gray-200 mt-4">
        <h2 class="text-2xl font-bold text-primary-navy mb-4">Operator Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-800">
            <div>
                <span class="font-semibold">Full Name:</span>
                {{ $operator->last_name }}, {{ $operator->first_name }} {{ $operator->middle_initial ? $operator->middle_initial . '.' : '' }}
            </div>
            <div>
                <span class="font-semibold">Birth Date:</span>
                {{ $operator->birth_date }}
            </div>
            <div>
                <span class="font-semibold">Age:</span>
                {{ $operator->age }}
            </div>
            <div>
                <span class="font-semibold">Sex:</span>
                {{ ucfirst($operator->sex) }}
            </div>
            <div>
                <span class="font-semibold">Civil Status:</span>
                {{ ucfirst($operator->civil_status) }}
            </div>
            <div>
                <span class="font-semibold">Contact No:</span>
                {{ $operator->contact_no }}
            </div>
            <div class="col-span-full">
                <span class="font-semibold">Address:</span>
                {{ $operator->barangay }}, {{ $operator->municipality }}, {{ $operator->province }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
        <table id="documentsTable" class="min-w-full display nowrap table-auto" style="width:100%">
            <thead>
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>#</th>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $i => $document)
                <tr>
                    <td class="py-2 px-4">{{ $i + 1 }}</td>
                    <td class="py-2 px-4">{{ $document->documentType->name }}</td>
                    <td class="py-2 px-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if($document->status === 'approved') bg-green-100 text-green-800
                            @elseif($document->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($document->status === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($document->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        {{-- Updated to use Cloudinary URL --}}
                        <x-button
                            color="blue"
                            class="flex items-center justify-center"
                            onclick="openDocumentModal('{{ $document->file_url ?: $document->full_file_url }}', '{{ $document->documentType->name }}', '{{ $document->id }}')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Document
                        </x-button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Document Modal -->
    <div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50 hidden">
        <div class="relative mx-auto my-12 w-full max-w-6xl bg-white rounded-md shadow-lg">
            <div class="flex flex-col h-[90vh] p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
                    <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Document Viewer -->
                <div class="flex-1 overflow-hidden" id="documentViewerContainer">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <!-- Modal Footer Buttons -->
                <div class="flex justify-end mt-4 space-x-3">
                    <form id="verifyForm" method="POST">
                        @csrf
                        <input type="hidden" name="status" id="documentStatus">
                        <div id="rejectionReasonBox" class="hidden mt-2">
                            <textarea name="rejection_reason" id="rejectionReason" rows="2" placeholder="Enter reason for rejection..."
                                class="w-full border rounded p-2 text-sm text-gray-800"></textarea>
                        </div>
                        <div class="flex gap-2 mt-2 justify-end">
                            <x-button type="button" onclick="submitVerification('approved')">
                                Approve
                            </x-button>
                            <x-button type="button" color="red" onclick="showRejectReason()">
                                Reject
                            </x-button>
                            <x-button type="button" class="hidden" id="submitButton" onclick="submitVerification('rejected')">
                                Submit
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentDocumentId = null;

        function openDocumentModal(fileUrl, documentName, documentId) {
            document.getElementById('modalTitle').textContent = documentName;
            currentDocumentId = documentId;

            const container = document.getElementById('documentViewerContainer');
            
            // Check if it's a PDF or image based on file extension or URL
        const isPdf = fileUrl.toLowerCase().includes('.pdf') || fileUrl.toLowerCase().includes('image/upload') === false;
        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(fileUrl) || fileUrl.includes('image/upload');

        if (isImage && !isPdf) {
            // Display as image
            container.innerHTML = `
                <img src="${fileUrl}" 
                     alt="${documentName}" 
                     class="w-full h-full object-contain rounded-md border border-gray-300" 
                     style="min-height:600px;" />
            `;
        } else {
            // Display as iframe (works for PDFs)
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
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentViewerContainer').innerHTML = '';
            document.getElementById('rejectionReasonBox').classList.add('hidden');
            document.getElementById('rejectionReason').value = '';
            document.getElementById('submitButton').classList.add('hidden');
            document.getElementById('documentStatus').value = '';
            document.body.style.overflow = 'auto';
        }

        function submitVerification(status) {
            console.log('Submitting with status:', status);
            document.getElementById('documentStatus').value = status;

            const form = document.getElementById('verifyForm');

            if (currentDocumentId) {
                const baseUrl = '{{ url("/") }}';
                form.action = `${baseUrl}/admin/documents/operator/${currentDocumentId}/verify`;
                console.log('Form action set to:', form.action);
            }

            if (status === 'approved') {
                form.submit();
            }
        }

        function showRejectReason() {
            document.getElementById('rejectionReasonBox').classList.remove('hidden');
            document.getElementById('submitButton').classList.remove('hidden');
            document.getElementById('documentStatus').value = 'rejected';

            const form = document.getElementById('verifyForm');

            if (currentDocumentId) {
                form.action = `/admin/documents/operator/${currentDocumentId}/verify`;
            }
        }

        function submitVerification(status) {
            console.log('Submitting with status:', status);
            document.getElementById('documentStatus').value = status;

            const form = document.getElementById('verifyForm');

            if (currentDocumentId) {
                const baseUrl = '{{ url("/") }}';
                form.action = `${baseUrl}/admin/documents/operator/${currentDocumentId}/verify`;
                console.log('Form action set to:', form.action);
            }

            if (status === 'rejected') {
                const reason = document.getElementById('rejectionReason').value.trim();
                if (!reason) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Rejection Reason Required',
                        text: 'Please provide a reason for rejection.',
                    });
                    return;
                }
            }

            form.submit();
        }

        $(document).ready(function() {
            $('#documentsTable').DataTable({
                responsive: true,
                "order": [],
                "language": {
                    "emptyTable": "No documents have been submitted by this operator yet."
                }
            });
        });
    </script>
    @if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('status') }}",
            confirmButtonColor: '#3085d6',
        });
    </script>
    @endif
    @endpush
    @endsection