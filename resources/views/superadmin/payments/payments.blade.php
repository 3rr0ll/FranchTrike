@extends('layouts.superadmin')

@section('title', 'Payments Record')
@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Payments Record
</h2>
@endsection

@section('content')
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('superadmin.payments.index') }}"
            class="inline-flex items-center px-4 mb-5 py-2 bg-primary-navy border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Payments
        </a>
        <!-- Date Filter & Export Buttons -->
        <div
            class="rounded-lg mb-2 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-4">
            <div class="flex items-center space-x-4">
                <label for="min-date" class="text-sm font-medium text-gray-700">From:</label>
                <input type="date" id="min-date" class="border rounded-md p-2 text-sm">

                <label for="max-date" class="text-sm font-medium text-gray-700">To:</label>
                <input type="date" id="max-date" class="border rounded-md p-2 text-sm">
            </div>
            <div id="export-buttons" class="flex flex-wrap gap-2 items-center">
                <!-- Export buttons will be injected here by DataTables -->
            </div>
        </div>

        <!-- Payments -->

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="overflow-auto">

                <table id="grouped-payments-table" class="w-full divide-y divide-gray-200 display nowrap"
                    style="width:100%">
                    <thead class="bg-gray-50">
                        <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                            <th>Application #</th>
                            <th>OR Number</th>
                            <th>Operator</th>
                            <th>Fees</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Manage by</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPayments as $group)
                            <tr>
                                <td>{{ $group['application_number'] }}</td>
                                <td>{{ $group['or_no'] ?? '-' }}</td>
                                <td>{{ $group['operator_name'] }}</td>
                                <td>
                                    <ul class="list-disc ml-4">
                                        @foreach($group['fees'] as $fee)
                                            <li>{{ $fee['description'] }} – ₱{{ number_format($fee['amount_paid'], 2) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>₱{{ number_format($group['total_amount'], 2) }}</td>
                                <td>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group['paid_at'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $group['paid_at'] ? 'Paid' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($group['reviewer'])
                                        {{ $group['reviewer']->name }}
                                    @else
                                        <span class="text-gray-400 italic">Not reviewed</span>
                                    @endif
                                </td>
                                <td>{{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('M d, Y h:i A') : 'Pending' }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var table = $('#grouped-payments-table').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                    targets: 6,
                    orderable: false,
                    searchable: false
                }],
                language: {
                    search: "Search applications:",
                    lengthMenu: "Show _MENU_ applications per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ applications",
                    infoEmpty: "Showing 0 to 0 of 0 applications",
                    infoFiltered: "(filtered from _MAX_ total applications)",
                    zeroRecords: "No applications found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        className: 'bg-blue-500 text-white px-3 py-1 rounded'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'bg-green-500 text-white px-3 py-1 rounded'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'bg-red-500 text-white px-3 py-1 rounded'
                    },
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

                    $controls.insertBefore($('#grouped-payments-table').closest('.overflow-auto'));

                    // Move export buttons to the export-buttons div
                    var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                    let $exportDiv = $('#export-buttons');
                    if ($exportDiv.length === 0) {
                        $exportDiv = $('<div id="export-buttons" class="flex flex-wrap gap-2 mb-4"></div>');
                        $('#grouped-payments-table').before($exportDiv);
                    }
                    $exportDiv.empty().append(btns);
                }
            });

            // Date range filter
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var min = $('#min-date').val();
                var max = $('#max-date').val();
                var date = data[7]; // column index of "Date" (updated from 5 to 7)

                if (!date) return false;

                var parsedDate = new Date(date);
                if ((min === "" || new Date(min) <= parsedDate) &&
                    (max === "" || parsedDate <= new Date(max))) {
                    return true;
                }
                return false;
            });

            $('#min-date, #max-date').on('change', function () {
                table.draw();
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#1D2761'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#E63946'
            });
        </script>
    @endif
@endsection