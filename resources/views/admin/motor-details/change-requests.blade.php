@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
Motor Change Requests
</h2>
@endsection

@section('content')
<div class="w-full mt-4">

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Requests</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $counts['all'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $counts['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $counts['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $counts['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3 rounded border-l-4 border-green-600 bg-green-50 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-3 p-3 rounded border-l-4 border-red-600 bg-red-50 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.motor-change.change-create') }}" class="inline-flex items-center px-4 py-2 bg-primary-navy text-white rounded-lg font-semibold hover:bg-blue-900 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Motor Change Request
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-4">
    <div class="border-b font-semibold text-lg text-primary-navy mb-2">
       New Motor Change Request 
    </div>
    <div class="overflow-x-auto">
        <table id="motorChangeTable" class="table-auto row-border w-full text-left">
            <thead>
                <tr>
                    <th>Franchise No</th>
                    <th>Current Motor Details</th>
                    <th>New Motor Details</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr>
                    <td>{{ $request->franchiseApplication->franchise_no ?? 'N/A' }}</td>
                    <td>
                        <strong>Type:</strong> {{ $request->old_unit_type }} <br>
                        <strong>Make:</strong> {{ $request->oldUnitMake->name ?? 'N/A' }} <br>
                        <strong>Motor No:</strong> {{ $request->old_motorno }} <br>
                        <strong>Chassis No:</strong> {{ $request->old_chasisno }} <br>
                        <strong>Plate:</strong> {{ $request->old_platenumber }}
                    </td>
                    <td>
                        @if($request->new_unit_type)
                            <strong>Type:</strong> {{ $request->new_unit_type }} <br>
                            <strong>Make:</strong> {{ $request->newUnitMake->name ?? 'N/A' }} <br>
                            <strong>Motor No:</strong> {{ $request->new_motorno }} <br>
                            <strong>Chassis No:</strong> {{ $request->new_chasisno }} <br>
                            <strong>Plate:</strong> {{ $request->new_platenumber }}
                        @else
                            <span class="text-gray-500 italic">Not yet specified</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge 
                                @if($request->status == 'approved') bg-success 
                                @elseif($request->status == 'rejected') bg-danger 
                                @else bg-warning 
                                @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($request->status === 'pending')
                            @if(!$request->new_unit_type)
                                <a href="{{ route('admin.motor-change.input-details', $request->id) }}" class="inline-block bg-primary-navy hover:bg-primary-navy/90 text-white px-3 py-1 rounded text-sm">
                                   Evaluate
                                </a>
                            @else
                               <div class="flex flex-row gap-2">
                                   <form action="{{ route('admin.motor-change.approve', $request->id) }}" method="POST" class="js-approval-form" data-action="approve">
                                        @csrf
                                        <button type="submit" class="inline-block bg-primary-navy hover:bg-primary-navy/90 text-white px-3 py-1 rounded text-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.motor-change.reject', $request->id) }}" method="POST" class="js-approval-form" data-action="reject">
                                        @csrf
                                        <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Reject</button>
                                    </form>
                               </div>
                            @endif
                        @else
                            <em>No actions available</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- History Table for Approved and Rejected Requests --}}
@if($historyRequests && $historyRequests->count() > 0)
<div class="bg-white shadow-sm rounded-lg mt-8 p-4">
    <div class="border-b font-semibold text-lg text-primary-navy mb-2">
        Motor Change Request History (Approved &amp; Rejected)
    </div>
    <div class="overflow-x-auto">
        <table id="motorChangeHistoryTable" class="w-full text-sm text-left text-black">
            <thead class="text-xs bg-gray-50 text-black">
                <tr>
                    <th>Franchise No</th>
                    <th>Current Motor Details</th>
                    <th>New Motor Details</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Processed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyRequests as $request)
                    <tr class="bg-white border-b hover:bg-gray-50 text-black">
                        <td class="px-6 py-4 font-medium">
                            {{ $request->franchiseApplication->franchise_no ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div><strong>Type:</strong> {{ ucfirst($request->old_unit_type) }}</div>
                                <div><strong>Make:</strong> {{ $request->oldUnitMake->name ?? 'N/A' }}</div>
                                <div><strong>Motor No:</strong> {{ $request->old_motorno }}</div>
                                <div><strong>Chassis No:</strong> {{ $request->old_chasisno }}</div>
                                <div><strong>Plate:</strong> {{ $request->old_platenumber }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($request->new_unit_type)
                                <div class="text-sm">
                                    <div><strong>Type:</strong> {{ ucfirst($request->new_unit_type) }}</div>
                                    <div><strong>Make:</strong> {{ $request->newUnitMake->name ?? 'N/A' }}</div>
                                    <div><strong>Motor No:</strong> {{ $request->new_motorno }}</div>
                                    <div><strong>Chassis No:</strong> {{ $request->new_chasisno }}</div>
                                    <div><strong>Plate:</strong> {{ $request->new_platenumber }}</div>
                                </div>
                            @else
                                <span class="text-gray-500 italic">Not specified</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($request->status == 'approved') bg-green-100 text-green-800
                                @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($request->status == 'approved' && $request->updated_at)
                                {{ $request->updated_at->format('M d, Y') }}
                            @elseif($request->status == 'rejected' && $request->updated_at)
                                {{ $request->updated_at->format('M d, Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white shadow-sm rounded-lg mt-8 p-6">
    <div class="text-center text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No History</h3>
        <p class="mt-1 text-sm text-gray-500">No approved or rejected motor change requests yet.</p>
    </div>
</div>
@endif

{{-- DataTables and SweetAlert Script --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#motorChangeTable').DataTable({
            responsive: true,
            order: [[4, 'desc']],
            pageLength: 10
        });
        
        @if($historyRequests && $historyRequests->count() > 0)
        $('#motorChangeHistoryTable').DataTable({
            responsive: true,
            order: [[4, 'desc']],
            pageLength: 10,
            language: {
                search: "Search history:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ history entries",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            }
        });
        @endif

        // Flash messages via SweetAlert
        @if(session('success'))
            Swal.fire({
                title: 'Success',
                text: `{{ session('success') }}`,
                icon: 'success',
                confirmButtonColor: '#1D2761'
            });
        @endif
        @if(session('error'))
            Swal.fire({
                title: 'Error',
                text: `{{ session('error') }}`,
                icon: 'error',
                confirmButtonColor: '#E63946'
            });
        @endif

        // Intercept approve/reject with confirmation dialogs
        document.querySelectorAll('.js-approval-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const action = form.getAttribute('data-action');
                const isApprove = action === 'approve';
                Swal.fire({
                    title: isApprove ? 'Approve this request?' : 'Reject this request?',
                    text: isApprove ? 'This will update the motor details.' : 'This will mark the request as rejected.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: isApprove ? '#16a34a' : '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: isApprove ? 'Yes, approve' : 'Yes, reject'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection