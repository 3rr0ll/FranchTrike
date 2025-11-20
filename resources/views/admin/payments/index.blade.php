@extends('layouts.admin')

@section('title', 'Payments')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Payment Management
</h2>
@endsection

@section('content')
<div class="p-4">


    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
    @endif

    {{-- Payment Form --}}
    <div class="mb-8 p-4 bg-gray-50 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Accept Payment</h3>
        <form id="payment-form" method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            {{-- Franchise Application --}}
            <div>
                <label for="franchise_application_id" class="block font-medium mb-2">Select Franchise Application <span class="text-red-500">*</span></label>
                <select name="franchise_application_id" id="franchise_application_id" class="w-full border border-gray-300 rounded-lg p-2.5 select2" required>
                    <option value="">-- Select Application --</option>
                    @foreach($applications as $application)
                        @php
                            $franchiseNo = $application->franchise_no ?? 'No Franchise#';
                            $stickerNo = $application->sticker_no ?? 'No Sticker#';
                            $endDate = $application->franchise_end_date
                                ? \Carbon\Carbon::parse($application->franchise_end_date)->format('Y-m-d')
                                : 'No End Date';
                        @endphp
                        <option value="{{ $application->id }}" {{ old('franchise_application_id') == $application->id ? 'selected' : '' }}>
                            {{ $application->operator->last_name ?? 'N/A' }},
                            {{ $application->operator->first_name ?? '' }}
                            - Franchise#: {{ $franchiseNo }}, 
                            Sticker#: {{ $stickerNo }}, 
                            End Date: {{ $application->franchise_end_date
                                ? \Carbon\Carbon::parse($application->franchise_end_date)->format('M d, Y')
                                : 'No End Date' }}
                            (Plate Number: {{ $application->motorDetail->platenumber ?? 'No Plate' }})
                        <span class="ml-1 px-2 py-1 text-xs rounded 
                            @if($application->status == 'active') bg-green-200 text-green-800 
                            @elseif($application->status == 'expired') bg-red-200 text-red-800 
                            @else bg-gray-200 text-gray-800 @endif">
                            {{ ucfirst($application->status ?? 'Unknown') }}
                        </span>
                        </option>
                    @endforeach
                </select>
                @error('franchise_application_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>      
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mt-4 mb-2">
                    Select Fees to Pay
                </label>
                @php
                    // Arrange fees for vertical filling (first column, then second, then third)
                    $colCount = 3; 
                    $feeChunks = [];
                    $chunkSize = ceil(count($fees) / $colCount);
                    for($i = 0; $i < $colCount; $i++) {
                        $feeChunks[$i] = $fees->slice($i * $chunkSize, $chunkSize)->values();
                    }
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @for($row = 0; $row < $chunkSize; $row++)
                        @for($col = 0; $col < $colCount; $col++)
                            @php
                                $fee = isset($feeChunks[$col][$row]) ? $feeChunks[$col][$row] : null;
                            @endphp
                            <div class="flex items-center">
                                @if($fee)
                                    <input type="checkbox" name="fees[]" value="{{ $fee->id }}" id="fee_{{ $fee->id }}"
                                        class="mr-2 fee-checkbox" data-amount="{{ $fee->amount }}">
                                    <label for="fee_{{ $fee->id }}" class="text-gray-800">
                                        {{ $fee->description }} (₱{{ number_format($fee->amount, 2) }})
                                    </label>
                                @endif
                            </div>
                        @endfor
                    @endfor
                </div>
            </div>
            <div class="flex flex-col md:flex-row md:items-end md:justify-end gap-4">
                <div class="mb-4 md:mb-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Total Amount
                    </label>
                    <input type="text" id="total-amount" name="total_amount" readonly
                        class="w-40 px-3 py-2 border rounded bg-gray-100 font-bold"
                        value="₱0.00">
                </div>
                <x-button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
                    Accept Payment
                </x-button>
            </div>
        </form>
    </div>

    {{-- Date Filter & Export Buttons --}}
    <div class="flex flex-col gap-4 mb-4 mr-0 sm:mr-4 sm:flex-row sm:items-center sm:justify-end">
        <div class="flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center">
            <div class="flex flex-row items-center gap-2 w-full">
                <span class="text-gray-600 whitespace-nowrap">From:</span>
                <input type="date" id="datepicker-range-start"
                       class="flex-1 min-w-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5"
                >
                <span class="text-gray-600 whitespace-nowrap">to</span>
                <input type="date" id="datepicker-range-end"
                       class="flex-1 min-w-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5"
                >
            </div>
        </div>
        <div id="export-buttons" class="flex flex-wrap gap-2 items-center w-full sm:w-auto justify-start sm:justify-end">
            {{-- Export buttons will be dynamically injected here --}}
        </div>
        <div class="flex w-full sm:w-auto justify-end">
            <a href="{{ route('admin.payments.monthlyReport') }}" class="w-full sm:w-auto">
                <x-button class="w-full sm:w-auto">
                    View Monthly Report
                </x-button>
            </a>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="overflow-auto">
        <table id="payments-table" class="w-full text-sm text-left row-border text-black">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>Application #</th>
                    <th>OR Number</th>
                    <th>Operator</th>
                    <th>Fees</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th>Manage by</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedPayments as $group)
                <tr>
                    <td>#{{ $group['franchise_application_id'] }}</td>
                    <td>{{ $group['or_no'] ?? '-' }}</td>
                    <td>{{ $group['operator_name'] ?? 'N/A' }}</td>
                    <td>
                        <ul class="list-disc pl-4">
                            @foreach($group['fees'] as $fee)
                            <li>{{ $fee['description'] }} (₱{{ number_format($fee['amount_paid'], 2) }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>₱{{ number_format($group['total_amount'], 2) }}</td>
                    <td>
                        @if($group['paid_at'])
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Paid</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td data-order="{{ $group['paid_at'] ?? '' }}" 
                    data-date="{{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('Y-m-d') : '' }}">
                    {{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('M d, Y h:i A') : '-' }}
                </td>
                                    <td>
                        @if($group['reviewer'])
                        {{ $group['reviewer']->name }}
                        @else
                        <span class="text-gray-400 italic">Not reviewed</span>
                        @endif
                    </td>

                    <td class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                        @if(!$group['paid_at'])
                        <form method="POST" action="{{ route('admin.payments.markPaid', $group['first_payment_id']) }}" class="inline w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs text-center transition-all">
                                Mark as Paid
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.payments.receipt', encrypt($group['first_payment_id'])) }}"
                            class="w-full sm:w-auto px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs text-gray-900 text-center transition-all">
                            View Receipt
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center px-4 py-6 text-gray-500">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.fee-checkbox');
        const totalAmountInput = document.getElementById('total-amount');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.getAttribute('data-amount'));
                }
            });
            totalAmountInput.value = '₱' + total.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));

        // Date filter for DataTables
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let min = $('#datepicker-range-start').val();
        let max = $('#datepicker-range-end').val();

        // Get the hidden date from the 7th column (<td data-date="...">)
        let dateCell = $(settings.aoData[dataIndex].nTr).find('td:eq(6)');
        let rawDate = dateCell.data('date');

        if (!rawDate) return true;

        let paymentDate = new Date(rawDate);
        let minDate = min ? new Date(min) : null;
        let maxDate = max ? new Date(max) : null;

        if ((minDate === null && maxDate === null) ||
            (minDate === null && paymentDate <= maxDate) ||
            (paymentDate >= minDate && maxDate === null) ||
            (paymentDate >= minDate && paymentDate <= maxDate)) {
            return true;
        }
        return false;
    });

        var table = $('#payments-table').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [7, 'asc']
            ],
            columnDefs: [{
                targets: 6, 
                orderable: false,
                searchable: false
            }],
            language: {
                search: "Search payments:",
                lengthMenu: "Show _MENU_ payments per page",
                info: "Showing _START_ to _END_ of _TOTAL_ payments",
                infoEmpty: "Showing 0 to 0 of 0 payments",
                infoFiltered: "(filtered from _MAX_ total payments)",
                zeroRecords: "No payments found",
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
            initComplete: function() {
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
                );
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
                );
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

                $controls.insertBefore($('#payments-table').closest('.overflow-auto, .overflow-x-auto'));

                var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                let $exportDiv = $('#export-buttons');
                if ($exportDiv.length === 0) {
                    $exportDiv = $('<div id="export-buttons" class="flex flex-wrap gap-2 mb-4"></div>');
                    $('#payments-table').before($exportDiv);
                }
                $exportDiv.empty().append(btns);
            }
        });

        // Redraw when date inputs change
        $('#datepicker-range-start, #datepicker-range-end').on('change', function() {
            table.draw();
        });
    });
</script>
 {{-- Include Select2 JS --}}
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script>
     $(document).ready(function() {
         $('.select2').select2({
             width: '100%',
             placeholder: function(){
                 $(this).data('placeholder');
             },
             allowClear: true
         });
     });
 </script>
@endpush
@endsection