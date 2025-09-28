<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\MotorChangeRequest;
use App\Models\MotorDetail;
use App\Models\UnitMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotorChangeController extends Controller
{
    /**
     * Show the motor change requests for a specific franchise application under the operator.
     */
    public function index(Request $request)
    {
        $operator = Auth::user()->operator;

        $franchiseId = $request->input('franchise_application_id');

        // All franchise applications owned by operator
        $franchiseApplications = FranchiseApplication::where('operator_id', $operator->id)->get();

        // Fetch requests only tied to this operator’s franchises
        $query = MotorChangeRequest::with(['franchiseApplication', 'oldUnitMake'])
            ->whereHas('franchiseApplication', function ($q) use ($operator) {
                $q->where('operator_id', $operator->operator_id); // 👈 match operator_id from schema
            });

        if ($franchiseId) {
            $query->where('franchise_application_id', $franchiseId);
        }

        $requests = $query->latest()->get();

        return view('operator.motor-change.index', [
            'requests' => $requests,
            'franchiseApplications' => $franchiseApplications,
            'selectedFranchiseId' => $franchiseId,
        ]);
    }

    public function create($franchiseId)
    {
        $application = FranchiseApplication::findOrFail($franchiseId);

        // Make sure motor details exist
        $motorDetail = $application->motorDetail;
        if (!$motorDetail) {
            return redirect()->route('operator.franchise.index')
                ->with('error', 'No motor details found for this franchise.');
        }

        // Check if there's already a pending request for this franchise
        $existingRequest = MotorChangeRequest::where('franchise_application_id', $application->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('operator.franchise.index')
                ->with('error', 'You already have a pending motor change request for this franchise.');
        }

        $userId = Auth::check() ? Auth::id() : null;


        // Directly create the motor change request with only old details
        MotorChangeRequest::create([
            'franchise_application_id' => $application->id,
            'old_unit_type' => $motorDetail->unit_type,
            'old_unit_make_id' => $motorDetail->unit_make_id,
            'old_motorno' => $motorDetail->motorno,
            'old_chasisno' => $motorDetail->chasisno,
            'old_platenumber' => $motorDetail->platenumber,
            'new_unit_type' => null, // Admin will input this
            'new_unit_make_id' => null, // Admin will input this
            'new_motorno' => null, // Admin will input this
            'new_chasisno' => null, // Admin will input this
            'new_platenumber' => null, // Admin will input this
            'status' => 'pending',
        ]);

        \App\Helpers\ActivityLogger::log(
            'motor_change_request',
            'submitted',
            'Operator submitted a motor change request.',
            [
                'franchise_application_id' => $application->id,
                'operator_id' => $application->operator_id,
                'old_unit_type' => $motorDetail->unit_type,
                'old_unit_make_id' => $motorDetail->unit_make_id,
                'old_motorno' => $motorDetail->motorno,
                'old_chasisno' => $motorDetail->chasisno,
                'old_platenumber' => $motorDetail->platenumber,
                'submitted_by' => Auth::user()->name,
                'user_id' => $userId,
            ]
        );

        return redirect()->route('operator.franchise.index')
            ->with('success', 'Motor change request submitted successfully! Please prepare for physical evaluation.');
    }
}
