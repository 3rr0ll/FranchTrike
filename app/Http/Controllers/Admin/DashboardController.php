<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\FranchiseApplication;
use App\Models\Route;
use App\Models\User;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $totalApplications = FranchiseApplication::count();
        $totalOperators = Operator::count();
        $totalDrivers = Driver::count();
        $pendingReview = FranchiseApplication::whereIn('status', ['submitted', 'under_review'])->count();

        // Status Distribution
        $statusCounts = [
            'submitted' => FranchiseApplication::where('status', 'submitted')->count(),
            'under_review' => FranchiseApplication::where('status', 'under_review')->count(),
            'approved' => FranchiseApplication::where('status', 'approved')->count(),
            'rejected' => FranchiseApplication::where('status', 'rejected')->count(),
        ];

        // Recent Applications
        $recentApplications = FranchiseApplication::with(['operator', 'driver'])
            ->latest()
            ->take(5)
            ->get();

        // Today's Statistics
        $today = Carbon::today();
        $todayStats = [
            'new_applications' => FranchiseApplication::whereDate('created_at', $today)->count(),
            'applications_reviewed' => FranchiseApplication::whereDate('reviewed_at', $today)->count(),
            'approved_today' => FranchiseApplication::where('status', 'approved')
                ->whereDate('reviewed_at', $today)->count(),
            'rejected_today' => FranchiseApplication::where('status', 'rejected')
                ->whereDate('reviewed_at', $today)->count(),
        ];

        // Document Statistics
        $documentStats = [
            'pending_review' => OperatorDocument::where('status', 'pending')->count() + 
                               DriverDocument::where('status', 'pending')->count(),
            'approved' => OperatorDocument::where('status', 'approved')->count() + 
                         DriverDocument::where('status', 'approved')->count(),
            'rejected' => OperatorDocument::where('status', 'rejected')->count() + 
                         DriverDocument::where('status', 'rejected')->count(),
        ];

        // System Statistics
        $systemStats = [
            'active_users' => User::where('is_active', true)->count(),
            'total_routes' => Route::count(),
        ];

        return view('admin.dashboard', compact(
            'totalApplications',
            'totalOperators', 
            'totalDrivers',
            'pendingReview',
            'statusCounts',
            'recentApplications',
            'todayStats',
            'documentStats',
            'systemStats'
        ));
    }
}
