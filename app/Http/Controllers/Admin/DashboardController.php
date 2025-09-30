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
            'expired' => FranchiseApplication::where('status', 'expired')->count(),
        ];

        // Recent Applications
        $recentApplications = FranchiseApplication::with(['operator', 'driver'])
            ->latest()
            ->take(6)
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


        // Get the first day of the month 5 months ago (to include this month and previous 5)
        $startMonth = now()->copy()->startOfMonth()->subMonths(5);

        // Applications Over Time
        $applicationsOverTime = FranchiseApplication::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startMonth)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Applications Pending Review Over Time
        $pendingOverTime = FranchiseApplication::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'under_review')
            ->where('created_at', '>=', $startMonth)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Operators Over Time
        $operatorsOverTime = Operator::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startMonth)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Drivers Over Time
        $driversOverTime = Driver::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startMonth)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        // Prepare labels and data arrays for the last 6 months
        $labels = [];
        $data = [];
        $pendingData = [];
        $operatorsData = [];
        $driversData = [];

        for ($i = 0; $i < 6; $i++) {
            $date = $startMonth->copy()->addMonths($i);
            $key = $date->format('Y-m');
            $labels[] = $date->format('M');
            $data[] = isset($applicationsOverTime[$key]) ? $applicationsOverTime[$key]->count : 0;
            $pendingData[] = isset($pendingOverTime[$key]) ? $pendingOverTime[$key]->count : 0;
            $operatorsData[] = isset($operatorsOverTime[$key]) ? $operatorsOverTime[$key]->count : 0;
            $driversData[] = isset($driversOverTime[$key]) ? $driversOverTime[$key]->count : 0;
        }

        return view('admin.dashboard', compact(
            'totalApplications',
            'totalOperators',
            'totalDrivers',
            'pendingReview',
            'statusCounts',
            'recentApplications',
            'todayStats',
            'documentStats',
            'systemStats',
            'labels',
            'data',
            'pendingData',
            'operatorsData',
            'driversData'
        ));
    }
}
