@extends('layouts.operator')



@section('content')

    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- Payment Summary -->
                <div class="mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fee:</span>
                                <span class="font-medium">{{ $fee->description }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Application:</span>
                                <span class="font-medium">{{ $application->application_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-bold text-primary-navy">₱{{ number_format($payment->amount_paid, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stripe Payment Form -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                    <form id="payment-form">
                        <div class="space-y-4">
                            <!-- Card Element -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Card Information</label>
                                <div id="card-element" class="border border-gray-300 rounded-md p-3 focus-within:ring-2 focus-within:ring-primary-navy focus-within:border-primary-navy">
                                    <!-- Stripe Elements will be inserted here -->
                                </div>
                                <div id="card-errors" class="text-red-600 text-sm mt-2" role="alert"></div>
                            </div>

                            <!-- Payment Button -->
                            <button type="submit" id="submit-button" class="w-full inline-flex justify-center items-center px-4 py-3 bg-primary-navy border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-navy/90 focus:bg-primary-navy/90 active:bg-primary-navy/90 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 transition ease-in-out duration-150">
                                <span id="button-text">Pay ₱{{ number_format($payment->amount_paid, 2) }}</span>
                                <div id="spinner" class="hidden ml-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </button>
                            <a href="{{ route('operator.payments.index') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-3">
                                
                                Cancel Payment
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Security Notice -->
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Secure Payment</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Your payment information is encrypted and secure. We never store your card details.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Stripe Scripts -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements();

        // Create card element
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });

        // Mount card element
        card.mount('#card-element');

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Disable button and show spinner
            submitButton.disabled = true;
            buttonText.style.display = 'none';
            spinner.classList.remove('hidden');

            const { paymentIntent, error } = await stripe.confirmCardPayment('{{ $paymentIntent->client_secret }}', {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: '{{ Auth::user()->name }}',
                    },
                },
            });

            if (error) {
                // Show error message
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                console.error('Payment error:', error);

                // Re-enable button
                submitButton.disabled = false;
                buttonText.style.display = 'inline';
                spinner.classList.add('hidden');
            } else {
                // Payment successful
                console.log('Payment intent:', paymentIntent);
                if (paymentIntent.status === 'succeeded') {
                    // Redirect to success page
                    const successUrl = '{{ route("operator.payments.success") }}?payment_intent=' + paymentIntent.id;
                    console.log('Redirecting to:', successUrl);
                    window.location.href = successUrl;
                } else {
                    console.log('Payment not succeeded, status:', paymentIntent.status);
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = 'Payment was not completed successfully.';

                    // Re-enable button
                    submitButton.disabled = false;
                    buttonText.style.display = 'inline';
                    spinner.classList.add('hidden');
                }
            }
        });

        // Handle card errors
        card.addEventListener('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.textContent = error.message;
            } else {
                displayError.textContent = '';
            }
        });
    </script>
@endpush