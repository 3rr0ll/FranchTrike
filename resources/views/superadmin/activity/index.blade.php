@extends('layouts.superadmin')

@section('title', 'Activity Logs')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Activity Logs
</h2>
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
        <table id="logsTable" class="min-w-full row-border">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Data</th>
                    <th class="px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td class="px-4 py-2" style="min-width: 150px;">
                        {{ $log->user->name ?? 'System' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($log->category) }}</td>
                    <td class="px-4 py-2">{{ $log->description }}</td>
                    <td class="px-4 py-2">
                        <pre class="text-xs bg-gray-50 p-2 rounded" style="max-width: 250px; overflow-x: auto; white-space: pre-wrap;">
                        {{ json_encode($log->data, JSON_PRETTY_PRINT) }}
                        </pre>
                    </td>
                    <td class="px-4 py-2">{{ $log->created_at->format('M d, Y h:i A') }}</td>
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
        // Initialize DataTable with custom design
        var table = $('#logsTable').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [4, 'desc']
            ],
            columnDefs: [{
                targets: 4, // Date column
                orderable: true,
                searchable: true
            }],
            language: {
                search: "Search logs:",
                lengthMenu: "Show _MENU_ logs per page",
                info: "Showing _START_ to _END_ of _TOTAL_ logs",
                infoEmpty: "Showing 0 to 0 of 0 logs",
                infoFiltered: "(filtered from _MAX_ total logs)",
                zeroRecords: "No logs found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function() {
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
                );
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
                );

                var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
                var $length = $('.dataTables_length').css('margin', '0');
                var $search = $('.dataTables_filter').css('margin', '0');
                $controls.append($length).append($search);

                $controls.insertBefore($('#logsTable').closest('.overflow-x-auto'));
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