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
            <thead>
                <tr>
                    <th>Application #</th>
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
                            <a href="{{ route('operator.payments.receipt', $group['first_payment_id']) }}"
                               class="px-3 py-1 bg-primary-navy  rounded text-s text-white">
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
        $('#paymentHistoryTable').DataTable({
            responsive: true,
            dom: 'Blfrtip',
            order: [[5, 'desc']], // sort by "Paid At"
            pageLength: 10
        });
    });
</script>
@endpush
