@extends('layouts.operator')

@section('content')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Payment Center
</h2>

<div class="max-w-8xl mx-auto space-y-12">

    {{-- Unsettled (Pending) Payments --}}
    @php
    $paidFeeIds = $pendingPayments->pluck('fee_id')
    ->merge($completedPayments->pluck('fee_id'))
    ->unique();

    $availableFees = $fees->reject(fn($fee) => $paidFeeIds->contains($fee->id));
    @endphp

    @if($availableFees->count() > 0)
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-5 flex items-center gap-3">
            <svg class="w-7 h-7 text-primary-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 11-0 14 7 7 0 010-14z" />
            </svg>
            <h3 class="text-xl font-semibold text-primary-navy tracking-wide">Unsettled Fees</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-8">
            @foreach($availableFees as $fee)
            <div class="bg-white rounded-lg p-7 border border-gray-100 shadow-sm hover:shadow transition group relative">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-primary-navy transition">{{ $fee->description }}</h4>
                    <span class="text-2xl font-extrabold text-primary-navy">₱{{ number_format($fee->amount, 2) }}</span>
                </div>
                <p class="text-sm text-gray-500 mb-6">Settle this fee to continue your application process.</p>
                <a href="{{ route('operator.payments.show', $fee) }}"
                    class="w-full inline-flex justify-center items-center px-5 py-2.5 bg-primary-navy text-white text-sm font-bold uppercase tracking-wider rounded-lg shadow hover:bg-primary-gold hover:text-primary-navy border-2 border-primary-navy transition">
                    Pay Now
                </a>
                <div class="absolute top-3 right-3">
                    <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-primary-navy rounded-full font-medium">Unpaid</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Paid Payments --}}
    @if($completedPayments->count() > 0)

    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-5 flex items-center gap-3">
            <svg class="w-7 h-7 text-primary-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h3 class="text-xl font-semibold text-primary-navy tracking-wide">Paid Payments</h3>
        </div>
        <div class="overflow-x-auto px-6 py-6"> {{-- Added padding for breathing room --}}
            <table id="paid-payments-table" class="min-w-full divide-y divide-gray-100 text-sm display">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Application</th>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Fee</th>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Paid Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left font-semibold text-primary-navy uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($completedPayments as $payment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            {{ $payment->franchiseApplication->application_number }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $payment->fee->description }}</td>
                        <td class="px-6 py-4 font-bold text-primary-navy">
                            ₱{{ number_format($payment->amount_paid, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $payment->paid_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-primary-navy rounded-full flex items-center gap-1">
                                <svg class="w-4 h-4 text-primary-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Paid
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('operator.payments.receipt', $payment) }}"
                                class="inline-flex items-center px-4 py-2 bg-primary-navy text-white text-xs font-bold rounded-lg shadow hover:bg-primary-gold hover:text-primary-navy transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9"></path>
                                </svg>
                                View Receipt
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- DataTables JS --}}

    <script>
        $(document).ready(function() {
            $('#paid-payments-table').DataTable({
                "order": [[ 3, "desc" ]], // Order by Paid Date descending
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 5 } // Disable ordering on Action column
                ],
                "initComplete": function() {
                    // Enhance DataTables search box spacing
                    let searchBox = $('#paid-payments-table_filter input');
                    searchBox.addClass('px-4 py-2 rounded border border-gray-300 focus:ring focus:ring-primary-navy/30');
                    $('#paid-payments-table_filter').addClass('mb-4 pl-1');
                }
            });
        });
    </script>
    @endif

    @if($availableFees->count() === 0 && $completedPayments->count() === 0)
    <div class="bg-white border border-gray-200 rounded-xl shadow p-10 flex flex-col items-center justify-center mt-8 mb-8">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h4 class="text-xl font-semibold text-gray-600 mb-2">No Payments Found</h4>
        <p class="text-gray-500">You currently have no unsettled or paid payments.</p>
    </div>
    @endif

</div>
@endsection