@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
Franchise Applications
</h2>
@endsection

@section('content')


    <div class="w-full mt-6">
        <div class="bg-white shadow p-6 rounded-lg">
            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
                
                @if ($canApply)
                <a href="{{ route('operator.franchise.create') }}">
                    <x-button>Submit New Application</x-button>
                </a>
                @else
                <x-button disabled>Complete Document Approval to Apply</x-button>
                @endif
            </div>

            {{-- Active Franchise Cards --}}
            @php
                // Get the 2 most recent active (approved and not expired) franchises
                $activeFranchises = $applications->where('status', 'approved')->sortByDesc('submitted_at')->take(2);
                // Get the rest (renewed/expired/other) for the table
                $renewedFranchises = $applications->filter(function($app) {
                    return $app->status !== 'approved';
                });
            @endphp

            @if($activeFranchises->count())
            <div class="mb-8">
                <h4 class="text-md font-semibold mb-3 text-primary-navy">Active Franchise(s)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeFranchises as $app)
                    <div class="border rounded-lg shadow p-5 bg-white flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        </div>
                        <div class="mt-2">
                            <div class="text-2xl font-bold text-primary-navy mb-1">Franchise #{{ $app->franchise_no ?? '-' }}</div>
                            <div class="text-sm text-gray-600 mb-2">Submitted: {{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</div>
                            <div class="text-sm text-gray-700 mb-2">
                                Expiration: 
                                {{ $app->franchise_end_date ? \Carbon\Carbon::parse($app->franchise_end_date)->format('M d, Y') : '-' }}
                            </div>
                        </div>
                        <div class="flex gap-2 mt-auto">
                           
                        </div>
                        <div class="flex gap-2 mt-2 justify-end">
                            <a href="{{ route('operator.franchise.show', $app) }}" class="inline-block bg-primary-navy text-white px-4 py-2 rounded hover:bg-primary-gold hover:text-primary-navy text-sm">View</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Renewed/Other Franchises Table --}}
            <div>
                <h4 class="text-md font-semibold mb-3 text-primary-navy">Renewed/Other Franchise Applications</h4>
                <div class="overflow-x-auto">
                    <table id="renewedFranchiseTable" class="w-full table-auto border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">#</th>
                                <th class="p-2 text-left">Application Type</th>
                                <th class="p-2 text-left">Status</th>
                                <th class="p-2 text-left">Franchise No</th>
                                <th class="p-2 text-left">Submitted</th>
                                <th class="p-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($renewedFranchises as $app)
                            <tr class="border-t">
                                <td class="p-2">{{ $app->id }}</td>
                                <td class="p-2 capitalize">{{ $app->application_type }}</td>
                                <td class="p-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($app->status == 'approved') bg-green-100 text-green-800
                                        @elseif($app->status == 'rejected') bg-red-100 text-red-800
                                        @elseif($app->status == 'expired') bg-red-100 text-red-800
                                        @elseif($app->status == 'under_review' || $app->status == 'submitted') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($app->status === 'under_review')
                                            Under review
                                        @else
                                            {{ ucfirst($app->status ?? 'pending') }}
                                        @endif
                                    </span>
                                </td>
                                <td class="p-2">{{ $app->franchise_no ?? '-' }}</td>
                                <td class="p-2">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</td>
                                <td class="p-2 space-x-2">
                                    <a href="{{ route('operator.franchise.show', $app) }}" class="inline-block bg-primary-navy text-white px-3 py-1 rounded hover:bg-primary-gold hover:text-primary-navy">View</a>
                                    @if($app->status === 'approved' && $app->motorDetail)
                                    <a href="{{ route('operator.franchise.motor-change.create', $app->id) }}" class="inline-block bg-accent-purple text-white px-3 py-1 rounded hover:bg-accent-purple/90">Request Motor Change</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No renewed/other applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#renewedFranchiseTable').DataTable({
                "order": [[ 0, "desc" ]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 5 }
                ]
            });
        });
    </script>
@endpush