@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
        Payment Receipt
    </h2>

    <p class="text-gray-700 dark:text-gray-300 mb-2">
        <strong>Application ID:</strong> #{{ $payments->first()->franchise_application_id }}
    </p>
    <p class="text-gray-700 dark:text-gray-300 mb-2">
        <strong>Operator:</strong> {{ $payments->first()->franchiseApplication->operator->name ?? 'N/A' }}
    </p>
    <p class="text-gray-700 dark:text-gray-300 mb-4">
        <strong>Paid At:</strong> {{ $payments->first()->paid_at->format('M d, Y H:i') }}
    </p>

    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            <tr>
                <th class="px-4 py-2">Fee</th>
                <th class="px-4 py-2">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">{{ $payment->fee->description ?? 'N/A' }}</td>
                    <td class="px-4 py-2">₱{{ number_format($payment->amount_paid, 2) }}</td>
                </tr>
            @endforeach
            <tr class="font-bold">
                <td class="px-4 py-2 text-right">Total:</td>
                <td class="px-4 py-2">₱{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <button onclick="window.print()" 
        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
        Print Receipt
    </button>
</div>
@endsection
