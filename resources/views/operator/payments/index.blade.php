@extends('layouts.operator')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">My Payment History</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Application #</th>
                    <th class="px-4 py-2">Fee(s)</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Paid At</th>
                    <th class="px-4 py-2">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedPayments as $group)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $group['group_id'] }}</td>
                        <td class="px-4 py-2">
                            {{ $group['application_number'] ?? '-' }}
                        </td>
                        <td class="px-4 py-2">
                            <ul class="list-disc pl-4">
                                @foreach($group['fees'] as $fee)
                                    <li>{{ $fee['description'] }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">
                            ₱{{ number_format($group['total_amount'], 2) }}
                        </td>
                        <td class="px-4 py-2">
                            @if($group['paid_at'])
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Paid</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ $group['paid_at'] ? $group['paid_at']->format('M d, Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('operator.payments.receipt', $group['first_payment_id']) }}"
                               class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded text-xs text-gray-900 dark:text-white">
                                View Receipt
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-6 text-gray-500 dark:text-gray-400">
                            No payments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
