@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Payment Receipt
</h2>
@endsection

@section('content')
<div class="w-full px-0 sm:px-0 lg:px-0 py-8">
    
    <div class="max-w-4xl mx-auto">
        <!-- Receipt Header -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-primary-navy to-primary-navy/90 px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Payment Receipt</h1>
                        <p class="text-primary-navy/80 mt-1">Transaction completed successfully</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-primary-navy/80">Payment Date</p>
                        <p class="text-lg font-semibold">{{ $paymentDate->format('M d, Y') }}</p>
                        <p class="text-sm text-primary-navy/80">{{ $paymentDate->format('h:i A') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <!-- Application Details -->
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Application Number</p>
                            <p class="font-semibold text-gray-900">{{ $application->application_number ?? ('Application #' . $application->id) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Intent ID</p>
                            <p class="font-mono text-sm text-gray-700">{{ $paymentIntentId }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Summary -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        @foreach($payments as $payment)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->fee->description }}</p>
                                    <p class="text-sm text-gray-500">Fee ID: {{ $payment->fee->id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-primary-navy">₱{{ number_format($payment->amount_paid, 2) }}</p>
                                <p class="text-xs text-green-600 font-medium">✓ Paid</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Total Amount -->
                <div class="bg-primary-navy/5 border border-primary-navy/20 rounded-lg p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xl font-semibold text-primary-navy">Total Amount Paid</h4>
                            <p class="text-sm text-gray-600">{{ $payments->count() }} fee(s) processed</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-primary-navy">₱{{ number_format($totalAmount, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900">Payment Method</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                This payment was processed securely through Stripe. 
                                All fees have been successfully paid and recorded.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('operator.payments.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Back to Payments
                    </a>
                    <button onclick="window.print()" 
                            class="px-6 py-3 bg-primary-navy text-white rounded-lg font-medium hover:bg-primary-navy/90 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .bg-gradient-to-r { background: #1e40af !important; }
    .shadow-xl { box-shadow: none !important; }
    .border { border: 1px solid #000 !important; }
}
</style>
@endsection
