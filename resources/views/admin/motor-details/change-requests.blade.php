@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Motor Change Requests</h2>

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

    <table id="motorChangeTable" class="table table-bordered table-striped">
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
                    <form action="{{ route('admin.motor-change.approve', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this request?')">Approve</button>
                    </form>

                    <form action="{{ route('admin.motor-change.reject', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this request?')">Reject</button>
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

{{-- DataTables Script --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#motorChangeTable').DataTable();
    });
</script>
@endpush
@endsection