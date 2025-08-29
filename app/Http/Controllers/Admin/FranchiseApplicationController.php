<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\Operator;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\FranchiseStatusUpdated;
use App\Services\NotificationService;

class FranchiseApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = FranchiseApplication::with(['operator', 'driver', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by application type
        if ($request->filled('application_type')) {
            $query->where('application_type', $request->application_type);
        }

        // Search by operator name or franchise number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('operator_name', 'like', "%{$search}%")
                  ->orWhere('franchise_no', 'like', "%{$search}%")
                  ->orWhere('sticker_no', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15);

        // Get status counts for dashboard
        $statusCounts = [
            'submitted' => FranchiseApplication::where('status', 'submitted')->count(),
            'under_review' => FranchiseApplication::where('status', 'under_review')->count(),
            'approved' => FranchiseApplication::where('status', 'approved')->count(),
            'rejected' => FranchiseApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.franchise.index', compact('applications', 'statusCounts'));
    }

    public function show(FranchiseApplication $franchiseApplication)
    {
        $franchiseApplication->load(['operator', 'driver', 'reviewer', 'motorDetail.unitMake', 'route']);
        
        return view('admin.franchise.show', compact('franchiseApplication'));
    }

    public function updateStatus(Request $request, FranchiseApplication $franchiseApplication)
    {
        $request->validate([
            'status' => 'required|in:under_review,approved,rejected',
            'rejection_reason' => 'nullable|string|max:500',
            'franchise_no' => 'nullable|string|max:50',
            'sticker_no' => 'nullable|string|max:50',
            'franchise_start_date' => 'nullable|date',
            'franchise_end_date' => 'nullable|date|after:franchise_start_date',
            'franchise_fee' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ];

        // Add rejection reason if status is rejected
        if ($request->status === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
        }

        // Add franchise details if approved
        if ($request->status === 'approved') {
            $updateData = array_merge($updateData, [
                'franchise_no' => $request->franchise_no,
                'sticker_no' => $request->sticker_no,
                'franchise_start_date' => $request->franchise_start_date,
                'franchise_end_date' => $request->franchise_end_date,
                'franchise_fee' => $request->franchise_fee,
            ]);
        }

        $franchiseApplication->update($updateData);

        // Send email notification to operator user
        try {
            $franchiseApplication->loadMissing(['operator.user']);
            $recipient = optional($franchiseApplication->operator->user)->email;
            if ($recipient) {
                Mail::to($recipient)->send(new FranchiseStatusUpdated($franchiseApplication));
            }
        } catch (\Throwable $e) {
            // Silently ignore email failures but keep request flow
        }

        // Create in-app notification (template-based)
        try {
            if ($franchiseApplication->operator && $franchiseApplication->operator->user) {
                $user = $franchiseApplication->operator->user;
                $templateKey = match ($request->status) {
                    'approved' => 'franchise_approved',
                    'rejected' => 'franchise_rejected',
                    default => 'franchise_under_review',
                };
                app(NotificationService::class)->sendToUser($user, $templateKey, [
                    'application_number' => $franchiseApplication->application_number,
                    'rejection_reason' => $franchiseApplication->rejection_reason ?? 'N/A',
                    'status' => $request->status,
                ]);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Log the status change
        // $franchiseApplication->logStatusChange(
        //     $franchiseApplication->getOriginal('status'),
        //     $request->status,
        //     $request->status === 'rejected' ? $request->rejection_reason : 'Status updated by admin'
        // );

        return redirect()->route('admin.franchise.index')
            ->with('success', 'Application status updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:franchise_applications,id',
            'status' => 'required|in:under_review,approved,rejected',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $applications = FranchiseApplication::whereIn('id', $request->application_ids)->get();

        foreach ($applications as $application) {
            $updateData = [
                'status' => $request->status,
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ];

            if ($request->status === 'rejected') {
                $updateData['rejection_reason'] = $request->rejection_reason;
            }

            $application->update($updateData);

            // Log the status change
            // $application->logStatusChange(
            //     $application->getOriginal('status'),
            //     $request->status,
            //     $request->status === 'rejected' ? $request->rejection_reason : 'Bulk status update by admin'
            // );
        }

        return redirect()->route('admin.franchise.index')
            ->with('success', count($applications) . ' applications updated successfully.');
    }

    public function statistics()
    {
        $stats = [
            'total_applications' => FranchiseApplication::count(),
            'pending_review' => FranchiseApplication::whereIn('status', ['submitted', 'under_review'])->count(),
            'approved_today' => FranchiseApplication::where('status', 'approved')
                ->whereDate('reviewed_at', today())->count(),
            'rejected_today' => FranchiseApplication::where('status', 'rejected')
                ->whereDate('reviewed_at', today())->count(),
            'status_distribution' => FranchiseApplication::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
        ];

        return response()->json($stats);
    }

    public function export(Request $request)
    {
        $query = FranchiseApplication::with(['operator', 'driver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        $filename = 'franchise_applications_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Application Number', 'Operator Name', 'Driver Name', 'Application Type', 
                'Status', 'Franchise No', 'Sticker No', 'CTC No',
                'Submitted At', 'Reviewed At', 'Reviewer', 'Rejection Reason'
            ]);

            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->application_number,
                    $application->operator_name,
                    $application->driver->name ?? 'N/A',
                    $application->application_type,
                    $application->status,
                    $application->franchise_no ?? 'N/A',
                    $application->sticker_no ?? 'N/A',
                    $application->ctc_no ?? 'N/A',
                    $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i:s') : 'N/A',
                    $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i:s') : 'N/A',
                    $application->reviewer->name ?? 'N/A',
                    $application->rejection_reason ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 