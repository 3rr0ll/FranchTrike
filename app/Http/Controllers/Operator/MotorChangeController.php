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

        $unitMakes = UnitMake::all();

        return view('operator.motor-change.create', compact('application', 'motorDetail', 'unitMakes'));
    }

    public function store(Request $request, $franchiseId)
    {
        $application = FranchiseApplication::findOrFail($franchiseId);

        $motorDetail = $application->motorDetail;
        if (!$motorDetail) {
            return redirect()->route('operator.franchise.index')
                ->with('error', 'Cannot request change. No existing motor details found.');
        }

        $request->validate([
            'new_unit_type' => 'required|string|max:255',
            'new_unit_make_id' => 'required|exists:unit_makes,id',
            'new_motorno' => 'required|string|max:255',
            'new_chasisno' => 'required|string|max:255',
            'new_platenumber' => 'required|string|max:255',
        ]);

        MotorChangeRequest::create([
            'franchise_application_id' => $application->id,
            'old_unit_type' => $motorDetail->unit_type,
            'old_unit_make_id' => $motorDetail->unit_make_id,
            'old_motorno' => $motorDetail->motorno,
            'old_chasisno' => $motorDetail->chasisno,
            'old_platenumber' => $motorDetail->platenumber,
            'new_unit_type' => $request->new_unit_type,
            'new_unit_make_id' => $request->new_unit_make_id,
            'new_motorno' => $request->new_motorno,
            'new_chasisno' => $request->new_chasisno,
            'new_platenumber' => $request->new_platenumber,
            'status' => 'pending',
        ]);

        return redirect()->route('operator.franchise.index')
            ->with('success', 'Motor change request submitted and awaiting admin approval.');
    }
}
