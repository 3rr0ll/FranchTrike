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
use App\Models\UnitMake;
use Illuminate\Contracts\Encryption\DecryptException;



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
            'expired' => FranchiseApplication::where('status', 'expired')->count(),
        ];

        return view('admin.franchise.index', compact('applications', 'statusCounts'));
    }

    public function show($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }
    
        $franchiseApplication = FranchiseApplication::with([
            'operator', 
            'driver', 
            'reviewer', 
            'motorDetail.unitMake', 
            'route',
            'logs.updatedBy'
        ])->findOrFail($id);
    
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
        // Activity log for status update
        \App\Helpers\ActivityLogger::log(
            'franchise application',
            'status updated',
            'Franchise application status updated to "' . $request->status . '".',
            [
                'franchise application id' => $franchiseApplication->id,
                'status' => $request->status,
                'updated by' =>  Auth::user()->name,
                'rejection reason' => $request->rejection_reason ?? null,
                'franchise no' => $request->franchise_no ?? null,
                'sticker no' => $request->sticker_no ?? null,
                'franchise start date' => $request->franchise_start_date ?? null,
                'franchise end date' => $request->franchise_end_date ?? null,
            ]
        );



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

   
    public function masterList(Request $request)
    {
        $applications = FranchiseApplication::with([
            'operator',
            'driver',
            'route',
            'motorDetail.unitMake',
        ])->latest('submitted_at')->get();

        return view('admin.franchise.master-list', compact('applications'));
    }


    /**
     * Log the print action for the Master List.
     */
    public function logPrint(Request $request)
    {
        // Optionally, you can log additional filter info from the request if sent
        $filters = $request->only(['date_start', 'date_end', 'route']);

        // Compose log details
        $details = [
            'printed by admin name' => Auth::user()->name,
            'filters' => $filters,
        ];

        // Log the activity (adjust ActivityLogger namespace as needed)
        \App\Helpers\ActivityLogger::log(
            'franchise master list',
            'printed',
            'Master List printed by Admin',
            $details
        );

        return response()->json(['status' => 'success']);
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
            'driver_license_nature' => 'required|string',

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

            // Motor Details - referencing Operator side
            'unit_type' => 'required|string',
            'unit_make_id' => 'required|exists:unit_makes,id',
            'motorno' => 'required|string',
            'chasisno' => 'required|string',
            'platenumber' => 'required|string',
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
                'license_nature' => $request->driver_license_nature,
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

            // Create Motor Details (admin-side, mirrored operator-side logic)
            \App\Models\MotorDetail::create([
                'franchise_application_id' => $franchiseApplication->id,
                'unit_type' => $request->unit_type,
                'unit_make_id' => $request->unit_make_id,
                'motorno' => $request->motorno,
                'chasisno' => $request->chasisno,
                'platenumber' => $request->platenumber,
            ]);

            // Log motor detail addition
            \App\Helpers\ActivityLogger::log(
                'motor_detail',
                'added',
                'Admin added motor details to franchise application via admin-side.',
                [
                    'franchise_application_id' => $franchiseApplication->id,
                    'operator' => $operator->first_name . ' ' . $operator->last_name,
                    'unit_type' => $request->unit_type,
                    'unit_make_id' => $request->unit_make_id,
                    'motorno' => $request->motorno,
                    'chasisno' => $request->chasisno,
                    'platenumber' => $request->platenumber,
                    'added by' => Auth::user()->name,
                    'user_id' => Auth::check() ? Auth::id() : null,
                ]
            );

            // Create operator documents (as checkboxes - marked as approved since physically submitted)
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

            // Create driver documents (as checkboxes, marked as approved since physically submitted)
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

            \App\Helpers\ActivityLogger::log(
                'franchise application',
                'created',
                'Franchise application created for client.',
                [
                    'franchise application id' => $franchiseApplication->id,
                    'operator id' => $operator->operator_id,
                    'driver id' => $driver->driver_id,
                    'created by' => Auth::user()->name,
                    'application type' => $request->application_type,
                    'franchise fee' => $request->franchise_fee,
                    'route id' => $request->route_id,
                ]
            );

            DB::commit();

            return redirect()->route('admin.franchise.index')
                ->with('success', 'Franchise application created successfully for client.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create franchise application: ' . $e->getMessage());
        }
    }


    public function edit($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $franchiseApplication = FranchiseApplication::with([
            'operator.user',
            'driver',
            'motorDetail',
            'route',
        ])->findOrFail($id);

        // Fetch options for dropdowns or checkboxes
        $routes = Route::all();
        $unitMakes = UnitMake::all();
        $documentTypes = DocumentType::all();

        return view('admin.franchise.edit', compact(
            'franchiseApplication',
            'routes',
            'unitMakes',
            'documentTypes',
            'encryptedId'
        ));
    }

    /**
     * Update the specified Franchise Application in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }
    
        $franchiseApplication = FranchiseApplication::with(['operator', 'driver', 'motorDetail'])
            ->findOrFail($id);
    
        $validated = $request->validate([
            'operator_name' => 'required|string|max:255',
            'operator_contact_no' => 'required|string|max:20',
            'operator_address' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
            'driver_license_no' => 'required|string|max:50',
            'driver_contact_no' => 'required|string|max:20',
            'driver_address' => 'required|string|max:255',
            'application_type' => 'required|in:new,renewal',
            'route_id' => 'required|exists:routes,id',
            'ctc_no' => 'required|string|max:50',
            'operator_name_document' => 'required|string|max:255',
            'franchise_no' => 'nullable|string|max:50',
            'sticker_no' => 'nullable|string|max:50',
            'franchise_start_date' => 'nullable|date',
            'franchise_end_date' => 'nullable|date',
            'rejection_reason' => 'nullable|string|max:500',
        ]);
    
        DB::transaction(function () use ($franchiseApplication, $validated) {
            $franchiseApplication->update([
                'application_type' => $validated['application_type'],
                'route_id' => $validated['route_id'],
                'ctc_no' => $validated['ctc_no'],
                'operator_name' => $validated['operator_name_document'],
                'franchise_no' => $validated['franchise_no'] ?? $franchiseApplication->franchise_no,
                'sticker_no' => $validated['sticker_no'] ?? $franchiseApplication->sticker_no,
                'franchise_start_date' => $validated['franchise_start_date'] ?? $franchiseApplication->franchise_start_date,
                'franchise_end_date' => $validated['franchise_end_date'] ?? $franchiseApplication->franchise_end_date,
                'rejection_reason' => $validated['rejection_reason'] ?? null,
            ]);
    
            $franchiseApplication->operator->update([
                'full_name' => $validated['operator_name'],
                'contact_no' => $validated['operator_contact_no'],
                'address' => $validated['operator_address'],
            ]);
    
            $franchiseApplication->driver->update([
                'full_name' => $validated['driver_name'],
                'license_no' => $validated['driver_license_no'],
                'contact_no' => $validated['driver_contact_no'],
                'address' => $validated['driver_address'],
            ]);
        });
    
        return redirect()
            ->route('admin.franchise.show', encrypt($franchiseApplication->id))
            ->with('success', 'Franchise application updated successfully.');
    }
}