<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class FranchiseApplicationController extends Controller
{
    public function index()
    {
        $operator = Auth::user()->operator;

        $operatorDocumentsApproved = OperatorDocument::where('operator_id', $operator->id)
            ->where('status', '!=', 'approved')
            ->count() === 0;

        $drivers = Driver::where('operator_id', $operator->id)->get();
        $driverIds = $drivers->pluck('id');

        $driverDocumentsApproved = DriverDocument::whereIn('driver_id', $driverIds)
            ->where('status', '!=', 'approved')
            ->count() === 0;

        $canApply = $operatorDocumentsApproved && $driverDocumentsApproved;

        $applications = FranchiseApplication::where('operator_id', $operator->id)->latest()->get();

        return view('operator.franchise.index', compact('applications', 'canApply'));
    }

    public function create()
    {
        $user = Auth::user();
        $operator = $user ? $user->operator : null;
        $drivers = $operator ? $operator->drivers()->latest()->get() : collect();

        return view('operator.franchise.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_type' => 'required|in:new,renewal',
            'previous_application_id' => 'nullable|integer',
            'driver_id' => 'required|exists:drivers,id',
            'franchise_no' => 'nullable|string',
            'sticker_no' => 'nullable|string',
            'operator_name' => 'required|string',
            'ctc_no' => 'required|string',
            'ctc_date_issued' => 'required|date',
            'ctc_place_issued' => 'required|string',
            'franchise_fee' => 'nullable|numeric',
        ]);

        $operator = Auth::user()->operator;

        FranchiseApplication::create([
            'operator_id' => $operator->operator_id,
            'driver_id' => $request->driver_id,
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
            'status' => 'pending', 
        ]);

        return redirect()->route('operator.franchise.index')->with('success', 'Franchise application submitted.');
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
}
