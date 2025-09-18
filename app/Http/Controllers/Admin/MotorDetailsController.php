<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorDetail;
use App\Models\FranchiseApplication;
use App\Models\UnitMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotorDetailsController extends Controller
{
    public function index(Request $request)
    {
        $query = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'unitMake']);

        // Filter by franchise application status
        if ($request->filled('application_status')) {
            $query->whereHas('franchiseApplication', function ($q) use ($request) {
                $q->where('status', $request->application_status);
            });
        }

        // Filter by unit type
        if ($request->filled('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }

        // Filter by unit make
        if ($request->filled('unit_make_id')) {
            $query->where('unit_make_id', $request->unit_make_id);
        }

        // Search by plate number, motor number, or chasis number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('platenumber', 'like', "%{$search}%")
                  ->orWhere('motorno', 'like', "%{$search}%")
                  ->orWhere('chasisno', 'like', "%{$search}%");
            });
        }

        $motorDetails = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_units' => MotorDetail::count(),
            'motocabs' => MotorDetail::where('unit_type', 'motocab')->count(),
            'tricycles' => MotorDetail::where('unit_type', 'tricycle')->count(),
            'approved_units' => MotorDetail::whereHas('franchiseApplication', function ($q) {
                $q->where('status', 'approved');
            })->count(),
        ];

        $unitMakes = UnitMake::all();
        $applicationStatuses = ['submitted', 'under_review', 'approved', 'rejected'];

        return view('admin.motor-details.index', compact('motorDetails', 'stats', 'unitMakes', 'applicationStatuses'));
    }

    public function show(MotorDetail $motorDetail)
    {
        $motorDetail->load(['franchiseApplication.operator', 'franchiseApplication.driver', 'unitMake']);
        
        return view('admin.motor-details.show', compact('motorDetail'));
    }

    public function edit(MotorDetail $motorDetail)
    {
        $motorDetail->load(['franchiseApplication', 'unitMake']);
        $unitMakes = UnitMake::all();
        $unitTypes = ['motocab', 'tricycle'];
        
        return view('admin.motor-details.edit', compact('motorDetail', 'unitMakes', 'unitTypes'));
    }

    public function update(Request $request, MotorDetail $motorDetail)
    {
        $request->validate([
            'unit_type' => 'required|in:motocab,tricycle',
            'unit_make_id' => 'required|exists:unit_makes,id',
            'motorno' => 'required|string|max:50',
            'chasisno' => 'required|string|max:50',
            'platenumber' => 'required|string|max:20',
        ]);

        \App\Helpers\ActivityLogger::log(
            'motor_detail',
            'updated',
            'Motor details for franchise id ' . ($motorDetail->franchiseApplication->id ?? 'N/A') . ' updated.',
            ['motor_detail_id' => $motorDetail->id]
        );
       

        $motorDetail->update($request->all());

        return redirect()->route('admin.motor-details.index')
            ->with('success', 'Motor details updated successfully.');
    }

    public function destroy(MotorDetail $motorDetail)
    {
        $motorDetail->delete();

        \App\Helpers\ActivityLogger::log(
            'motor_detail',
            'deleted',
            'Motor detail with ID ' . $motorDetail->id . ' deleted.',
            ['motor_detail_id' => $motorDetail->id]
        );
        return redirect()->route('admin.motor-details.index')
            ->with('success', 'Motor detail deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'motor_detail_ids' => 'required|array',
            'motor_detail_ids.*' => 'exists:motor_details,id',
            'unit_type' => 'nullable|in:motocab,tricycle',
            'unit_make_id' => 'nullable|exists:unit_makes,id',
        ]);

        $motorDetails = MotorDetail::whereIn('id', $request->motor_detail_ids)->get();

        foreach ($motorDetails as $motorDetail) {
            $updateData = [];
            
            if ($request->filled('unit_type')) {
                $updateData['unit_type'] = $request->unit_type;
            }
            
            if ($request->filled('unit_make_id')) {
                $updateData['unit_make_id'] = $request->unit_make_id;
            }

            if (!empty($updateData)) {
                $motorDetail->update($updateData);
            }
        }

        return redirect()->route('admin.motor-details.index')
            ->with('success', count($motorDetails) . ' motor details updated successfully.');
    }

    public function statistics()
    {
        $stats = [
            'total_units' => MotorDetail::count(),
            'by_type' => MotorDetail::select('unit_type', DB::raw('count(*) as count'))
                ->groupBy('unit_type')
                ->pluck('count', 'unit_type')
                ->toArray(),
            'by_make' => MotorDetail::with('unitMake')
                ->select('unit_make_id', DB::raw('count(*) as count'))
                ->groupBy('unit_make_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->unitMake->name ?? 'Unknown' => $item->count];
                })
                ->toArray(),
            'approved_units' => MotorDetail::whereHas('franchiseApplication', function ($q) {
                $q->where('status', 'approved');
            })->count(),
        ];

        return response()->json($stats);
    }

    public function export(Request $request)
    {
        $query = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'unitMake']);

        if ($request->filled('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }

        if ($request->filled('unit_make_id')) {
            $query->where('unit_make_id', $request->unit_make_id);
        }

        $motorDetails = $query->get();

        $filename = 'motor_details_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($motorDetails) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Application Number', 'Operator Name', 'Driver Name', 
                'Unit Type', 'Unit Make', 'Motor No', 'Chasis No', 'Plate Number',
                'Application Status', 'Created At'
            ]);

            foreach ($motorDetails as $motorDetail) {
                fputcsv($file, [
                    $motorDetail->id,
                    $motorDetail->franchiseApplication->application_number ?? 'N/A',
                    $motorDetail->franchiseApplication->operator->name ?? 'N/A',
                    $motorDetail->franchiseApplication->driver->name ?? 'N/A',
                    $motorDetail->unit_type,
                    $motorDetail->unitMake->name ?? 'N/A',
                    $motorDetail->motorno,
                    $motorDetail->chasisno,
                    $motorDetail->platenumber,
                    $motorDetail->franchiseApplication->status ?? 'N/A',
                    $motorDetail->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 