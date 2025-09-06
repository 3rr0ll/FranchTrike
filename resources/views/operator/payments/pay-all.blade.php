@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
    </svg>
    Pay All Fees
</h2>
@endsection

@section('content')
<div class="w-full px-0 sm:px-0 lg:px-0 py-8 space-y-8">
    
    @if($availableFees->count() > 0)
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-5">
            <h3 class="text-xl font-semibold text-primary-navy tracking-wide">Payment Summary</h3>
            <p class="text-gray-600 mt-1">Review and pay all your outstanding fees in one transaction</p>
        </div>
        
        <div class="p-8">
            <!-- Fees List -->
            <div class="space-y-4 mb-8">
                @foreach($availableFees as $fee)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-primary-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium text-gray-900">{{ $fee->description }}</span>
                    </div>
                    <span class="text-lg font-bold text-primary-navy">₱{{ number_format($fee->amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            
            <!-- Total Amount -->
            <div class="bg-primary-navy/5 border border-primary-navy/20 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-primary-navy">Total Amount</h4>
                        <p class="text-sm text-gray-600">{{ $availableFees->count() }} fee(s) to be paid</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-primary-navy">₱{{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Form -->
            <form method="POST" action="{{ route('operator.payments.create-pay-all') }}" class="space-y-6">
                @csrf
                
                <!-- Application Selection -->
                <div>
                    <label for="franchise_application_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Application
                    </label>
                    <select name="franchise_application_id" id="franchise_application_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-navy focus:border-primary-navy @error('franchise_application_id') border-red-500 @enderror" required>
                        <option value="">Choose an application...</option>
                        @foreach($applications as $application)
                        <option value="{{ $application->id }}" 
                                {{ old('franchise_application_id') == $application->id ? 'selected' : '' }}>
                            {{ $application->application_number ?? ('Application #' . $application->id) }}
                            @if($application->status)
                                - {{ ucfirst($application->status) }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('franchise_application_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Payment Method Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900">Payment Method</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                You will be redirected to Stripe to complete your payment securely. 
                                All major credit cards and debit cards are accepted.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('operator.payments.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 flex items-center justify-center px-6 py-3 bg-primary-navy text-white rounded-lg font-bold text-base tracking-widest hover:bg-primary-navy/90 focus:bg-primary-navy/90 active:bg-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Pay ₱{{ number_format($totalAmount, 2) }} Now
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-xl shadow p-10 flex flex-col items-center justify-center">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h4 class="text-xl font-semibold text-gray-600 mb-2">All Fees Paid</h4>
        <p class="text-gray-500">You have no outstanding fees to pay.</p>
        <a href="{{ route('operator.payments.index') }}" 
           class="mt-4 px-6 py-3 bg-primary-navy text-white rounded-lg font-medium hover:bg-primary-navy/90 transition">
            Back to Payments
        </a>
    </div>
    @endif
</div>
@endsection
