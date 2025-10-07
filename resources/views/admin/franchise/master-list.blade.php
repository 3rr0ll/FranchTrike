@extends('layouts.admin')

@section('title', 'Master List')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        Master List
    </h2>
@endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full sm:w-auto">
        <!-- Date Range Picker -->
        <div class="flex items-center gap-2">
            <span class="text-gray-600">From:</span>
            <input type="date" id="datepicker-range-start"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
            <span class="text-gray-600">to</span>
            <input type="date" id="datepicker-range-end"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
        </div>

        <!-- Route Filter Dropdown -->
        <div class="flex items-center gap-2">
            <span class="text-gray-600">Route:</span>
            <select id="route-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
                <option value="">All</option>
                @php
                    $routeNames = [];
                    foreach($applications as $application) {
                        if ($application->route && $application->route->name) {
                            $routeNames[$application->route->name] = true;
                        }
                    }
                @endphp
                @foreach(array_keys($routeNames) as $routeName)
                    <option value="{{ $routeName }}">{{ $routeName }}</option>
                @endforeach
            </select>
        </div>

        <!-- Export Buttons -->
        <div id="export-buttons" class="flex flex-wrap gap-2 items-center">
            <!-- Buttons will be injected here by DataTables -->
        </div>

        <!-- Print Preview Button -->
        <button id="print-preview-btn" class="bg-gray-700 text-white px-3 py-1 rounded flex items-center gap-2" style="white-space:nowrap;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8"/>
            </svg>
            Print Preview
        </button>
    </div>
    <div class="p-4 bg-white rounded-lg shadow" style="margin: 8px;">
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-left row-border" id="master-list-table">
                <thead class="bg-gray-50">
                    <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                        <th>Franchise No</th>
                        <th>Sticker No</th>
                        <th>Operator's Name</th>
                        <th>Driver's Name</th>
                        <th>Route</th>
                        <th>Unit Type</th>
                        <th>Plate No.</th>
                        <th>Unit Make</th>
                        <th>Motor No.</th>
                        <th>Chassis No.</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                        @php $motor = $application->motorDetail; @endphp
                        <tr>
                            <td class="px-4 py-2">{{ $application->franchise_no ?? ($motor->franchise_number ?? 'N/A') }}</td>
                            <td class="px-4 py-2">{{ $application->sticker_no ?? ($motor->sticker_number ?? 'N/A') }}</td>
                            <td class="px-4 py-2">
                                {{ $application->operator?->full_name ?? $application->operator_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $application->driver?->full_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $application->route?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $motor?->unit_type ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $motor?->platenumber ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $motor?->unitMake?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $motor?->motorno ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $motor?->chasisno ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $application->submitted_at ? $application->submitted_at->format('F d, Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                var table = $('#master-list-table').DataTable({
                    pageLength: 25,
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'All']],
                    order: [[10, 'desc']],
                    dom: 'lBfrtip', 
                    buttons: [
                        { extend: 'csvHtml5', text: 'CSV', className: 'bg-blue-500 text-white px-3 py-1 rounded' },
                        { extend: 'excelHtml5', text: 'Excel', className: 'bg-green-500 text-white px-3 py-1 rounded' },
                        { extend: 'pdfHtml5', text: 'PDF', className: 'bg-red-500 text-white px-3 py-1 rounded' },
                    ],
                    initComplete: function () {
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
                        $controls.insertBefore($('#master-list-table').closest('.overflow-x-auto'));

                        // Move export buttons to custom container if needed
                        var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                        $('#export-buttons').empty().append(btns);
                    }
                });

                // --- Date Range Filter ---
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'master-list-table') return true;

                    var start = $('#datepicker-range-start').val();
                    var end = $('#datepicker-range-end').val();
                    var submitted = data[10]; // Date Submitted column (Y-m-d)

                    if (!submitted || submitted === 'N/A') return false;

                    var submittedDate = new Date(submitted);
                    if (isNaN(submittedDate.getTime())) return false;

                    if (start) {
                        var startDate = new Date(start);
                        startDate.setHours(0,0,0,0);
                        if (submittedDate < startDate) return false;
                    }
                    if (end) {
                        var endDate = new Date(end);
                        endDate.setHours(23,59,59,999);
                        if (submittedDate > endDate) return false;
                    }

                    return true;
                });

                // --- Route Filter ---
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'master-list-table') return true;

                    var selectedRoute = $('#route-filter').val();
                    var route = data[4] ? data[4].trim() : '';

                    if (selectedRoute && route !== selectedRoute) {
                        return false;
                    }
                    return true;
                });

                // Re-draw when date inputs or route filter change
                $('#datepicker-range-start, #datepicker-range-end, #route-filter').on('change', function() {
                    table.draw();
                });

                // Print Preview Button Handler
                $('#print-preview-btn').on('click', function() {
                    // Get current filter values
                    var start = $('#datepicker-range-start').val();
                    var end = $('#datepicker-range-end').val();
                    var selectedRoute = $('#route-filter').val();

                    // Get filtered data from DataTable
                    var filteredData = table.rows({ search: 'applied' }).data().toArray();

                    // Get table headers
                    var headers = [];
                    $('#master-list-table thead th').each(function() {
                        headers.push($(this).text());
                    });

                    // Build print preview HTML
                    var printWindow = window.open('', '_blank');
                    var margin = "16px";
                    // Get current date and time in GMT+8
                    function getPHDateTimeString() {
                        var now = new Date();
                        // Convert to GMT+8
                        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                        var phTime = new Date(utc + (8 * 60 * 60 * 1000));
                        // Format: Month Day, Year HH:MM:SS AM/PM (12-hour format)
                        var months = [
                            "January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"
                        ];
                        var month = months[phTime.getMonth()];
                        var day = phTime.getDate();
                        var year = phTime.getFullYear();
                        var hours24 = phTime.getHours();
                        var ampm = hours24 >= 12 ? 'PM' : 'AM';
                        var hours12 = hours24 % 12;
                        hours12 = hours12 ? hours12 : 12; // the hour '0' should be '12'
                        var hours = hours12.toString().padStart(2, '0');
                        var minutes = phTime.getMinutes().toString().padStart(2, '0');
                        var seconds = phTime.getSeconds().toString().padStart(2, '0');
                        return `${month} ${day}, ${year} ${hours}:${minutes}:${seconds} ${ampm}`;
                    }
                    var currentDateTimePH = getPHDateTimeString();

                    var headerHtml = `
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; background: #d0e2f7; color: #1a237e; border-radius: 8px; padding: 24px 16px;">
                            <div style="font-weight: bold; font-size: 1.5rem; flex: 1; text-align: left;">Franchise Master List</div>
                            <div style="flex: 1; text-align: center; font-size: 1.4rem; font-weight: 600;">
                                ${selectedRoute ? selectedRoute : ''}
                            </div>
                            <div style="flex: 1; text-align: right; font-size: 1rem; font-weight: 400;">
                                ${currentDateTimePH}
                            </div>
                        </div>
                    `;

                    // Make table text smaller: font-size: 0.75rem (was 0.95rem)
                    var tableHtml = '<table style="width:100%; border-collapse:collapse; font-size: 0.75rem;">';
                    tableHtml += '<thead><tr>';
                    headers.forEach(function(h) {
                        tableHtml += '<th style="border:1px solid #ccc; padding:2px 6px; background:#f3f3f3; font-size:0.75rem;">' + h + '</th>';
                    });
                    tableHtml += '</tr></thead><tbody>';
                    filteredData.forEach(function(row) {
                        tableHtml += '<tr>';
                        row.forEach(function(cell) {
                            var div = document.createElement('div');
                            div.textContent = cell;
                            tableHtml += '<td style="border:1px solid #ccc; padding:2px 6px; font-size:0.70rem;">' + div.innerHTML + '</td>';
                        });
                        tableHtml += '</tr>';
                    });
                    tableHtml += '</tbody></table>';

                    var style = `
                        <style>
                            @media print {
                                body { margin: ${margin}; }
                                table { page-break-inside: auto; }
                                tr { page-break-inside: avoid; page-break-after: auto; }
                                thead { display: table-header-group; }
                                tfoot { display: table-footer-group; }
                                .no-print { display: none !important; }
                                table, th, td { font-size: 0.75rem !important; }
                            }
                            body { margin: ${margin}; font-family: Arial, sans-serif; }
                            .no-print { display: block; }
                            table, th, td { font-size: 0.75rem !important; }
                        </style>
                    `;

                    
                    var csrfToken = '{{ csrf_token() }}';
                    var printButtonHtml = `
                        <div class="no-print" style="text-align: center; margin: 20px 0; padding: 20px; border-radius: 8px;">
                            <button id="printBtn" style="background-color: #1a237e; color: #fff; border: none; padding: 10px 24px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 0 2px 6px rgba(26,35,126,0.08); transition: background 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8"/>
            </svg>
                                Print
                            </button>
                        </div>
                        <script>
                            // Wait for DOMContentLoaded to ensure the button exists
                            document.addEventListener('DOMContentLoaded', function() {
                                var btn = document.getElementById('printBtn');
                                if (btn) {
                                    btn.addEventListener('click', function() {
                                        fetch("{{ route('admin.franchise.master-list.print-log') }}", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "X-CSRF-TOKEN": "${csrfToken}"
                                            },
                                            body: JSON.stringify({ activity: "Printed Master List", start: "${start}", end: "${end}", route: "${selectedRoute}" })
                                        }).finally(function() {
                                            // Always print, even if logging fails
                                            window.print();
                                        });
                                    });
                                }
                            });
                        <\/script>
                    `;

                    printWindow.document.write(`
                        <html>
                        <head>
                            <title>Master List Print Preview</title>
                            ${style}
                        </head>
                        <body>
                            ${printButtonHtml}
                            ${headerHtml}
                            ${tableHtml}
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                });
            });
        </script>
    @endpush
@endsection