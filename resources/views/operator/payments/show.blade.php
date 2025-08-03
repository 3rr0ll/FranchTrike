<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pay {{ $fee->description }}
            </h2>
            <a href="{{ route('operator.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- Fee Details -->
                <div class="mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-medium text-gray-900">{{ $fee->description }}</h3>
                            <span class="text-3xl font-bold text-primary-navy">₱{{ number_format($fee->amount, 2) }}</span>
                        </div>
                        <p class="text-sm text-gray-600">This fee is required for your franchise application to proceed.</p>
                    </div>
                </div>

                <!-- Payment Form -->
                <form action="{{ route('operator.payments.create', $fee) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Application Selection -->
                        <div>
                            <x-label for="franchise_application_id" value="Select Application" />
                            <select name="franchise_application_id" id="franchise_application_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy" required>
                                <option value="">Choose an application</option>
                                @foreach($applications as $application)
                                <option value="{{ $application->id }}">
                                    {{ $application->application_number }} - {{ $application->route->name ?? 'N/A' }} (Status: {{ ucfirst($application->status) }})
                                </option>
                                @endforeach
                            </select>
                            @error('franchise_application_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <x-label for="amount_paid" value="Amount to Pay (₱)" />
                            <x-input id="amount_paid" type="number" step="0.01" min="{{ $fee->amount }}" value="{{ $fee->amount }}" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" disabled />
                            <input type="hidden" name="amount_paid" value="{{ $fee->amount }}" />
                            @error('amount_paid')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Secure Payment</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Your payment will be processed securely through Stripe. We accept all major credit cards and debit cards.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('operator.payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-button type="submit" class="bg-primary-navy hover:bg-primary-navy/90 focus:bg-primary-navy/90 active:bg-primary-navy/90">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Proceed to Payment
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 