@extends('layouts.operator')

@section('title', 'Receipt')


@section('content')
<div id="receipt" class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-8">
    <!-- Header -->
    <div class="flex justify-between items-start border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Payment Receipt</h2>
            <p class="text-sm text-gray-500">
                Date: {{ $payments->first()->paid_at ? $payments->first()->paid_at->format('M d, Y ') : '-' }}
            </p>
        </div>
        <div class="text-right">
            <h3 class="text-lg font-semibold text-gray-900">Application #{{ $payments->first()->franchiseApplication->id ?? 'N/A' }}</h3>
            <h3 class="text-md font-semibold text-gray-900">OR No: {{ $payments->first()->or_no ?? '-' }}</h3>
        </div>
    </div>

    <!-- Operator Info -->
    <div class="mb-6">
        <h4 class="text-md font-semibold text-gray-900 mb-2">Operator Information</h4>
        <p class="text-gray-700">
            {{ $payments->first()->franchiseApplication->operator->first_name ?? '' }}
            {{ $payments->first()->franchiseApplication->operator->middle_initial ? $payments->first()->franchiseApplication->operator->middle_initial . '.' : '' }}
            {{ $payments->first()->franchiseApplication->operator->last_name ?? '' }}
        </p>
    </div>

    <!-- Fees Section -->
    <div class="mb-6">
        <h4 class="text-md font-semibold text-gray-900 mb-3">Payment Details</h4>
        <div class="space-y-3">
            @foreach($payments as $index => $payment)
                <div class="flex justify-between border rounded-lg px-4 py-3 bg-gray-50">
                    <div>
                        <p class="font-medium text-gray-800">{{ $payment->fee->description ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right font-semibold text-gray-900">
                        ₱{{ number_format($payment->amount_paid, 2) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Total -->
    <div class="flex justify-between items-center border-t pt-4 font-bold text-gray-900 text-lg">
        <span>Total</span>
        <span>₱{{ number_format($totalAmount, 2) }}</span>
    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center mt-6">
        <p class="text-sm text-gray-500">This is a system-generated receipt.</p>
        <button onclick="printReceipt()"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
            Print Receipt
        </button>
    </div>
</div>

<script>
function printReceipt() {
    let printContents = document.getElementById('receipt').innerHTML;
    let originalContents = document.body.innerHTML;

    // Create a wrapper div with margin for printing
    let wrapper = document.createElement('div');
    wrapper.style.margin = '40px';
    wrapper.innerHTML = printContents;

    document.body.innerHTML = wrapper.outerHTML;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // reload to restore JS functionality
}
</script>
@endsection
