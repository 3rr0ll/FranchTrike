<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->get();
    
        return view('superadmin.activity.index', compact('logs'));
    }
}
