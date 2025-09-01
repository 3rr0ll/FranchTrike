<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use App\Models\Driver;
use App\Models\UnitMake;
use Illuminate\Support\Facades\Auth;

class FranchiseApplicationController extends Controller
{
    public function index()
    {
        $operator = Auth::user()->operator;

        $operatorDocumentsApproved = OperatorDocument::where('operator_id', $operator->operator_id)
            ->where('status', '!=', 'approved')
            ->count() === 0;

        $drivers = Driver::where('operator_id', $operator->operator_id)->get();
        // Changed from pluck('id') to pluck('driver_id')
        $driverIds = $drivers->pluck('driver_id');

        $driverDocumentsApproved = DriverDocument::whereIn('driver_id', $driverIds)
            ->where('status', '!=', 'approved')
            ->count() === 0;

        $canApply = $operatorDocumentsApproved && $driverDocumentsApproved;

        $applications = FranchiseApplication::where('operator_id', $operator->operator_id)->latest()->get();

        foreach ($applications as $application) {
            if (
                $application->status === 'approved' &&
                $application->franchise_end_date &&
                now()->greaterThan($application->franchise_end_date)
            ) {
                $application->update(['status' => 'expired']);
            }
        }

        // Get drivers who already have franchise applications
        $driversWithApplications = $applications->pluck('driver_id')->toArray();

        return view('operator.franchise.index', compact('applications', 'canApply', 'drivers', 'driversWithApplications'));
    }

    public function create()
    {
        $user = Auth::user();
        $operator = $user ? $user->operator : null;

        // Get all drivers for this operator
        $allDrivers = $operator ? $operator->drivers()->latest()->get() : collect();

        // Get drivers who already have franchise applications
        $driversWithApplications = FranchiseApplication::where('operator_id', $operator->operator_id)
            ->pluck('driver_id')
            ->toArray();

        // Filter out drivers who already have applications
        $availableDrivers = $allDrivers->whereNotIn('driver_id', $driversWithApplications);

        $routes = \App\Models\Route::all();

        return view('operator.franchise.create', compact('availableDrivers', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_type' => 'required|in:new,renewal',
            'previous_application_id' => 'nullable|integer',
            'driver_id' => 'required|exists:drivers,driver_id',
            'route_id' => 'required|exists:routes,id',
            'franchise_no' => 'nullable|string',
            'sticker_no' => 'nullable|string',
            'operator_name' => 'required|string',
            'ctc_no' => 'required|string',
            'ctc_date_issued' => 'required|date',
            'ctc_place_issued' => 'required|string',
            'franchise_fee' => 'nullable|numeric',
        ]);

        $operator = Auth::user()->operator;

        // Create the franchise application
        $franchiseApplication = FranchiseApplication::create([
            'operator_id' => $operator->operator_id,
            'driver_id' => $request->driver_id,
            'route_id' => $request->route_id,
            'application_type' => $request->application_type,
            'previous_application_id' => $request->previous_application_id,
            'franchise_no' => $request->franchise_no,
            'sticker_no' => $request->sticker_no,
            'operator_name' => $request->operator_name,
            'ctc_no' => $request->ctc_no,
            'ctc_date_issued' => $request->ctc_date_issued,
            'ctc_place_issued' => $request->ctc_place_issued,
            'franchise_start_date' => null,
            'franchise_end_date' => null,
            'franchise_fee' => $request->franchise_fee,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        return redirect()->route('operator.franchise.motor-details', $franchiseApplication->id)->with('success', 'Franchise application submitted..');
    }

    // Motor details form
    public function motorDetails($franchiseApplicationId)
    {
        $franchiseApplication = FranchiseApplication::findOrFail($franchiseApplicationId);
        $unitMakes = \App\Models\UnitMake::all();

        return view('operator.franchise.motor-details', compact('franchiseApplication', 'unitMakes'));
    }

    // Store motor details
    public function storeMotorDetails(Request $request, $franchiseApplicationId)
    {
        $request->validate([
            'unit_type' => 'required|string',
            'unit_make_id' => 'required|exists:unit_makes,id',
            'motorno' => 'required|string',
            'chasisno' => 'required|string',
            'platenumber' => 'required|string',
        ]);

        \App\Models\MotorDetail::create([
            'franchise_application_id' => $franchiseApplicationId,
            'unit_type' => $request->unit_type,
            'unit_make_id' => $request->unit_make_id,
            'motorno' => $request->motorno,
            'chasisno' => $request->chasisno,
            'platenumber' => $request->platenumber,
        ]);

        return redirect()->route('operator.franchise.index')->with('success', 'Motor details added successfully.');
    }

    // Approve method: set status to approved, set start and end dates
    public function approve(FranchiseApplication $application)
    {
        $application->update([
            'status' => 'approved',
            'franchise_start_date' => now(),
            'franchise_end_date' => now()->addYear(),
        ]);
        return back()->with('success', 'Application approved and dates set.');
    }

    public function show(FranchiseApplication $franchiseApplication)
    {
        // Check if the application should be marked as expired
        if (
            $franchiseApplication->status === 'approved' &&
            $franchiseApplication->franchise_end_date &&
            now()->greaterThan($franchiseApplication->franchise_end_date)
        ) {
            $franchiseApplication->update(['status' => 'expired']);
        }

        $franchiseApplication->load(['operator', 'driver', 'route', 'motorDetail']);
        $unitMakes = UnitMake::all();
        return view('operator.franchise.show', compact('franchiseApplication', 'unitMakes'));
    }

    /**
     * Renew an expired franchise application by creating a new one, including motor details.
     */
    public function renew(FranchiseApplication $franchiseApplication)
    {
        // Only allow renewal if status is 'expired'
        if ($franchiseApplication->status !== 'expired') {
            return back()->with('error', 'Only expired applications can be renewed.');
        }

        // Ensure the operator owns this application
        $operator = Auth::user()->operator;
        if ($franchiseApplication->operator_id !== $operator->operator_id) {
            abort(403, 'Unauthorized access to this application.');
        }

        // Prevent multiple active applications for the same driver
        $activeApp = FranchiseApplication::where('driver_id', $franchiseApplication->driver_id)
            ->whereIn('status', ['submitted', 'under_review', 'approved'])
            ->first();

        if ($activeApp) {
            return back()->with('error', 'This driver already has an active application.');
        }

        try {
            // Replicate the old application for renewal, but do NOT save yet
            $renewalApplication = $franchiseApplication->replicate();

            // Set application_number to null for the new renewal application
            $renewalApplication->application_number = null;

            // Set renewal-specific fields
            $renewalApplication->status = 'submitted';
            $renewalApplication->application_type = 'renewal';
            $renewalApplication->previous_application_id = $franchiseApplication->id;
            $renewalApplication->submitted_at = now();
            $renewalApplication->franchise_start_date = now();
            $renewalApplication->franchise_end_date = now()->addYear();

            // Save as a new record first (so the old one is still 'expired' during insert)
            $renewalApplication->save();

            // Also replicate the motor details if they exist
            if ($franchiseApplication->motorDetail) {
                $motorDetail = $franchiseApplication->motorDetail->replicate();
                $motorDetail->franchise_application_id = $renewalApplication->id;
                $motorDetail->save();
            }

            // Now, safely mark the old application as renewed (status change)
            $franchiseApplication->update(['status' => 'renewed']);

            return redirect()->route('operator.franchise.show', $renewalApplication->id)
                ->with('success', 'Renewal application created successfully! The application has been submitted for review, including motor details.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create renewal application: ' . $e->getMessage());
        }
    }
}