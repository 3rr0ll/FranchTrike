@extends('layouts.superadmin')

@section('title', 'Activity Logs')

@section('content')
<div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Activity Logs</h2>

    <div class="overflow-x-auto">
        <table id="logsTable" class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Category</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Data</th>
                    <th class="px-4 py-2 border">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $index => $log)
                    <tr>
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($log->category) }}</td>
                        <td class="px-4 py-2 border">{{ $log->description }}</td>
                        <td class="px-4 py-2 border">
                            <pre class="text-xs bg-gray-50 p-2 rounded">
                                {{ json_encode($log->data, JSON_PRETTY_PRINT) }}
                            </pre>
                        </td>
                        <td class="px-4 py-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#logsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[5, 'desc']]
        });
    });
</script>
@endpush
