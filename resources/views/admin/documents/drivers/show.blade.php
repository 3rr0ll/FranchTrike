<x-admin-layout>
    <h2 class="text-2xl font-semibold mb-4">Documents for Driver: {{ $driver->first_name }} {{ $driver->last_name }}</h2>

    @forelse($documents as $document)
    <div class="mb-4 p-4 border rounded shadow">
        <h3 class="font-semibold">{{ $document->documentType->name }}</h3>
        <p>Status: <span class="text-sm">{{ ucfirst($document->status) }}</span></p>
        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-500 underline">View</a>
    </div>
    @empty
    <p>No documents submitted by this driver.</p>
    @endforelse
</x-admin-layout>