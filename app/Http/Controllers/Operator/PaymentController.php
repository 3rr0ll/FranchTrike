<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\FranchiseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret) {
            throw new \Exception('Stripe secret key not configured');
        }
        Stripe::setApiKey($stripeSecret);
    }

    /**
     * Display the payment page for operators
     */
    public function index()
    {
        $operator = Auth::user()->operator;
        $fees = Fee::where('is_active', true)->get();
        $pendingPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->whereNull('paid_at')->with(['fee', 'franchiseApplication'])->get();
        
        $completedPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->whereNotNull('paid_at')->with(['fee', 'franchiseApplication'])->latest()->take(10)->get();
        
        return view('operator.payments.index', compact('fees', 'pendingPayments', 'completedPayments'));
    }

    /**
     * Show the payment form for a specific fee
     */
    public function show(Fee $fee)
    {
        $operator = Auth::user()->operator;
        $applications = FranchiseApplication::where('operator_id', $operator->operator_id)->latest()->get();

        return view('operator.payments.show', compact('fee', 'applications'));
    }

    /**
     * Create payment intent and redirect to payment page
     */
    public function createPayment(Request $request, Fee $fee)
    {
        $request->validate([
            'franchise_application_id' => 'required|exists:franchise_applications,id',
            'amount_paid' => 'required|numeric|min:' . $fee->amount,
        ]);

        $operator = Auth::user()->operator;
        
        // Verify the application belongs to the operator
        $application = FranchiseApplication::where('id', $request->franchise_application_id)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();

        try {
            // Create payment intent with Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount_paid * 100, // Convert to cents
                'currency' => 'php',
                'metadata' => [
                    'fee_id' => $fee->id,
                    'franchise_application_id' => $application->id,
                    'operator_id' => $operator->operator_id,
                ],
            ]);
            
            Log::info('Payment intent created', [
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $request->amount_paid,
                'fee_id' => $fee->id,
                'application_id' => $application->id
            ]);

            // Create payment record
            $payment = Payment::create([
                'franchise_application_id' => $application->id,
                'fee_id' => $fee->id,
                'amount_paid' => $request->amount_paid,
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return view('operator.payments.process', [
                'paymentIntent' => $paymentIntent,
                'payment' => $payment,
                'fee' => $fee,
                'application' => $application,
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment setup failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent') ?? $request->query('payment_intent');
        
        Log::info('Payment success called', [
            'payment_intent_id' => $paymentIntentId,
            'request_data' => $request->all()
        ]);
        
        if (!$paymentIntentId) {
            Log::error('Payment intent not found in request');
            return redirect()->route('operator.payments.index')
                ->with('error', 'Payment intent not found.');
        }
        
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            Log::info('Payment intent retrieved', [
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
                
                if ($payment) {
                    $payment->update([
                        'paid_at' => now(),
                        'stripe_payment_status' => 'succeeded',
                    ]);
                    
                    Log::info('Payment updated successfully', ['payment_id' => $payment->id]);
                    
                    return redirect()->route('operator.payments.index')
                        ->with('success', 'Payment completed successfully!');
                } else {
                    Log::error('Payment record not found', ['payment_intent_id' => $paymentIntentId]);
                }
            }
            
            Log::warning('Payment verification failed', ['status' => $paymentIntent->status]);
            return redirect()->route('operator.payments.index')
                ->with('error', 'Payment verification failed.');
                
        } catch (\Exception $e) {
            Log::error('Payment verification exception', ['error' => $e->getMessage()]);
            return redirect()->route('operator.payments.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        
        // Update payment status to cancelled
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
        if ($payment) {
            $payment->update([
                'stripe_payment_status' => 'cancelled',
            ]);
        }
        
        return redirect()->route('operator.payments.index')
            ->with('error', 'Payment was cancelled.');
    }

    /**
     * Show payment history
     */
    public function history()
    {
        $operator = Auth::user()->operator;
        $payments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->with(['fee', 'franchiseApplication'])->latest()->paginate(15);
        
        return view('operator.payments.history', compact('payments'));
    }

    /**
     * Show payment receipt
     */
    public function receipt(Payment $payment)
    {
        $operator = Auth::user()->operator;
        
        // Verify the payment belongs to the operator
        if ($payment->franchiseApplication->operator_id !== $operator->operator_id) {
            abort(403);
        }
        
        return view('operator.payments.receipt', compact('payment'));
    }
} 