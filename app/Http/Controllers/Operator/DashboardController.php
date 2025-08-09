<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\OperatorDocument;
use App\Models\DocumentType;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $operator = $user->operator;

        if (!$operator) {
            return redirect()->route('login')->with('error', 'Operator record not found.');
        }

        $alerts = [];

        // Franchise Applications
        $applications = $operator->franchiseApplications()->get();
        $latestApp = $applications->sortByDesc('created_at')->first();

        // --- Franchise Status & End Date ---
        $franchiseStatus = $latestApp ? ucfirst($latestApp->status) : 'No Application';
        $franchiseEndDate = $latestApp && $latestApp->franchise_end_date
            ? Carbon::parse($latestApp->franchise_end_date)->format('F j, Y')
            : null;

        if ($latestApp) {
            if ($latestApp->status === 'submitted') {
                $alerts[] = [
                    'type' => 'info',
                    'message' => 'Your franchise application was received and is under review.'
                ];
            }
            if ($latestApp->status === 'approved') {
                $alerts[] = [
                    'type' => 'success',
                    'message' => 'Congratulations! Your franchise application was approved.'
                ];
            }
            if ($latestApp->status === 'rejected') {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => 'Your franchise application was rejected.' .
                        ($latestApp->rejection_reason ? ' Reason: ' . $latestApp->rejection_reason : '')
                ];
            }

            // Expiration reminder
            if ($latestApp->status === 'approved' && $latestApp->franchise_end_date) {
                $daysLeft = Carbon::now()->diffInDays($latestApp->franchise_end_date, false);

                if ($daysLeft <= 90 && $daysLeft > 0) {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => "Your franchise will expire in $daysLeft days. Please renew soon."
                    ];
                } elseif ($daysLeft <= 0) {
                    $alerts[] = [
                        'type' => 'danger',
                        'message' => 'Your franchise has expired. Please renew immediately.'
                    ];
                }
            }
        }

        // Required Documents
        $requiredDocTypes = DocumentType::forOperator()->get();
        $submittedDocs = $operator->documents ?? collect();

        $missingDocs = $requiredDocTypes->filter(function ($type) use ($submittedDocs) {
            return !$submittedDocs->where('document_type_id', $type->document_id)->count();
        });

        if ($missingDocs->count()) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'You have missing required documents: ' . $missingDocs->pluck('name')->implode(', ')
            ];
        }

        $rejectedDocs = $submittedDocs->where('status', 'rejected');
        if ($rejectedDocs->count()) {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Some documents were rejected: ' . $rejectedDocs->map(function ($doc) {
                    return $doc->documentType->name .
                        ($doc->rejection_reason ? ' (Reason: ' . $doc->rejection_reason . ')' : '');
                })->implode(', ')
            ];
        }

        // Expiring documents (within 30 days)
        $expiringDocuments = $submittedDocs->filter(function ($doc) {
            return $doc->expiry_date && Carbon::now()->diffInDays($doc->expiry_date, false) <= 30;
        });

        // Payments (matching PaymentController logic)
        $pendingPayments = Payment::whereHas('franchiseApplication', function ($q) use ($operator) {
            $q->where('operator_id', $operator->operator_id);
        })->whereNull('paid_at')->with(['fee', 'franchiseApplication'])->get();

        $completedPayments = Payment::whereHas('franchiseApplication', function ($q) use ($operator) {
            $q->where('operator_id', $operator->operator_id);
        })->whereNotNull('paid_at')->with(['fee', 'franchiseApplication'])->latest()->take(10)->get();

        // Incomplete applications (not approved/rejected/submitted)
        $incompleteApplications = $applications->whereNotIn('status', ['approved', 'rejected', 'submitted']);

        return view('operator.dashboard', compact(
            'alerts',
            'pendingPayments',
            'completedPayments',
            'incompleteApplications',
            'expiringDocuments',
            'franchiseStatus',
            'franchiseEndDate'
        ));
    }
}
