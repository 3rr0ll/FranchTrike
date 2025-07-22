<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Franchise Applications</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6">
        <div class="bg-white shadow p-6 rounded-lg">
            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif
            <a href="{{ route('operator.home') }}">
                <x-button class="mt-4">Back</x-button>
            </a>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Application History</h3>
                @if ($canApply)
                <a href="{{ route('operator.franchise.create') }}">
                    <x-button>Submit New Application</x-button>
                </a>
                @else
                <x-button disabled>Complete Document Approval to Apply</x-button>
                @endif
            </div>

            <table class="w-full table-auto border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Application Type</th>
                        <th class="p-2 text-left">Status</th>
                        <th class="p-2 text-left">Franchise No</th>
                        <th class="p-2 text-left">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                    <tr class="border-t">
                        <td class="p-2">{{ $app->id }}</td>
                        <td class="p-2 capitalize">{{ $app->application_type }}</td>
                        <td class="p-2">{{ ucfirst($app->status ?? 'pending') }}</td>
                        <td class="p-2">{{ $app->franchise_no ?? '-' }}</td>
                        <td class="p-2">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">No applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>