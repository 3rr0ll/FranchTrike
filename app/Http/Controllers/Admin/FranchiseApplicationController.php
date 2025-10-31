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
use Illuminate\Validation\Rule;


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
            'operator_last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'operator_first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'operator_middle_initial' => [
                'nullable',
                'string',
                'max:1',
                'regex:/^[A-Za-z]$/'
            ],
            'operator_barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'operator_birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The operator must be at least 18 years old.');
                    }
                },
            ],
            'operator_age' => [
                'required',
                'integer',
                'min:18',
                'max:80'
            ],
            'operator_sex' => [
                'required'
            ],
            'operator_civil_status' => [
                'required'
            ],
            'operator_contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'operator_email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'operator_password' => [
                'required',
                'string',
                'min:8'
            ],

            // Driver details
            'driver_last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'driver_first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'driver_middle_initial' => [
                'nullable',
                'string',
                'max:1',
                'regex:/^[A-Za-z]$/'
            ],
            'driver_barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'driver_birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The driver must be at least 18 years old.');
                    }
                },
            ],
            'driver_age' => [
                'required',
                'integer',
                'min:18',
                'max:80',
            ],
            'driver_sex' => [
                'required'            ],
            'driver_civil_status' => [
                'required'
            ],
            'driver_contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'driver_license_no' => [
                'required',
                'string',
                'max:50',
                'unique:drivers,license_no',
                'regex:/^[A-Z]\d{2}-\d{2}-\d{6}$/'
            ],
            'driver_license_validity' => [
                'required',
                'date',
                'after:today',
            ],
            'driver_license_nature' => [
                'required'
            ],

            // Franchise application details
            'application_type' => [
                'required',
                'in:new,renewal'
            ],
            'route_id' => [
                'required',
                'exists:routes,id'
            ],
            'ctc_no' => [
                'required',
                'string',
                'max:50'
            ],
            'ctc_date_issued' => [
                'required',
                'date'
            ],
            'ctc_place_issued' => [
                'required',
                'string',
                'max:255'
            ],
            'franchise_fee' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Previous franchise details (for renewal)
            'previous_franchise_no' => [
                'nullable',
                'string',
                'max:50'
            ],
            'previous_sticker_no' => [
                'nullable',
                'string',
                'max:50'
            ],
            'previous_application_id' => [
                'nullable',
                'integer'
            ],
            'previous_franchise_end_date' => [
                'nullable',
                'date'
            ],

            // Document checkboxes
            'operator_documents' => [
                'required',
                'array'
            ],
            'operator_documents.*' => [
                'exists:document_types,document_id'
            ],
            'driver_documents' => [
                'required',
                'array'
            ],
            'driver_documents.*' => [
                'exists:document_types,document_id'
            ],

            // Motor Details - referencing Operator side
            'unit_type' => [
                'required',
                'string'
            ],
            'unit_make_id' => [
                'required',
                'exists:unit_makes,id'
            ],
            'motorno' => [
                'required',
                'string'
            ],
            'chasisno' => [
                'required',
                'string'
            ],
            'platenumber' => [
                'required',
                'string'
            ],
        ];

        // Add conditional validation for renewal applications
        if ($request->application_type === 'renewal') {
            $validationRules['previous_franchise_no'] = [
                'required',
                'string',
                'max:50'
            ];
            $validationRules['previous_sticker_no'] = [
                'required',
                'string',
                'max:50'
            ];
            $validationRules['previous_franchise_end_date'] = [
                'required',
                'date'
            ];
        }
        // Set default municipality and province for operator and driver
        $request->merge([
            'operator_municipality' => 'Padre Garcia',
            'operator_province' => 'Batangas',
            'driver_municipality' => 'Padre Garcia',
            'driver_province' => 'Batangas',
        ]);

        $request->validate($validationRules);


        DB::beginTransaction();

        try {
            // Create user account for operator
            $user = User::create([
                'name' => $request->operator_first_name . ' ' . $request->operator_last_name,
                'email' => $request->operator_email,
                'password' => Hash::make($request->operator_password),
                'role_id' => 1,
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
            $applicationNumber = FranchiseApplication::count() + 1;

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

        // Load all data/relationships needed for the edit view
        $franchiseApplication = FranchiseApplication::with([
            'operator.user',
            'driver',
            'driver.driverDocuments.documentType',
            'motorDetail.unitMake',
            'route',
            'operator',
        ])->findOrFail($id);

        // Get list of all possible routes (for route select field)
        $routes = Route::all();

        // Get list of all unit makes (for motor detail/unit select fields)
        $unitMakes = UnitMake::all();

        return view('admin.franchise.edit', compact(
            'franchiseApplication',
            'routes',
            'unitMakes',
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

        $franchiseApplication = FranchiseApplication::with(['operator.user', 'driver', 'motorDetail'])
            ->findOrFail($id);

        // Set up validation rules (unchanged logic)
        $validationRules = [
            // Operator details
            'operator_last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'operator_first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'operator_middle_initial' => [
                'nullable',
                'string',
                'max:1',
                'regex:/^[A-Za-z]$/'
            ],
            'operator_barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'operator_birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The operator must be at least 18 years old.');
                    }
                },
            ],
            'operator_age' => [
                'required',
                'integer',
                'min:18',
                'max:80'
            ],
            'operator_sex' => [
                'required'
            ],
            'operator_civil_status' => [
                'required'
            ],
            'operator_contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'operator_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore(optional($franchiseApplication->operator->user)->id ?? null)
                    ->where('role_id', 1),
                function ($attribute, $value, $fail) {
                    if (!preg_match('/\.com$/i', $value)) {
                        $fail('The operator email must end with .com.');
                    }
                },
            ],
            'operator_password' => [
                'nullable',
                'string',
                'min:8'
            ],

            // Driver details
            'driver_last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'driver_first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'driver_middle_initial' => [
                'nullable',
                'string',
                'max:1',
                'regex:/^[A-Za-z]$/'
            ],
            'driver_barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'driver_birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The driver must be at least 18 years old.');
                    }
                },
            ],
            'driver_age' => [
                'required',
                'integer',
                'min:18',
                'max:80',
            ],
            'driver_sex' => [
                'required',
            ],
            'driver_civil_status' => [
                'required'
            ],
            'driver_contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'driver_license_no' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z]\d{2}-\d{2}-\d{6}$/'
            ],
            'driver_license_validity' => [
                'required',
                'date',
                'after:today',
            ],
            'driver_license_nature' => [
                'required'
            ],

            // Franchise application details
            'application_type' => [
                'required',
                'in:new,renewal'
            ],
            'route_id' => [
                'required',
                'exists:routes,id'
            ],
            'ctc_no' => [
                'required',
                'string',
                'max:50'
            ],
            'ctc_date_issued' => [
                'required',
                'date'
            ],
            'ctc_place_issued' => [
                'required',
                'string',
                'max:255'
            ],
            'franchise_no' => [
                'nullable',
                'string',
                'max:50'
            ],
            'sticker_no' => [
                'nullable',
                'string',
                'max:50'
            ],

            // Previous franchise details (for renewal)
            'previous_franchise_no' => [
                'nullable',
                'string',
                'max:50'
            ],
            'previous_sticker_no' => [
                'nullable',
                'string',
                'max:50'
            ],
            'previous_application_id' => [
                'nullable',
                'integer'
            ],
            'previous_franchise_end_date' => [
                'nullable',
                'date'
            ],
        ];

        // We want fields missing from the form to keep their old values
        // For "old value" in validation, merge old values for nullable fields
        $request->merge([
            'franchise_fee' => $request->input('franchise_fee', $franchiseApplication->franchise_fee),
            'franchise_no' => $request->input('franchise_no', $franchiseApplication->franchise_no),
            'sticker_no' => $request->input('sticker_no', $franchiseApplication->sticker_no),
            'previous_franchise_no' => $request->input('previous_franchise_no', $franchiseApplication->previous_franchise_no),
            'previous_sticker_no' => $request->input('previous_sticker_no', $franchiseApplication->previous_sticker_no),
            'previous_application_id' => $request->input('previous_application_id', $franchiseApplication->previous_application_id),
            'previous_franchise_end_date' => $request->input('previous_franchise_end_date', $franchiseApplication->previous_franchise_end_date),
        ]);

        $validated = $request->validate($validationRules);

        // Enhanced check for changes, including all editable fields and normalizing nulls
        $fieldsToCheck = [
            'application_type',
            'route_id',
            'ctc_no',
            'ctc_date_issued',
            'ctc_place_issued',
            'franchise_fee',
            'franchise_no',
            'sticker_no',
            'previous_franchise_no',
            'previous_sticker_no',
            'previous_application_id',
            'previous_franchise_end_date'
        ];

        $hasChanges = false;
        foreach ($fieldsToCheck as $field) {
            if (($franchiseApplication->{$field} ?? null) != ($validated[$field] ?? null)) {
                $hasChanges = true;
                break;
            }
        }

        // Also check OPERATOR fields
        $operatorFields = [
            'last_name'        => 'operator_last_name',
            'first_name'       => 'operator_first_name',
            'middle_initial'   => 'operator_middle_initial',
            'barangay'         => 'operator_barangay',
            'birth_date'       => 'operator_birth_date',
            'age'              => 'operator_age',
            'sex'              => 'operator_sex',
            'civil_status'     => 'operator_civil_status',
            'contact_no'       => 'operator_contact_no',
        ];
        if ($franchiseApplication->operator) {
            foreach ($operatorFields as $db => $req) {
                if (($franchiseApplication->operator->{$db} ?? null) != ($validated[$req] ?? null)) {
                    $hasChanges = true;
                    break;
                }
            }
        }

        // Also check DRIVER fields
        $driverFields = [
            'last_name'        => 'driver_last_name',
            'first_name'       => 'driver_first_name',
            'middle_initial'   => 'driver_middle_initial',
            'barangay'         => 'driver_barangay',
            'birth_date'       => 'driver_birth_date',
            'age'              => 'driver_age',
            'sex'              => 'driver_sex',
            'civil_status'     => 'driver_civil_status',
            'contact_no'       => 'driver_contact_no',
            'license_no'       => 'driver_license_no',
            'license_validity' => 'driver_license_validity',
            'license_nature'   => 'driver_license_nature',
        ];
        if ($franchiseApplication->driver) {
            foreach ($driverFields as $db => $req) {
                if (($franchiseApplication->driver->{$db} ?? null) != ($validated[$req] ?? null)) {
                    $hasChanges = true;
                    break;
                }
            }
        }

        if (!$hasChanges) {
            // Populate old values for the form by redirecting back with model data
            return redirect()->route('admin.franchise.show', $encryptedId)
                ->withInput($request->all())
                ->with('info', 'No changes were made to the franchise application.');
        }

        DB::transaction(function () use ($franchiseApplication, $validated) {
            // Update franchiseApplication model with all relevant fields,
            // using validated values and falling back to current value if not provided
            $franchiseApplication->update([
                'application_type'     => $validated['application_type'],
                'route_id'             => $validated['route_id'],
                'ctc_no'               => $validated['ctc_no'],
                'ctc_date_issued'      => $validated['ctc_date_issued'],
                'ctc_place_issued'     => $validated['ctc_place_issued'],
                'franchise_fee'        => $validated['franchise_fee'] ?? $franchiseApplication->franchise_fee,
                'franchise_no'         => $validated['franchise_no'] ?? $franchiseApplication->franchise_no,
                'sticker_no'           => $validated['sticker_no'] ?? $franchiseApplication->sticker_no,
                'previous_franchise_no'=> $validated['previous_franchise_no'] ?? $franchiseApplication->previous_franchise_no,
                'previous_sticker_no'  => $validated['previous_sticker_no'] ?? $franchiseApplication->previous_sticker_no,
                'previous_application_id' => $validated['previous_application_id'] ?? $franchiseApplication->previous_application_id,
                'previous_franchise_end_date' => $validated['previous_franchise_end_date'] ?? $franchiseApplication->previous_franchise_end_date,
            ]);

            // Update operator details (assuming relationships)
            if ($franchiseApplication->operator) {
                $franchiseApplication->operator->update([
                    'last_name'        => $validated['operator_last_name'],
                    'first_name'       => $validated['operator_first_name'],
                    'middle_initial'   => $validated['operator_middle_initial'] ?? $franchiseApplication->operator->middle_initial,
                    'barangay'         => $validated['operator_barangay'],
                    'birth_date'       => $validated['operator_birth_date'],
                    'age'              => $validated['operator_age'],
                    'sex'              => $validated['operator_sex'],
                    'civil_status'     => $validated['operator_civil_status'],
                    'contact_no'       => $validated['operator_contact_no'],
                ]);
            }

            // Update driver details (assuming relationships)
            if ($franchiseApplication->driver) {
                $franchiseApplication->driver->update([
                    'last_name'        => $validated['driver_last_name'],
                    'first_name'       => $validated['driver_first_name'],
                    'middle_initial'   => $validated['driver_middle_initial'] ?? $franchiseApplication->driver->middle_initial,
                    'barangay'         => $validated['driver_barangay'],
                    'birth_date'       => $validated['driver_birth_date'],
                    'age'              => $validated['driver_age'],
                    'sex'              => $validated['driver_sex'],
                    'civil_status'     => $validated['driver_civil_status'],
                    'contact_no'       => $validated['driver_contact_no'],
                    'license_no'       => $validated['driver_license_no'],
                    'license_validity' => $validated['driver_license_validity'],
                    'license_nature'   => $validated['driver_license_nature'],
                ]);
            }

            // Any other model relation updates come here...
        });

        return redirect()
            ->route('admin.franchise.show', encrypt($franchiseApplication->id))
            ->with('success', 'Franchise application updated successfully.');
    }
}