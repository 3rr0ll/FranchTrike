<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\FranchiseApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOperators = Operator::count();
        $totalDrivers = Driver::count();
        $pendingApplications = FranchiseApplication::where('status', 'pending')->count();
        $totalFranchises = FranchiseApplication::count();
        return view('admin.dashboard', compact('totalOperators', 'totalDrivers', 'pendingApplications', 'totalFranchises'));
    }
}
