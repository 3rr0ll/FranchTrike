@extends('layouts.operator')

@section('title', 'Payments')


@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
     Payment History
    </h2>
@endsection

@section('content')
<div class="w-full mx-auto p-6 bg-white rounded-lg shadow">

    <div class="overflow-x-auto">
        <table id="paymentHistoryTable" class="display-full row-border">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>Application #</th>
                    <th>OR Number</th>
                    <th>Fee(s)</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedPayments as $group)
                    <tr>
                        <td>{{ $group['application_id'] ?? '-' }}</td>
                        <td>{{ $group['or_no'] ?? '-' }}</td>
                        <td>
                            <ul class="list-disc pl-4">
                                @foreach($group['fees'] as $fee)
                                    <li>{{ $fee['description'] }}</li>
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
                        <td>{{ $group['paid_at'] ? $group['paid_at']->format('M d, Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('operator.payments.receipt', encrypt($group['first_payment_id'])) }}"
                               class="block sm:inline-block w-full sm:w-auto px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs text-gray-900 text-center transition-all whitespace-nowrap">
                                View Receipt
                            </a>
                        </td>
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
        var table = $('#paymentHistoryTable').DataTable({
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
            initComplete: function() {
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
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

                $controls.insertBefore($('#paymentHistoryTable').closest('.overflow-x-auto'));
            }
        });
    });
</script>
@endpush
