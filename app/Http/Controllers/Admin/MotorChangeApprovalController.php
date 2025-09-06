<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorChangeRequest;
use App\Models\UnitMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotorChangeApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = MotorChangeRequest::with(['franchiseApplication.motorDetail', 'oldUnitMake', 'newUnitMake'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->get();

        // Get history requests (approved and rejected)
        $historyRequests = MotorChangeRequest::with(['franchiseApplication.motorDetail', 'oldUnitMake', 'newUnitMake'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        // Provide counts for quick filter badges (optional UI use)
        $counts = [
            'pending' => MotorChangeRequest::where('status', 'pending')->count(),
            'approved' => MotorChangeRequest::where('status', 'approved')->count(),
            'rejected' => MotorChangeRequest::where('status', 'rejected')->count(),
            'all' => MotorChangeRequest::count(),
        ];

        return view('admin.motor-details.change-requests', compact('requests', 'historyRequests', 'status', 'counts'));
    }

    public function approve(MotorChangeRequest $motorChange)
    {
        try {
            DB::beginTransaction();

            $franchiseApplication = $motorChange->franchiseApplication;
            if (!$franchiseApplication) {
                DB::rollBack();
                return back()->with('error', 'Franchise application not found for this request.');
            }

            $motorDetail = $franchiseApplication->motorDetail;
            if (!$motorDetail) {
                DB::rollBack();
                return back()->with('error', 'No motor details found for this franchise application.');
            }

            $motorDetail->update([
                'unit_type'     => $motorChange->new_unit_type,
                'unit_make_id'  => $motorChange->new_unit_make_id,
                'motorno'       => $motorChange->new_motorno,
                'chasisno'      => $motorChange->new_chasisno,
                'platenumber'   => $motorChange->new_platenumber,
            ]);

            $motorChange->update(['status' => 'approved']);

            DB::commit();
            return back()->with('success', 'Motor change approved.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    public function reject(MotorChangeRequest $motorChange)
    {
        try {
            $motorChange->update(['status' => 'rejected']);
            return back()->with('success', 'Motor change rejected.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }

    public function inputDetails(MotorChangeRequest $motorChange)
    {
        $unitMakes = UnitMake::all();
        return view('admin.motor-details.input-new-details', compact('motorChange', 'unitMakes'));
    }

    public function storeNewDetails(Request $request, MotorChangeRequest $motorChange)
    {
        $request->validate([
            'new_unit_type' => 'required|string|max:255',
            'new_unit_make_id' => 'required|exists:unit_makes,id',
            'new_motorno' => 'required|string|max:255',
            'new_chasisno' => 'required|string|max:255',
            'new_platenumber' => 'required|string|max:255',
        ]);

        try {
            $motorChange->update([
                'new_unit_type' => $request->new_unit_type,
                'new_unit_make_id' => $request->new_unit_make_id,
                'new_motorno' => $request->new_motorno,
                'new_chasisno' => $request->new_chasisno,
                'new_platenumber' => $request->new_platenumber,
            ]);

            return redirect()->route('admin.motor-change.index')
                ->with('success', 'New motor details saved successfully. You can now approve or reject the request.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to save new details: ' . $e->getMessage());
        }
    }
}
