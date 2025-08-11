<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorChangeRequest;
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

        // Provide counts for quick filter badges (optional UI use)
        $counts = [
            'pending' => MotorChangeRequest::where('status', 'pending')->count(),
            'approved' => MotorChangeRequest::where('status', 'approved')->count(),
            'rejected' => MotorChangeRequest::where('status', 'rejected')->count(),
            'all' => MotorChangeRequest::count(),
        ];

        return view('admin.motor-details.change-requests', compact('requests', 'status', 'counts'));
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
}
