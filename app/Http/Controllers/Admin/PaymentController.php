<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\FranchiseApplication;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show payments list + create form
     * Now returns grouped payments for the view.
     */
    public function index()
    {
        $fees = Fee::all();

        // Get all payments with related data
        $payments = Payment::with(['fee', 'franchiseApplication.operator', 'reviewer'])
            ->orderByDesc('created_at')
            ->get();

        // Group payments by franchise_application_id and paid_at (to the minute, or 'pending')
        $paymentsGrouped = $payments->groupBy(function ($payment) {
            $paidAtKey = $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : 'pending';
            return $payment->franchise_application_id . '|' . $paidAtKey;
        });

        $groupedPayments = [];
        $groupId = 1;

        foreach ($paymentsGrouped as $key => $group) {
            $first = $group->first();
            $groupedPayments[] = [
                'group_id' => $groupId++,
                'first_payment_id' => $first->id,
                'franchise_application_id' => $first->franchise_application_id,
                'or_no' => $first->or_no,
                'operator_name' => isset($first->franchiseApplication->operator)
                    ? trim(
                        $first->franchiseApplication->operator->first_name . ' ' .
                            ($first->franchiseApplication->operator->middle_initial ? $first->franchiseApplication->operator->middle_initial . '. ' : '') .
                            $first->franchiseApplication->operator->last_name
                    )
                    : 'N/A',
                'fees' => $group->map(function ($payment) {
                    return [
                        'description' => $payment->fee->description ?? 'N/A',
                        'amount_paid' => $payment->amount_paid,
                    ];
                })->toArray(),
                'total_amount' => $group->sum('amount_paid'),
                'paid_at' => $first->paid_at,
                'reviewer' => $first->reviewer,
            ];
        }

        return view('admin.payments.index', compact('fees', 'groupedPayments'));
    }

    /**
     * Store a new walk-in payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'franchise_application_id' => 'required|exists:franchise_applications,id',
            'fees' => 'required|array',
            'fees.*' => 'exists:fees,id',
        ]);

        $application = FranchiseApplication::findOrFail($request->franchise_application_id);
        $fees = Fee::whereIn('id', $request->fees)->get();
        $lastOrNo = Payment::max('or_no') ?? 1000;

        $userId = Auth::id();

        $paidAt = now();

        $newOrNo = $lastOrNo + 1;

        $createdPayments = [];

        foreach ($fees as $fee) {
            $payment = Payment::create([
                'franchise_application_id' => $application->id,
                'fee_id' => $fee->id,
                'amount_paid' => $fee->amount,
                'paid_at' => $paidAt, // Use the same timestamp for all
                'reviewed_by' => $userId, 
                'or_no' => $newOrNo, // Use the same OR number for all
            ]);

            $createdPayments[] = $payment;

            ActivityLogger::log(
                'payment',
                'created',
                'Payment of ₱' . $payment->amount_paid . '.',
                ['payment_id' => $payment->id, 'reviewed_by' => $userId, 'or_no' => $payment->or_no]
            );
        }

        // Apply encryption: encrypt the first payment id for the session (optional for future needs)
        $encryptedFirstPaymentId = isset($createdPayments[0]) ? encrypt($createdPayments[0]->id) : null;

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully.')
            ->with('encrypted_payment_id', $encryptedFirstPaymentId);
    }

    /**
     * Show a receipt for a payment
     */
    public function receipt($encryptedPaymentId)
    {
        // Decrypt the payment ID
        try {
            $paymentId = decrypt($encryptedPaymentId);
        } catch (\Exception $e) {
            abort(404, 'Invalid payment reference.');
        }

        // Fetch payment using the decrypted ID
        $payment = Payment::findOrFail($paymentId);

        // Group by the same application and the same paid_at timestamp
        $payments = Payment::with('fee', 'franchiseApplication.operator', 'reviewer')
            ->where('franchise_application_id', $payment->franchise_application_id)
            ->where('paid_at', $payment->paid_at) 
            ->get();

        // Calculate total
        $totalAmount = $payments->sum('amount_paid');

        // Get the reviewer (assume all payments in the batch have the same reviewer)
        $reviewedBy = $payments->first() ? $payments->first()->reviewer : null;

        return view('admin.payments.receipt', compact('payments', 'totalAmount', 'reviewedBy'));
    }

    public function monthlyReport(Request $request)
    {
        // Get optional year filter (default to current year)
        $year = $request->input('year', now()->year);
    
        // Fetch all payments for that year, eager loading related data
        $payments = Payment::with(['franchiseApplication.route'])
            ->whereYear('paid_at', $year)
            ->get();
    
        // Group payments by route name
        $paymentsByRoute = $payments->groupBy(function ($payment) {
            return $payment->franchiseApplication->route->name ?? 'Unknown';
        });
    
        $reportData = [];
    
        foreach ($paymentsByRoute as $routeName => $routePayments) {
            $monthlyTotals = array_fill_keys([
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ], 0);
    
            foreach ($routePayments as $payment) {
                if ($payment->paid_at) {
                    $month = $payment->paid_at->format('M'); 
                    $monthlyTotals[$month] += $payment->amount_paid;
                }
            }
    
            $reportData[$routeName] = $monthlyTotals;
        }
    
        // Compute overall totals
        $overall = array_fill_keys([
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ], 0);
    
        foreach ($reportData as $months) {
            foreach ($months as $month => $amount) {
                $overall[$month] += $amount;
            }
        }
    
        $grandTotal = array_sum($overall);
    
        return view('admin.payments.monthly-report', [
            'monthlyData' => $reportData,
            'overall' => $overall,
            'grandTotal' => $grandTotal,
            'year' => $year
        ]);
    }
    
}
