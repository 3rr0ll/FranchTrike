<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

        <!-- Operator Documents -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Operators Documents</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($operatorDocuments as $doc)
                <div class="bg-white p-6 rounded-lg shadow border">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $doc->documentType->name }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full 
                                @if($doc->status === 'approved') bg-green-100 text-green-700
                                @elseif($doc->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($doc->status === 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </div>

                    <x-button onclick="openDocumentModal('{{ asset('storage/' . $doc->file_path) }}', '{{ $doc->documentType->name }}')">
                        View Document
                    </x-button>
                </div>
                @empty
                <p class="text-gray-600">No operator documents submitted yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Driver Documents -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mt-4 mb-4">Drivers Documents</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($driverDocuments as $doc)
                <div class="bg-white p-6 rounded-lg shadow border">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $doc->documentType->name }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full 
                                @if($doc->status === 'approved') bg-green-100 text-green-700
                                @elseif($doc->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($doc->status === 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-2">
                        Driver: 
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
                    </p>

                    <x-button onclick="openDocumentModal('{{ asset('storage/' . $doc->file_path) }}', '{{ $doc->documentType->name }}')">
                        View Document
                    </x-button>
                </div>
                @empty
                <p class="text-gray-600">No driver documents submitted yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="documentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60">
        <div class="bg-white w-full h-[90vh] max-w-none rounded-none shadow-lg relative flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-800"></h2>
                <button onclick="closeDocumentModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    ✖
                </button>
            </div>
            <div class="flex-1 p-0">
                <iframe id="documentViewer" class="w-full h-full min-h-[80vh] border-0" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal JS -->
    <script>
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
</x-app-layout>