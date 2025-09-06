@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
    </svg>
    Complete Payment
</h2>
@endsection

@section('content')
<div class="w-full px-0 sm:px-0 lg:px-0 py-8 space-y-8">
    
    <div class="bg-white shadow rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 border-b border-gray-200 px-8 py-5">
            <h3 class="text-xl font-semibold text-primary-navy tracking-wide">Payment Processing</h3>
            <p class="text-gray-600 mt-1">Complete your payment for all outstanding fees</p>
        </div>
        
        <div class="p-8">
            <!-- Payment Summary -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h4>
                <div class="space-y-3">
                    @foreach($fees as $fee)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-900">{{ $fee->description }}</span>
                        <span class="font-bold text-primary-navy">₱{{ number_format($fee->amount, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="border-t pt-3 mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                            <span class="text-2xl font-bold text-primary-navy">₱{{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Application Info -->
            <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-900 mb-2">Application Details</h4>
                <p class="text-sm text-blue-700">
                    <strong>Application:</strong> {{ $application->application_number ?? ('Application #' . $application->id) }}
                </p>
            </div>
            
            <!-- Stripe Payment Form -->
            <div class="space-y-6">
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Secure Payment</h4>
                    <p class="text-gray-600">Complete your payment using the secure form below</p>
                </div>
                
                <!-- Payment Methods Info -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-green-900">Accepted Payment Methods</h4>
                            <p class="text-sm text-green-700 mt-1">
                                Credit Cards, Debit Cards, GrabPay, and PayNow are accepted. 
                                Your payment is processed securely by Stripe.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Stripe Elements Container -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Payment Information
                    </label>
                    <div id="payment-element" class="p-6 border border-gray-300 rounded-lg bg-white shadow-sm">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                </div>
                
                <!-- Error Messages -->
                <div id="payment-message" class="hidden p-4 rounded-lg"></div>
                
                <!-- Payment Button -->
                <div class="flex justify-center pt-4">
                    <button id="submit-payment" 
                            class="px-8 py-4 bg-primary-navy text-white rounded-lg font-bold text-lg tracking-widest hover:bg-primary-navy/90 focus:bg-primary-navy/90 active:bg-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 min-w-[200px]">
                        <span id="button-text" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Pay ₱{{ number_format($totalAmount, 2) }}
                        </span>
                        <span id="spinner" class="hidden flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
                
                <!-- Security Notice -->
                <div class="text-center">
                    <p class="text-xs text-gray-500 flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Secured by Stripe • Your payment information is encrypted
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe Scripts -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    
    // Initialize Stripe Elements
    const elements = stripe.elements({
        clientSecret: '{{ $paymentIntent->client_secret }}',
        appearance: {
            theme: 'stripe',
            variables: {
                colorPrimary: '#1e40af',
                colorBackground: '#ffffff',
                colorText: '#1f2937',
                colorDanger: '#dc2626',
                fontFamily: 'Inter, system-ui, sans-serif',
                spacingUnit: '4px',
                borderRadius: '8px',
            }
        }
    });
    
    // Create payment element
    const paymentElement = elements.create('payment', {
        layout: 'tabs',
        paymentMethodOrder: ['card', 'grabpay', 'paynow']
    });
    
    paymentElement.mount('#payment-element');
    
    // Get form elements
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const paymentMessage = document.getElementById('payment-message');
    
    // Handle form submission
    submitButton.addEventListener('click', async (event) => {
        event.preventDefault();
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        paymentMessage.classList.add('hidden');
        
        try {
            const {error} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{ route("operator.payments.success") }}?payment_intent={{ $paymentIntent->id }}',
                },
                redirect: 'if_required'
            });
            
            if (error) {
                // Show error message
                paymentMessage.classList.remove('hidden');
                paymentMessage.className = 'p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
                paymentMessage.textContent = error.message;
                
                // Reset button state
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                // Payment succeeded, redirect to success page
                window.location.href = '{{ route("operator.payments.success") }}?payment_intent={{ $paymentIntent->id }}';
            }
        } catch (err) {
            console.error('Payment error:', err);
            paymentMessage.classList.remove('hidden');
            paymentMessage.className = 'p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
            paymentMessage.textContent = 'An unexpected error occurred. Please try again.';
            
            // Reset button state
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
    
    // Handle real-time validation errors from the Element
    paymentElement.on('change', function(event) {
        if (event.error) {
            paymentMessage.classList.remove('hidden');
            paymentMessage.className = 'p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
            paymentMessage.textContent = event.error.message;
        } else {
            paymentMessage.classList.add('hidden');
        }
    });
});
</script>
@endsection
