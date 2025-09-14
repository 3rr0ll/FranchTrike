<?php 
namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display all payments for operator
     */
    public function index()
    {
        $operator = Auth::user()->operator;

        $pendingPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->whereNull('paid_at')->with(['fee', 'franchiseApplication'])->get();

        $completedPayments = Payment::whereHas('franchiseApplication', function($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->whereNotNull('paid_at')->with(['fee', 'franchiseApplication'])->latest()->get();

        return view('operator.payments.index', compact('pendingPayments', 'completedPayments'));
    }

    /**
     * Show operator's payment history
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

        if ($payment->franchiseApplication->operator_id !== $operator->operator_id) {
            abort(403);
        }

        return view('operator.payments.receipt', compact('payment'));
    }
}
