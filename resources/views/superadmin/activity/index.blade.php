@extends('layouts.superadmin')

@section('title', 'Activity Logs')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        Activity Logs
    </h2>
@endsection
@section('content')
    <div class="p-6 bg-white shadow rounded-lg">

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-2 w-full">
            <!-- Category Filter -->
            <div class="w-full sm:w-auto">
                <label for="categoryFilter" class="block text-xs font-medium text-gray-700 mb-0.5">Filter by Category</label>
                <select id="categoryFilter"
                    class="border rounded-md mt-2 py-1.5 px-2 text-xs sm:text-sm focus:ring focus:ring-primary-navy focus:border-primary-navy w-full sm:w-auto min-w-[120px]">
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
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-700 mb-0.5">Filter by Date</label>
                <div class="flex gap-2 flex-col xs:flex-row sm:flex-row mt-2">
                    <input type="text" id="minDate" placeholder="From"
                        class="border rounded-md py-1.5 px-2 text-xs sm:text-sm focus:ring focus:ring-primary-navy focus:border-primary-navy w-full xs:w-28 sm:w-32">
                    <input type="text" id="maxDate" placeholder="To"
                        class="border rounded-md py-1.5 px-2 text-xs sm:text-sm focus:ring focus:ring-primary-navy focus:border-primary-navy w-full xs:w-28 sm:w-32">
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
                                {{ $log->user->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-2">{{ ucfirst($log->category) }}</td>
                            <td class="px-4 py-2">{{ $log->description }}</td>
                            <td class="px-4 py-2 align-top">
                                @if(is_array($log->data) || is_object($log->data))
                                    <div class="text-xs bg-gray-50 p-2 rounded" style="max-width: 250px; overflow-x: auto;">
                                        <ul class="list-disc pl-4">
                                            @foreach((array) $log->data as $key => $value)
                                                <li>
                                                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                    <span>
                                                        @if(is_array($value) || is_object($value))
                                                            {{ json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}
                                                        @elseif(is_bool($value))
                                                            {{ $value ? 'true' : 'false' }}
                                                        @elseif(is_null($value))
                                                            <span class="italic text-gray-400">null</span>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(is_string($log->data) && ($json = json_decode($log->data, true)) && (is_array($json) || is_object($json)))
                                    <div class="text-xs bg-gray-50 p-2 rounded" style="max-width: 250px; overflow-x: auto;">
                                        <ul class="list-disc pl-4">
                                            @foreach($json as $key => $value)
                                                <li>
                                                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                    <span>
                                                        @if(is_array($value) || is_object($value))
                                                            {{ json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}
                                                        @elseif(is_bool($value))
                                                            {{ $value ? 'true' : 'false' }}
                                                        @elseif(is_null($value))
                                                            <span class="italic text-gray-400">null</span>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-xs bg-gray-50 p-2 rounded block" style="max-width: 250px; overflow-x: auto;">
                                        {{ $log->data ?? '-' }}
                                    </span>
                                @endif
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
    <script>
        $(document).ready(function () {
            // Tell DataTables how to read your formatted date
            $.fn.dataTable.moment('MMM D, YYYY hh:mm A'); // Matches "Oct 5, 2025 03:15 PM"

            var table = $('#logsTable').DataTable({
                pageLength: 10,
                order: [[4, 'desc']],
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
                initComplete: function () {
                    $('.dataTables_length select').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg');
                    $('.dataTables_filter input').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2');

                    // Make the search input smaller (text-xs, px-2, py-1, reduce width)
                    $('.dataTables_filter input').addClass(
                        'bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg ml-2 px-2 py-1'
                    ).css({
                        'height': '35px',
                        'width': '150px',
                        'max-width': '100%'
                    });

                    var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
                    var $length = $('.dataTables_length').css('margin', '0');
                    var $search = $('.dataTables_filter').css('margin', '0');
                    $controls.append($length).append($search);
                    $controls.insertBefore($('#logsTable').closest('.overflow-x-auto'));
                }
            });

            // Category filter
            $('#categoryFilter').on('change', function () {
                table.column(1).search(this.value).draw();
            });

            // Date range filter
            $("#minDate, #maxDate").datepicker({ dateFormat: "yy-mm-dd" });

            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var min = $('#minDate').val();
                var max = $('#maxDate').val();
                var date = data[4] || "";

                if (date) {
                    var logDate = new Date(date);
                    if ((min === "" || new Date(min) <= logDate) &&
                        (max === "" || new Date(max) >= logDate)) {
                        return true;
                    }
                    return false;
                }
                return true;
            });

            $('#minDate, #maxDate').change(function () {
                table.draw();
            });
        });
    </script>
@endpush