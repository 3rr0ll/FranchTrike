@extends('layouts.admin')

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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $document)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $document->documentType->name }}</h3>
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        @if($document->status === 'approved') bg-green-100 text-green-800
                        @elseif($document->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($document->status === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($document->status) }}
                    </span>
                </div>

                <div class="space-y-3">
                    <x-button
                        color="blue"
                        class="flex-1 flex items-center justify-center"
                        onclick="openDocumentModal('{{ asset('storage/' . $document->file_path) }}', '{{ $document->documentType->name }}', '{{ $document->id }}')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Document
                    </x-button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No documents</h3>
                <p class="mt-1 text-sm text-gray-500">No documents have been submitted by this operator yet.</p>
            </div>
        </div>
        @endforelse
    </div>
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
            <div class="flex-1 overflow-hidden">
                <iframe id="documentViewer" class="w-full h-full rounded-md border border-gray-300" style="min-height:600px;" frameborder="0"></iframe>
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

    function openDocumentModal(filePath, documentName, documentId) {
        document.getElementById('modalTitle').textContent = documentName;
        document.getElementById('documentViewer').src = filePath;
        currentDocumentId = documentId;

        document.getElementById('documentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDocumentModal() {
        document.getElementById('documentModal').classList.add('hidden');
        document.getElementById('documentViewer').src = '';
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