<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\OperatorDocument;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Use the correct operator relationship (assuming User hasOne Operator using user_id)
        $operator = $user->operator;

        if (!$operator) {
            return redirect()->route('login')->with('error', 'Operator record not found.');
        }

        $alerts = [];

        $applications = $operator->franchiseApplications()->get();

        $latestApp = $applications->sortByDesc('created_at')->first();

        if ($latestApp && $latestApp->status === 'submitted') {
            $alerts[] = [
                'type' => 'info',
                'message' => 'Your franchise application was received and is under review.'
            ];
        }

        if ($latestApp && $latestApp->status === 'approved') {
            $alerts[] = [
                'type' => 'success',
                'message' => 'Congratulations! Your franchise application was approved.'
            ];
        }

        if ($latestApp && $latestApp->status === 'rejected') {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Your franchise application was rejected.' .
                    ($latestApp->rejection_reason ? ' Reason: ' . $latestApp->rejection_reason : '')
            ];
        }

        if ($latestApp && $latestApp->status === 'approved' && $latestApp->franchise_end_date) {
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

        // Check for required operator documents
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

        return view('operator.dashboard', compact('alerts'));
    }
}
