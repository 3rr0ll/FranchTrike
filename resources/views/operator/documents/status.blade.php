@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Operator & Driver Documents
</h2>
@endsection

@section('content')
<div class="w-full px-0 sm:px-0 lg:px-0 py-8 space-y-12">
    <!-- Operator Documents Table -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Operator Documents</h2>
        <div class="overflow-x-auto">
            <table id="operator-documents-table" class="min-w-full bg-white rounded shadow border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Document Type</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                        <th class="px-4 py-2 text-left">Rejection Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operatorDocuments as $doc)
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
                            <x-button size="sm" onclick="openDocumentModal('{{ asset('storage/' . $doc->file_path) }}', '{{ $doc->documentType->name }}')">
                                View
                            </x-button>
                            @if($doc->status === 'rejected')
                                @php
                                    $resubmitUrl = route('operator.documents.operator.create', ['type' => $doc->document_type_id]);
                                @endphp
                                <x-button size="sm" class="bg-red-500 hover:bg-red-600" onclick="window.location.href='{{ $resubmitUrl }}'">
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
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-gray-600 text-center">No operator documents submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Driver Documents Table -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mt-4 mb-4">Driver Documents</h2>
        <div class="overflow-x-auto">
            <table id="driver-documents-table" class="min-w-full bg-white rounded shadow border">
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
                    @forelse ($driverDocuments as $doc)
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
                            <x-button size="sm" onclick="openDocumentModal('{{ asset('storage/' . $doc->file_path) }}', '{{ $doc->documentType->name }}')">
                                View
                            </x-button>
                            @if($doc->status === 'rejected')
                                @php
                                    $resubmitUrl = route('operator.documents.driver.create', ['type' => $doc->document_type_id]);
                                @endphp
                                <x-button size="sm" class="bg-red-500 hover:bg-red-600" onclick="window.location.href='{{ $resubmitUrl }}'">
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
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-gray-600 text-center">No driver documents submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
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
            <div class="flex-1 overflow-hidden">
                <iframe id="documentViewer" class="w-full h-full rounded-md border border-gray-300" style="min-height:600px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $('#operator-documents-table').DataTable({
            "order": [],
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": [2,3] }
            ]
        });
        $('#driver-documents-table').DataTable({
            "order": [],
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": [3,4] }
            ]
        });
    });

    function openDocumentModal(filePath, documentName) {
        document.getElementById('modalTitle').textContent = documentName;
        document.getElementById('documentViewer').src = filePath;
        document.getElementById('documentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDocumentModal() {
        document.getElementById('modalTitle').textContent = '';
        document.getElementById('documentViewer').src = '';
        document.getElementById('documentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush