<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\OperatorDocument;
use App\Models\DocumentType;
use App\Models\Payment;
use App\Models\Driver;
use App\Models\MotorChangeRequest;
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

        $applications = $this->getFranchiseApplications($operator);
        $latestApp = $this->getLatestApplication($applications);
        $franchiseApplications = $this->getSortedApplications($applications);

        $franchiseStatus = $this->getFranchiseStatus($latestApp);
        $franchiseEndDate = $this->getFranchiseEndDate($latestApp);

        $alerts = $this->buildAlerts($operator, $applications, $latestApp);

        $incompleteApplications = $this->getIncompleteApplications($applications);
        $expiringDocuments = $this->getExpiringDocuments($operator);
        $driversCount = $this->getDriversCount($operator);
        $applicationsInProgressCount = $franchiseApplications
            ? $franchiseApplications->whereIn('status', ['submitted', 'pending', 'under_review'])->count()
            : 0;
        $expiringDocumentsCount = $expiringDocuments->count();

        return view('operator.dashboard', compact(
            'alerts',
            'incompleteApplications',
            'expiringDocuments',
            'franchiseStatus',
            'franchiseEndDate',
            'franchiseApplications',
            'applicationsInProgressCount',
            'expiringDocumentsCount',
            'driversCount'
        ));
    }

    private function getFranchiseApplications($operator)
    {
        return $operator->franchiseApplications()->with('motorDetail')->get();
    }

    private function getLatestApplication($applications)
    {
        return $applications->sortByDesc('created_at')->first();
    }

    private function getSortedApplications($applications)
    {
        return $applications->sortByDesc('created_at')->values();
    }

    private function getFranchiseStatus($latestApp)
    {
        return $latestApp ? ucfirst($latestApp->status) : 'No Application';
    }

    private function getFranchiseEndDate($latestApp)
    {
        return $latestApp && $latestApp->franchise_end_date
            ? Carbon::parse($latestApp->franchise_end_date)->format('F j, Y')
            : null;
    }

    private function buildAlerts($operator, $applications, $latestApp)
    {
        $alerts = [];

        // Franchise application status alerts
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
                        ($latestApp->rejection_reason ? ' Reason: ' . e($latestApp->rejection_reason) : '')
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
                'message' => 'You have missing required documents: ' . e($missingDocs->pluck('name')->implode(', ')) .
                    ' — <a class="underline" href="' . route('operator.documents.status') . '">Upload now</a>'
            ];
        }

        $rejectedDocs = $submittedDocs->where('status', 'rejected');
        if ($rejectedDocs->count()) {
            $labels = $rejectedDocs->map(function ($doc) {
                return e($doc->documentType->name . ($doc->rejection_reason ? ' (Reason: ' . $doc->rejection_reason . ')' : ''));
            })->implode(', ');
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Some documents were rejected: ' . $labels .
                    ' — <a class="underline" href="' . route('operator.documents.status') . '">Review</a>'
            ];
        }

        // Expiring documents (within 30 days)
        $expiringDocuments = $submittedDocs->filter(function ($doc) {
            return $doc->expiry_date && Carbon::now()->diffInDays($doc->expiry_date, false) <= 30;
        });
        if ($expiringDocuments->count()) {
            $alerts[] = [
                'type' => 'warning',
                'message' => $expiringDocuments->count() . ' document(s) expiring within 30 days — <a class="underline" href="' . route('operator.documents.status') . '">View</a>'
            ];
        }

        // Drivers presence
        $driversCount = Driver::where('operator_id', $operator->operator_id)->count();
        if ($driversCount === 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => 'You have no drivers yet — <a class="underline" href="' . route('operator.driver.create') . '">Add a driver</a>'
            ];
        }

        // Motor change requests status
        $pendingMotorChanges = MotorChangeRequest::whereHas('franchiseApplication', function ($q) use ($operator) {
            $q->where('operator_id', $operator->operator_id);
        })->where('status', 'pending')->count();
        if ($pendingMotorChanges > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => 'You have ' . $pendingMotorChanges . ' motor change request(s) pending admin review.'
            ];
        }

        $recentMotorChange = MotorChangeRequest::whereHas('franchiseApplication', function ($q) use ($operator) {
            $q->where('operator_id', $operator->operator_id);
        })->latest()->first();
        if ($recentMotorChange && $recentMotorChange->created_at->gt(now()->subDays(7))) {
            if ($recentMotorChange->status === 'approved') {
                $alerts[] = [
                    'type' => 'success',
                    'message' => 'Your recent motor change request was approved.'
                ];
            } elseif ($recentMotorChange->status === 'rejected') {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => 'Your recent motor change request was rejected.'
                ];
            }
        }

        return $alerts;
    }

    private function getIncompleteApplications($applications)
    {
        return $applications->whereNotIn('status', ['approved', 'rejected', 'submitted']);
    }

    private function getExpiringDocuments($operator)
    {
        $submittedDocs = $operator->documents ?? collect();
        return $submittedDocs->filter(function ($doc) {
            return $doc->expiry_date && Carbon::now()->diffInDays($doc->expiry_date, false) <= 30;
        });
    }

    private function getDriversCount($operator)
    {
        return Driver::where('operator_id', $operator->operator_id)->count();
    }
}
