@extends('layouts.operator')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Receipt</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Date: {{ $payments->first()->paid_at ? $payments->first()->paid_at->format('M d, Y h:i A') : '-' }}
            </p>
        </div>
        <div class="text-right">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application #</h3>
            <p class="text-gray-700 dark:text-gray-300">
                {{ $payments->first()->franchiseApplication->id ?? 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Operator Info -->
    <div class="mb-6">
        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Operator Information</h4>
        <p class="text-gray-700 dark:text-gray-300">
            {{ $payments->first()->franchiseApplication->operator->first_name ?? '' }}
            {{ $payments->first()->franchiseApplication->operator->middle_initial ? $payments->first()->franchiseApplication->operator->middle_initial . '.' : '' }}
            {{ $payments->first()->franchiseApplication->operator->last_name ?? '' }}
        </p>
    </div>

    <!-- Fees Table -->
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">#</th>
                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Fee Description</th>
                    <th class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $index => $payment)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $payment->fee->description ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-right">₱{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t border-gray-300 dark:border-gray-600 font-semibold">
                    <td colspan="2" class="px-4 py-2 text-right">Total</td>
                    <td class="px-4 py-2 text-right">₱{{ number_format($totalAmount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer / Print -->
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is a system-generated receipt.</p>
        <button onclick="window.print()"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
            Print Receipt
        </button>
    </div>
</div>
@endsection
