@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Payment Management
</h2>
@endsection

@section('content')
<div class="p-6 ">


    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
    @endif

    {{-- Payment Form --}}
    <div class="mb-8 p-6 bg-gray-50 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Accept Payment</h3>
        <form id="payment-form" method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="mb-4">
                <label for="franchise_application_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Franchise Application ID
                </label>
                <input type="text" name="franchise_application_id" id="franchise_application_id" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                    placeholder="Enter Franchise Application ID">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Fees to Pay
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($fees as $fee)
                    <div class="flex items-center">
                        <input type="checkbox" name="fees[]" value="{{ $fee->id }}" id="fee_{{ $fee->id }}"
                            class="mr-2 fee-checkbox" data-amount="{{ $fee->amount }}">
                        <label for="fee_{{ $fee->id }}" class="text-gray-800">
                            {{ $fee->description }} (₱{{ number_format($fee->amount, 2) }})
                        </label>
                    </div>
                    @endforeach
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
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 mb-4 mr-4">
        <div class="flex items-center gap-2">
            <span class="text-gray-600">From:</span>
            <input type="date" id="datepicker-range-start"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
            <span class="text-gray-600">to</span>
            <input type="date" id="datepicker-range-end"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5">
        </div>
        <div id="export-buttons" class="flex flex-wrap gap-2 items-center">
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="overflow-x-auto bg-white p-4 rounded-md">
        <table id="payments-table" class="w-full text-sm text-left row-border text-black">
            <thead class="bg-gray-50 text-black">
                <tr>
                    <th>Application #</th>
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
                    <td>{{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('M d, Y') : '-' }}</td>
                    <td>
                        @if($group['reviewer'])
                        {{ $group['reviewer']->name }}
                        @else
                        <span class="text-gray-400 italic">Not reviewed</span>
                        @endif
                    </td>

                    <td>
                        @if(!$group['paid_at'])
                        <form method="POST" action="{{ route('admin.payments.markPaid', $group['first_payment_id']) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">
                                Mark as Paid
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.payments.receipt', $group['first_payment_id']) }}"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs text-gray-900">
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
            let date = data[6]; // Paid At column index

            if (!date) return true;

            let paymentDate = new Date(date);

            if (
                (min === "" && max === "") ||
                (min === "" && paymentDate <= new Date(max)) ||
                (new Date(min) <= paymentDate && max === "") ||
                (new Date(min) <= paymentDate && paymentDate <= new Date(max))
            ) {
                return true;
            }
            return false;
        });

        // Initialize DataTable
        var table = $('#payments-table').DataTable({
            // Show entries per page dropdown by including 'l' in the dom option
            dom: 'Blfrtip',
            buttons: [{
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
            order: [],
            initComplete: function() {
                // Move export buttons to a custom div container
                var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                // Create or select a div to hold the buttons
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
@endpush
@endsection