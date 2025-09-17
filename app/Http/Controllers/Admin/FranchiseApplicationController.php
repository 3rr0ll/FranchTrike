<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Route;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\FranchiseStatusUpdated;
use App\Services\NotificationService;
use App\Models\FranchiseApplicationLog;

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
                    ->orWhere('sticker_no', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15);

        $statusCounts = [
            'submitted' => FranchiseApplication::where('status', 'submitted')->count(),
            'under_review' => FranchiseApplication::where('status', 'under_review')->count(),
            'approved' => FranchiseApplication::where('status', 'approved')->count(),
            'rejected' => FranchiseApplication::where('status', 'rejected')->count(),
            'renewed' => FranchiseApplication::where('status', 'renewed')->count(),
        ];

        return view('admin.franchise.index', compact('applications', 'statusCounts'));
    }

    public function show(FranchiseApplication $franchiseApplication)
    {
        $franchiseApplication->load(['operator', 'driver', 'reviewer', 'motorDetail.unitMake', 'route','logs.updatedBy']);

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
        ]);

        $updateData = [
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ];


        $oldStatus = $franchiseApplication->status;

        FranchiseApplicationLog::create([
            'franchise_application_id' => $franchiseApplication->id,
            'status_before' => $oldStatus,
            'status_after' => $request->status,
            'updated_by' => Auth::id(),
        ]);

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
                    'application_id' => $franchiseApplication->id,
                    'rejection_reason' => $franchiseApplication->rejection_reason ?? 'N/A',
                    'status' => $request->status,
                ]);
            }
        } catch (\Throwable $e) {
            // ignore
        }



        return redirect()->route('admin.franchise.index')
            ->with('success', 'Application status updated successfully.');
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

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Application Number',
                'Operator Name',
                'Driver Name',
                'Application Type',
                'Status',
                'Franchise No',
                'Sticker No',
                'CTC No',
                'Submitted At',
                'Reviewed At',
                'Reviewer',
                'Rejection Reason'
            ]);

            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
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

    public function create()
    {
        $routes = Route::all();
        $documentTypes = DocumentType::all();

        return view('admin.franchise.create', compact('routes', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            // Operator details
            'operator_last_name' => 'required|string|max:255',
            'operator_first_name' => 'required|string|max:255',
            'operator_middle_initial' => 'nullable|string|max:1',
            'operator_barangay' => 'required|string|max:255',
            'operator_municipality' => 'required|string|max:255',
            'operator_province' => 'required|string|max:255',
            'operator_birth_date' => 'required|date',
            'operator_age' => 'required|integer|min:18',
            'operator_sex' => 'required|string|in:male,female',
            'operator_civil_status' => 'required|string',
            'operator_contact_no' => 'required|string|max:20',
            'operator_email' => 'required|email|unique:users,email',
            'operator_password' => 'required|string|min:8',

            // Driver details
            'driver_last_name' => 'required|string|max:255',
            'driver_first_name' => 'required|string|max:255',
            'driver_middle_initial' => 'nullable|string|max:1',
            'driver_barangay' => 'required|string|max:255',
            'driver_municipality' => 'required|string|max:255',
            'driver_province' => 'required|string|max:255',
            'driver_birth_date' => 'required|date',
            'driver_age' => 'required|integer|min:18',
            'driver_sex' => 'required|string|in:male,female',
            'driver_civil_status' => 'required|string',
            'driver_contact_no' => 'required|string|max:20',
            'driver_license_no' => 'required|string|max:50',
            'driver_license_validity' => 'required|date|after:today',

            // Franchise application details
            'application_type' => 'required|in:new,renewal',
            'route_id' => 'required|exists:routes,id',
            'ctc_no' => 'required|string|max:50',
            'ctc_date_issued' => 'required|date',
            'ctc_place_issued' => 'required|string|max:255',
            'franchise_fee' => 'nullable|numeric|min:0',

            // Previous franchise details (for renewal)
            'previous_franchise_no' => 'nullable|string|max:50',
            'previous_sticker_no' => 'nullable|string|max:50',
            'previous_application_id' => 'nullable|integer',
            'previous_franchise_end_date' => 'nullable|date',

            // Document checkboxes
            'operator_documents' => 'required|array',
            'operator_documents.*' => 'exists:document_types,document_id',
            'driver_documents' => 'required|array',
            'driver_documents.*' => 'exists:document_types,document_id',
        ];

        // Add conditional validation for renewal applications
        if ($request->application_type === 'renewal') {
            $validationRules['previous_franchise_no'] = 'required|string|max:50';
            $validationRules['previous_sticker_no'] = 'required|string|max:50';
            $validationRules['previous_franchise_end_date'] = 'required|date';
        }

        $request->validate($validationRules);


        DB::beginTransaction();

        try {
            // Create user account for operator
            $user = User::create([
                'name' => $request->operator_first_name . ' ' . $request->operator_last_name,
                'email' => $request->operator_email,
                'password' => Hash::make($request->operator_password),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]);

            // Create operator
            $operator = Operator::create([
                'user_id' => $user->id,
                'last_name' => $request->operator_last_name,
                'first_name' => $request->operator_first_name,
                'middle_initial' => $request->operator_middle_initial,
                'barangay' => $request->operator_barangay,
                'municipality' => $request->operator_municipality,
                'province' => $request->operator_province,
                'birth_date' => $request->operator_birth_date,
                'age' => $request->operator_age,
                'sex' => $request->operator_sex,
                'civil_status' => $request->operator_civil_status,
                'contact_no' => $request->operator_contact_no,
            ]);

            // Create driver
            $driver = Driver::create([
                'operator_id' => $operator->operator_id,
                'last_name' => $request->driver_last_name,
                'first_name' => $request->driver_first_name,
                'middle_initial' => $request->driver_middle_initial,
                'barangay' => $request->driver_barangay,
                'municipality' => $request->driver_municipality,
                'province' => $request->driver_province,
                'birth_date' => $request->driver_birth_date,
                'age' => $request->driver_age,
                'sex' => $request->driver_sex,
                'civil_status' => $request->driver_civil_status,
                'contact_no' => $request->driver_contact_no,
                'license_no' => $request->driver_license_no,
                'license_validity' => $request->driver_license_validity,
            ]);

            // Generate application number
            $applicationNumber = 'FA-' . date('Y') . '-' . str_pad(FranchiseApplication::count() + 1, 6, '0', STR_PAD_LEFT);

            // Create franchise application
            $franchiseApplicationData = [
                'application_number' => $applicationNumber,
                'operator_id' => $operator->operator_id,
                'driver_id' => $driver->driver_id,
                'application_type' => $request->application_type,
                'operator_name' => $request->operator_first_name . ' ' . $request->operator_last_name,
                'ctc_no' => $request->ctc_no,
                'ctc_date_issued' => $request->ctc_date_issued,
                'ctc_place_issued' => $request->ctc_place_issued,
                'franchise_fee' => $request->franchise_fee,
                'route_id' => $request->route_id,
                'status' => 'submitted',
                'submitted_at' => now(),
            ];

            // Add previous application ID for renewals
            if ($request->application_type === 'renewal' && $request->previous_application_id) {
                $franchiseApplicationData['previous_application_id'] = $request->previous_application_id;
            }

            $franchiseApplication = FranchiseApplication::create($franchiseApplicationData);

            // Create operator documents (as checkboxes - marked as approved since physiclly submitted)
            foreach ($request->operator_documents as $documentTypeId) {
                $documentType = DocumentType::find($documentTypeId);
                $operator->operatorDocuments()->create([
                    'document_type_id' => $documentTypeId,
                    'document_name' => $documentType ? $documentType->name . ' - Physically Submitted' : 'Document - Physically Submitted',
                    'file_path' => 'admin-uploaded/' . Str::uuid() . '.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 0,
                    'status' => 'approved',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);
            }

            // Create driver documents (as checkboxes - marked as approved since physically submitted)
            foreach ($request->driver_documents as $documentTypeId) {
                $documentType = DocumentType::find($documentTypeId);
                $driver->driverDocuments()->create([
                    'document_type_id' => $documentTypeId,
                    'document_name' => $documentType ? $documentType->name . ' - Physically Submitted' : 'Document - Physically Submitted',
                    'file_path' => 'admin-uploaded/' . Str::uuid() . '.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 0,
                    'status' => 'approved',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.franchise.index')
                ->with('success', 'Franchise application created successfully for walk-in client.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create franchise application: ' . $e->getMessage());
        }
    }
}
