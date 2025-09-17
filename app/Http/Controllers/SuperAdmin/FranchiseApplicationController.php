<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationLog;

class FranchiseApplicationController extends Controller
{
    /**
     * Display a listing of all franchise applications for superadmin.
     */
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
        ];

        return view('superadmin.franchise.index', compact('applications', 'statusCounts'));
    }



    public function show(FranchiseApplication $franchiseApplication)
    {
        $franchiseApplication->load(['operator', 'driver', 'reviewer', 'motorDetail.unitMake', 'route','logs.updatedBy']);

        return view('superadmin.franchise.show', compact('franchiseApplication'));
    }


}
