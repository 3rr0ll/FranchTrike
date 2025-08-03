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

        return redirect()->route('superadmin.payments.index')
            ->with('success', 'Fee deleted successfully!');
    }

    /**
     * Display payments management
     */
    public function payments()
    {
        $payments = Payment::with(['fee', 'franchiseApplication.operator'])
            ->latest()
            ->paginate(15);
        
        $fees = Fee::where('is_active', true)->get();
        $applications = FranchiseApplication::with('operator')->get();
        
        return view('superadmin.payments.payments', compact('payments', 'fees', 'applications'));
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

        return redirect()->route('superadmin.payments.payments')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Delete payment record
     */
    public function destroyPayment(Payment $payment)
    {
        $payment->delete();

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