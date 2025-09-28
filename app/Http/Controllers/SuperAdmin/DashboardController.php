<?php

// app/Http/Controllers/SuperAdmin/DashboardController.php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\FranchiseApplication;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard
        $stats = [
            'total_users' => User::count(),
            'admins' => User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->count(),
            'operators' => User::whereHas('role', function($query) {
                $query->where('name', 'operator');
            })->count(),
            'superadmins' => User::whereHas('role', function($query) {
                $query->where('name', 'superadmin');
            })->count(),
            'total_operators' => Operator::count(),
            'total_drivers' => Driver::count(),
            'total_applications' => FranchiseApplication::count(),
            'pending_applications' => FranchiseApplication::where('status', 'pending')->count(),
            'approved_applications' => FranchiseApplication::where('status', 'approved')->count(),
            'rejected_applications' => FranchiseApplication::where('status', 'rejected')->count(),
            // New stats
            'approved_franchises' => FranchiseApplication::where('status', 'approved')->whereDate('franchise_end_date', '>=', now())->count(),
            'expired_franchises' => FranchiseApplication::where('status', 'approved')->whereDate('franchise_end_date', '<', now())->count(),
        ];

        // Get recent users
        $recent_users = User::with('role')->latest()->take(5)->get();
        
        // Get recent applications
        $recent_applications = FranchiseApplication::with(['operator', 'driver'])
            ->latest()
            ->take(5)
            ->get();

        // Calendar/timeline data: all approved franchises with start/end dates
        $franchise_events = FranchiseApplication::where('status', 'approved')
            ->whereNotNull('franchise_start_date')
            ->whereNotNull('franchise_end_date')
            ->get(['id', 'application_number', 'franchise_start_date', 'franchise_end_date', 'operator_id']);

        return view('superadmin.dashboard', compact('stats', 'recent_users', 'recent_applications', 'franchise_events'));
    }

    public function search()
    {
        $q = request('q');
        $users = collect();
        $applications = collect();
        $documents = collect();
        if ($q) {
            $users = User::where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->with('role')
                ->get();
            $applications = FranchiseApplication::where('application_number', 'like', "%$q%")
                ->orWhere('franchise_no', 'like', "%$q%")
                ->orWhere('operator_name', 'like', "%$q%")
                ->with(['operator', 'driver'])
                ->get();
            $documents = \App\Models\OperatorDocument::where('document_number', 'like', "%$q%")
                ->orWhereHas('operator', function($query) use ($q) {
                    $query->where('full_name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact_number', 'like', "%$q%")
                        ->orWhere('address', 'like', "%$q%")
                        ;
                })
                ->with('operator')
                ->get();
        }
        return view('superadmin.search', compact('q', 'users', 'applications', 'documents'));
    }
}
