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

        return redirect()->route('operator.franchise.index')
            ->with('success', 'Motor change request submitted successfully! Please prepare for physical evaluation.');
    }

}
