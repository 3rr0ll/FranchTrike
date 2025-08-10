<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorChangeRequest;
use App\Models\MotorDetail;

class MotorChangeApprovalController extends Controller
{
    public function index()
    {
        $requests = MotorChangeRequest::latest()->get();
        return view('admin.motor-change.index', compact('requests'));
    }

    public function approve(MotorChangeRequest $request)
    {
        $motorDetail = $request->franchiseApplication->motorDetail;
        $motorDetail->update([
            'unit_type' => $request->new_unit_type,
            'unit_make_id' => $request->new_unit_make_id,
            'motorno' => $request->new_motorno,
            'chasisno' => $request->new_chasisno,
            'platenumber' => $request->new_platenumber,
        ]);

        $request->update(['status' => 'approved']);
        return back()->with('success', 'Motor change approved.');
    }

    public function reject(MotorChangeRequest $request)
    {
        $request->update(['status' => 'rejected']);
        return back()->with('success', 'Motor change rejected.');
    }
}
