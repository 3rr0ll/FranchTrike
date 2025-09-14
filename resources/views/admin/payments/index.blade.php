@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Payments Management</h2>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Payment Form --}}
    <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Accept Payment</h3>
        <form id="payment-form" method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="mb-4">
                <label for="franchise_application_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Franchise Application ID
                </label>
                <input type="text" name="franchise_application_id" id="franchise_application_id" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-800 dark:text-white dark:border-gray-600"
                    placeholder="Enter Franchise Application ID">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Fees to Pay
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($fees as $fee)
                        <div class="flex items-center">
                            <input type="checkbox" name="fees[]" value="{{ $fee->id }}" id="fee_{{ $fee->id }}"
                                class="mr-2 fee-checkbox" data-amount="{{ $fee->amount }}">
                            <label for="fee_{{ $fee->id }}" class="text-gray-800 dark:text-gray-200">
                                {{ $fee->description }} (₱{{ number_format($fee->amount, 2) }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Total Amount
                </label>
                <input type="text" id="total-amount" name="total_amount" readonly
                    class="w-40 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 font-bold"
                    value="₱0.00">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
                Accept Payment
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Operator</th>
                    <th class="px-4 py-2">Application</th>
                    <th class="px-4 py-2">Fees</th>
                    <th class="px-4 py-2">Total Amount</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Paid At</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedPayments as $group)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $group['group_id'] }}</td>
                        <td class="px-4 py-2">{{ $group['operator_name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-2">#{{ $group['franchise_application_id'] }}</td>
                        <td class="px-4 py-2">
                            <ul class="list-disc pl-4">
                                @foreach($group['fees'] as $fee)
                                    <li>{{ $fee['description'] }} (₱{{ number_format($fee['amount_paid'], 2) }})</li>
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
                            {{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('M d, Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
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
                               class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded text-xs text-gray-900 dark:text-white">
                                View Receipt
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center px-4 py-6 text-gray-500 dark:text-gray-400">
                            No payments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Walk-in Payment JS --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.fee-checkbox');
        const totalAmountInput = document.getElementById('total-amount');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.getAttribute('data-amount'));
                }
            });
            totalAmountInput.value = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });
</script>
@endpush

@endsection
