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
        })
        ->whereNull('paid_at')
        ->whereNotNull('stripe_payment_status')
        ->where('stripe_payment_status', '!=', 'cancelled')
        ->with(['fee', 'franchiseApplication'])->get();

        $cancelledPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })
        ->whereNull('paid_at')
        ->where(function ($q) {
            $q->whereNull('stripe_payment_status')
              ->orWhere('stripe_payment_status', 'cancelled');
        })
        ->with(['fee', 'franchiseApplication'])->get();
        
        $completedPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->whereNotNull('paid_at')->with(['fee', 'franchiseApplication'])->latest()->get();
        
        return view('operator.payments.index', compact('fees', 'pendingPayments', 'cancelledPayments', 'completedPayments'));
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
                $payments = Payment::where('stripe_payment_intent_id', $paymentIntentId)->get();
                
                if ($payments->isNotEmpty()) {
                    // Update all payments with the same payment intent
                    $payments->each(function ($payment) {
                        $payment->update([
                            'paid_at' => now(),
                            'stripe_payment_status' => 'succeeded',
                        ]);
                    });
                    
                    Log::info('Payments updated successfully', [
                        'payment_count' => $payments->count(),
                        'payment_intent_id' => $paymentIntentId
                    ]);
                    
                    // Check if this was a Pay All payment
                    $isPayAll = $payments->count() > 1 || 
                               ($paymentIntent->metadata['payment_type'] ?? '') === 'pay_all';
                    
                    if ($isPayAll) {
                        // Redirect to Pay All receipt
                        return redirect()->route('operator.payments.pay-all.receipt', $paymentIntentId)
                            ->with('success', "All payments completed successfully! ({$payments->count()} fees paid)");
                    } else {
                        // Single payment - redirect to regular receipt
                        $singlePayment = $payments->first();
                        return redirect()->route('operator.payments.receipt', $singlePayment)
                            ->with('success', 'Payment completed successfully!');
                    }
                } else {
                    Log::error('Payment records not found', ['payment_intent_id' => $paymentIntentId]);
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
     * Resume a cancelled payment by creating a new intent and returning to process page
     */
    public function resume(Request $request, Payment $payment)
    {
        $operator = Auth::user()->operator;
        if ($payment->franchiseApplication->operator_id !== $operator->operator_id) {
            abort(403);
        }

        if ($payment->paid_at) {
            return redirect()->route('operator.payments.index')->with('success', 'This payment was already completed.');
        }

        try {
            $intent = PaymentIntent::create([
                'amount' => $payment->amount_paid * 100,
                'currency' => 'php',
                'metadata' => [
                    'fee_id' => $payment->fee_id,
                    'franchise_application_id' => $payment->franchise_application_id,
                    'operator_id' => $operator->operator_id,
                    'resumed_from_payment_id' => $payment->id,
                ],
            ]);

            $payment->update([
                'stripe_payment_intent_id' => $intent->id,
                'stripe_payment_status' => null,
            ]);

            $fee = $payment->fee;
            $application = $payment->franchiseApplication;

            return view('operator.payments.process', [
                'paymentIntent' => $intent,
                'payment' => $payment,
                'fee' => $fee,
                'application' => $application,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to resume payment: ' . $e->getMessage()]);
        }
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

    /**
     * Show Pay All page with all unpaid fees
     */
    public function payAll()
    {
        $operator = Auth::user()->operator;
        $fees = Fee::where('is_active', true)->get();
        
        // Get all franchise applications for this operator
        $applications = FranchiseApplication::where('operator_id', $operator->operator_id)->latest()->get();
        
        // Get paid fee IDs to exclude them
        $paidFeeIds = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })
        ->whereNotNull('paid_at')
        ->pluck('fee_id')
        ->unique();
        
        // Get available fees (not yet paid) - includes both unpaid and cancelled
        $availableFees = $fees->reject(fn($fee) => $paidFeeIds->contains($fee->id));
        
        // Calculate total amount
        $totalAmount = $availableFees->sum('amount');
        
        return view('operator.payments.pay-all', compact('availableFees', 'applications', 'totalAmount'));
    }

    /**
     * Create payment intent for all unpaid fees
     */
    public function createPayAllPayment(Request $request)
    {
        $operator = Auth::user()->operator;
        $fees = Fee::where('is_active', true)->get();
        
        // Get paid fee IDs to exclude them
        $paidFeeIds = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })
        ->whereNotNull('paid_at')
        ->pluck('fee_id')
        ->unique();
        
        // Get available fees (not yet paid) - includes both unpaid and cancelled
        $availableFees = $fees->reject(fn($fee) => $paidFeeIds->contains($fee->id));
        
        if ($availableFees->isEmpty()) {
            return redirect()->route('operator.payments.index')
                ->with('info', 'No unpaid fees found.');
        }
        
        $request->validate([
            'franchise_application_id' => 'required|exists:franchise_applications,id',
        ]);
        
        // Verify the application belongs to the operator
        $application = FranchiseApplication::where('id', $request->franchise_application_id)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();
        
        $totalAmount = $availableFees->sum('amount');
        
        try {
            // Create payment intent with Stripe for the total amount
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount * 100, // Convert to cents
                'currency' => 'php',
                'metadata' => [
                    'operator_id' => $operator->operator_id,
                    'franchise_application_id' => $application->id,
                    'payment_type' => 'pay_all',
                    'fee_count' => $availableFees->count(),
                ],
            ]);
            
            Log::info('Pay All payment intent created', [
                'payment_intent_id' => $paymentIntent->id,
                'total_amount' => $totalAmount,
                'fee_count' => $availableFees->count(),
                'application_id' => $application->id
            ]);

            // Handle payment records - update existing or create new
            $payments = [];
            foreach ($availableFees as $fee) {
                // Check if there's an existing payment record for this fee
                $existingPayment = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
                    $query->where('operator_id', $operator->operator_id);
                })
                ->where('fee_id', $fee->id)
                ->whereNull('paid_at')
                ->first();
                
                if ($existingPayment) {
                    // Update existing payment record
                    $existingPayment->update([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount_paid' => $fee->amount,
                    ]);
                    $payments[] = $existingPayment;
                } else {
                    // Create new payment record
                    $payment = Payment::create([
                        'franchise_application_id' => $application->id,
                        'fee_id' => $fee->id,
                        'amount_paid' => $fee->amount,
                        'stripe_payment_intent_id' => $paymentIntent->id,
                    ]);
                    $payments[] = $payment;
                }
            }

            return view('operator.payments.process-pay-all', [
                'paymentIntent' => $paymentIntent,
                'payments' => $payments,
                'fees' => $availableFees,
                'application' => $application,
                'totalAmount' => $totalAmount,
            ]);

        } catch (\Exception $e) {
            Log::error('Pay All payment setup failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Payment setup failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show Pay All receipt for multiple payments
     */
    public function payAllReceipt($paymentIntentId)
    {
        $operator = Auth::user()->operator;
        
        // Get all payments with this payment intent ID
        $payments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })
        ->where('stripe_payment_intent_id', $paymentIntentId)
        ->whereNotNull('paid_at')
        ->with(['fee', 'franchiseApplication'])
        ->get();
        
        if ($payments->isEmpty()) {
            abort(404, 'Payment receipt not found.');
        }
        
        // Get the first payment to get common details
        $firstPayment = $payments->first();
        $application = $firstPayment->franchiseApplication;
        $totalAmount = $payments->sum('amount_paid');
        $paymentDate = $firstPayment->paid_at;
        
        return view('operator.payments.pay-all-receipt', compact('payments', 'application', 'totalAmount', 'paymentDate', 'paymentIntentId'));
    }
} 