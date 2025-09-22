@extends('layouts.superadmin')

@section('header')
    <div class="bg-primary-navy text-white py-4 px-6 mb-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold">Superadmin Activity Logs</h1>
        <p class="text-sm mt-1">Monitor all recent activities performed by users and the system.</p>
    </div>
@endsection

@section('content')
<div class="p-6 bg-white shadow rounded-lg">
   
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-4 ">
        <!-- Category Filter -->
        <div>
            <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
            <select id="categoryFilter" class="border rounded-lg p-2 focus:ring focus:ring-primary-navy focus:border-primary-navy">
                <option value="">All Categories</option>
                @php
                    $categories = $logs->pluck('category')->unique();
                @endphp
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Date Range Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
            <div class="flex gap-2">
                <input type="text" id="minDate" placeholder="From" 
                       class="border rounded-lg p-2 focus:ring focus:ring-primary-navy focus:border-primary-navy w-32">
                <input type="text" id="maxDate" placeholder="To" 
                       class="border rounded-lg p-2 focus:ring focus:ring-primary-navy focus:border-primary-navy w-32">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="logsTable" class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Category</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Data</th>
                    <th class="px-4 py-2 border">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="px-4 py-2 border">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($log->category) }}</td>
                        <td class="px-4 py-2 border">{{ $log->description }}</td>
                        <td class="px-4 py-2 border">
                            <pre class="text-xs bg-gray-50 p-2 rounded">
                                {{ json_encode($log->data, JSON_PRETTY_PRINT) }}
                            </pre>
                        </td>
                        <td class="px-4 py-2 border">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>  
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#logsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[4, 'desc']],
            language: {
                search: "",
                searchPlaceholder: "Search logs..."
            }
        });

        // Style search box
        $('#logsTable_filter input').addClass(
            'border rounded-lg p-2 focus:ring focus:ring-primary-navy focus:border-primary-navy'
        );
        $('#logsTable_filter').addClass('mb-4');

        // Category filter
        $('#categoryFilter').on('change', function() {
            var selected = $(this).val();
            table.column(1).search(selected).draw(); 
        });

        // Datepickers
        $("#minDate, #maxDate").datepicker({
            dateFormat: "yy-mm-dd"
        });

        // Custom filtering function for date range
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = $('#minDate').val();
                var max = $('#maxDate').val();
                var date = data[4] || ""; // Date column index

                if (date) {
                    var logDate = new Date(date);

                    if ((min === "" || new Date(min) <= logDate) &&
                        (max === "" || new Date(max) >= logDate)) {
                        return true;
                    }
                    return false;
                }
                return true;
            }
        );

        // Trigger table redraw on date change
        $('#minDate, #maxDate').change(function() {
            table.draw();
        });
    });
</script>
@endpush
