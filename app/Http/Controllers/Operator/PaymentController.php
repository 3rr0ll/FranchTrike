<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the operator's grouped payments.
     */
    public function index()
    {
        $operator = auth()->user()->operator;

        $payments = Payment::with(['fee', 'franchiseApplication'])
            ->whereHas('franchiseApplication', function ($q) use ($operator) {
                $q->where('operator_id', $operator->operator_id); // 👈 link via operator_id
            })
            ->orderByDesc('created_at')
            ->get();

        // Group payments by franchise_application_id + paid_at
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
                'application_number' => $first->franchiseApplication->application_number ?? 'N/A',
                'fees' => $group->map(function ($payment) {
                    return [
                        'description' => $payment->fee->description ?? 'N/A',
                        'amount_paid' => $payment->amount_paid,
                    ];
                })->toArray(),
                'total_amount' => $group->sum('amount_paid'),
                'paid_at' => $first->paid_at,
            ];
        }

        return view('operator.payments.index', compact('groupedPayments'));
    }


    /**
     * Show a receipt for a specific payment group.
     */
    public function receipt(Payment $payment)
    {
        $operator = auth()->user()->operator;
    
        // Ensure the payment belongs to this operator
        if ($payment->franchiseApplication->operator_id !== $operator->operator_id) {
            abort(403, 'Unauthorized');
        }
    
        // Pull all payments from the same batch (same application + same paid_at)
        $payments = Payment::with(['fee', 'franchiseApplication'])
            ->where('franchise_application_id', $payment->franchise_application_id)
            ->where('paid_at', $payment->paid_at)
            ->get();
    
        $totalAmount = $payments->sum('amount_paid');
    
        return view('operator.payments.receipt', compact('payments', 'totalAmount'));
    }
    
}
