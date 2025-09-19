<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\FranchiseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of fees
     */
    public function index()
    {
        $fees = Fee::withCount('payments')->latest()->get();
        $payments = Payment::with(['fee', 'franchiseApplication.operator'])
            ->latest()
            ->paginate(15);
        
        $totalFees = $fees->sum('amount');
        $totalPayments = $payments->sum('amount_paid');
        $pendingPayments = Payment::whereNull('paid_at')->count();
        
        return view('superadmin.payments.index', compact('fees', 'payments', 'totalFees', 'totalPayments', 'pendingPayments'));
    }

    /**
     * Show the form for creating a new fee
     */
    public function create()
    {
        return view('superadmin.payments.create');
    }

    /**
     * Store a newly created fee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        Fee::create($validated);
        $createdFee = \App\Models\Fee::where($validated)->latest()->first();

        \App\Helpers\ActivityLogger::log(
            'fee',
            'Super Admin created new fee',
            'Fee "' . $validated['description'] . '" with amount ₱' . $validated['amount'] . ' was created.',
            ['fee_id' => $createdFee ? $createdFee->id : null]
        );

        return redirect()->route('superadmin.payments.index')
            ->with('success', 'Fee created successfully!');
    }

    /**
     * Show the form for editing a fee
     */
    public function edit(Fee $fee)
    {
        return view('superadmin.payments.edit', compact('fee'));
    }

    /**
     * Update the specified fee
     */
    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $fee->update($validated);

        \App\Helpers\ActivityLogger::log(
            'fee',
            'Super Admin updated a fee',
            'Fee "' . $fee->description . '" (ID: ' . $fee->id . ') was updated to ₱' . $fee->amount . '.',
            ['fee_id' => $fee->id]
        );

        return redirect()->route('superadmin.payments.index')
            ->with('success', 'Fee updated successfully!');
    }

    /**
     * Remove the specified fee
     */
    public function destroy(Fee $fee)
    {
        // Check if fee has associated payments
        if ($fee->payments()->count() > 0) {
            return redirect()->route('superadmin.payments.index')
                ->with('error', 'Cannot delete fee with associated payments.');
        }

        $fee->delete();
        \App\Helpers\ActivityLogger::log(
            'fee',
            'Super Admin deleted a fee',
            'Fee "' . $fee->description . '" (ID: ' . $fee->id . ') was deleted.',
            ['fee_id' => $fee->id]
        );

        return redirect()->route('superadmin.payments.index')
            ->with('success', 'Fee deleted successfully!');
    }

    /**
     * Display payments management
     */
    public function payments()
    {
        $fees = Fee::withCount('payments')->latest()->get();

    // Get all payments with related data
    $payments = Payment::with(['fee', 'franchiseApplication.operator'])
        ->orderByDesc('created_at')
        ->get();

    // Group payments by application + paid_at timestamp (or "pending")
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
            'application_number' => $first->franchise_application_id ?? 'N/A',
            'operator_name' => $first->franchiseApplication && $first->franchiseApplication->operator
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

    // Extra stats
    $totalFees = $fees->sum('amount');
    $totalPayments = $payments->sum('amount_paid');
    $pendingPayments = Payment::whereNull('paid_at')->count();

    return view('superadmin.payments.payments', compact(
        'fees',
        'groupedPayments',
        'totalFees',
        'totalPayments',
        'pendingPayments'
    ));
    }

    /**
     * Create a new payment record
     */
    public function createPayment(Request $request)
    {
        $validated = $request->validate([
            'franchise_application_id' => 'required|exists:franchise_applications,id',
            'fee_id' => 'required|exists:fees,id',
            'amount_paid' => 'required|numeric|min:0',
            'paid_at' => 'nullable|date'
        ]);

        Payment::create($validated);

        return redirect()->route('superadmin.payments.payments')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Update payment record
     */
    public function updatePayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'franchise_application_id' => 'required|exists:franchise_applications,id',
            'fee_id' => 'required|exists:fees,id',
            'amount_paid' => 'required|numeric|min:0',
            'paid_at' => 'nullable|date'
        ]);

        $payment->update($validated);

        \App\Helpers\ActivityLogger::log(
            'Super Admin updated a payment',
            'updated',
            'Super Admin updated payment of ₱' . $payment->amount_paid . '.',
            ['payment_id' => $payment->id]
        );

        return redirect()->route('superadmin.payments.payments')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Delete payment record
     */
    public function destroyPayment(Payment $payment)
    {
        $payment->delete();

        \App\Helpers\ActivityLogger::log(
            'payment',
            'deleted',
            'Super Admin deleted a payment record.',
            ['payment_id' => $payment->id]
        );

        return redirect()->route('superadmin.payments.payments')
            ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Show payment statistics
     */
    public function statistics()
    {
        $totalFees = Fee::sum('amount');
        $totalPayments = Payment::sum('amount_paid');
        $pendingPayments = Payment::whereNull('paid_at')->count();
        $completedPayments = Payment::whereNotNull('paid_at')->count();
        
        $monthlyPayments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount_paid) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();
        
        $topFees = Fee::withCount('payments')
            ->orderBy('payments_count', 'desc')
            ->take(5)
            ->get();
        
        return view('superadmin.payments.statistics', compact(
            'totalFees', 
            'totalPayments', 
            'pendingPayments', 
            'completedPayments',
            'monthlyPayments',
            'topFees'
        ));
    }
} 