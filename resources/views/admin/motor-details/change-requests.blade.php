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

    <form method="GET" action="{{ route('admin.motor-change.index') }}" class="mb-3 d-flex align-items-center gap-2">
        <label for="status" class="me-2">Status:</label>
        <select name="status" id="status" class="form-select w-auto" onchange="this.form.submit()">
            <option value="all" {{ (isset($status) && $status==='all') ? 'selected' : '' }}>All ({{ $counts['all'] ?? 0 }})</option>
            <option value="pending" {{ (!isset($status) || $status==='pending') ? 'selected' : '' }}>Pending ({{ $counts['pending'] ?? 0 }})</option>
            <option value="approved" {{ (isset($status) && $status==='approved') ? 'selected' : '' }}>Approved ({{ $counts['approved'] ?? 0 }})</option>
            <option value="rejected" {{ (isset($status) && $status==='rejected') ? 'selected' : '' }}>Rejected ({{ $counts['rejected'] ?? 0 }})</option>
        </select>
    </form>

    <div class="bg-white shadow-sm rounded-lg">
    <div class="overflow-x-auto">
        <table id="motorChangeTable" class="table-auto w-full text-left">
            <thead>
                <tr>
                    <th>Franchise No</th>
                    <th>Old Motor Details</th>
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
                        <strong>Type:</strong> {{ $request->new_unit_type }} <br>
                        <strong>Make:</strong> {{ $request->newUnitMake->name ?? 'N/A' }} <br>
                        <strong>Motor No:</strong> {{ $request->new_motorno }} <br>
                        <strong>Chassis No:</strong> {{ $request->new_chasisno }} <br>
                        <strong>Plate:</strong> {{ $request->new_platenumber }}
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
                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($request->status === 'pending')
                        <form action="{{ route('admin.motor-change.approve', $request->id) }}" method="POST" class="d-inline js-approval-form" data-action="approve">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>

                        <form action="{{ route('admin.motor-change.reject', $request->id) }}" method="POST" class="d-inline js-approval-form" data-action="reject">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
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

{{-- DataTables and SweetAlert Script --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#motorChangeTable').DataTable({
            responsive: true
        });

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