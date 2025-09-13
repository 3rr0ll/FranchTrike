<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorDetail;
use App\Models\FranchiseApplication;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Route;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;

class CertificateController extends Controller
{
    public function generateMTOP($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $franchiseApplication->sticker_no ?? $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued 
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y') 
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'Padre Garcia, Batangas',
            'validity' => $motorDetail->validity ?? $franchiseApplication->franchise_end_date ?? now()->addYear()->format('Y-m-d')
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.certificates.MTOP', $data);
        return $pdf->download('MTOP_' . ($data['franchise_no'] !== 'N/A' ? $data['franchise_no'] : 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateMayorsPermit($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued 
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y')
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA'
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.certificates.mayors_permit', $data);
        return $pdf->download('Mayors_Permit_' . ($data['franchise_no'] !== 'N/A' ? $data['franchise_no'] : 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateApplication($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $franchiseApplication->sticker_no ?? $motorDetail->sticker_number ?? 'N/A',
            'toda_president' => $motorDetail->toda_president ?? 'N/A',
            'traffic_division' => $motorDetail->traffic_division ?? 'N/A',
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A'
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.certificates.application', $data);
        return $pdf->download('Application_' . ($data['franchise_no'] !== 'N/A' ? $data['franchise_no'] : 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateAllCertificates($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $franchiseApplication->sticker_no ?? $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued 
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y') 
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA',
            'validity' => $motorDetail->validity ?? $franchiseApplication->franchise_end_date ?? now()->addYear()->format('Y-m-d'),
            'toda_president' => $motorDetail->toda_president ?? 'N/A',
            'traffic_division' => $motorDetail->traffic_division ?? 'N/A',
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A'
        ];

        $pdf = app('dompdf.wrapper')->loadView('admin.certificates.all_certificates', $data);
        return $pdf->download('All_Certificates_' . ($data['franchise_no'] !== 'N/A' ? $data['franchise_no'] : 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function previewMTOP($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $franchiseApplication->sticker_no ?? $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued 
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y') 
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA',
            'validity' => $motorDetail->validity ?? $franchiseApplication->franchise_end_date ?? now()->addYear()->format('Y-m-d')
        ];

        return view('admin.certificates.MTOP', $data);
    }

    public function previewMayorsPermit($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued 
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y')
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA'
        ];

        return view('admin.certificates.mayors_permit', $data);
    }

    public function previewApplication($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route', 'unitMake'])->findOrFail($motorDetailId);
        
        // Auto-fill data from database
        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication->operator;
        $driver = $franchiseApplication->driver;
        $route = $franchiseApplication->route;
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $franchiseApplication,
            'operator' => $operator,
            'driver' => $driver,
            'route' => $route,
            'franchise_no' => $franchiseApplication->franchise_no ?? $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $franchiseApplication->sticker_no ?? $motorDetail->sticker_number ?? 'N/A',
            'toda_president' => $motorDetail->toda_president ?? 'N/A',
            'traffic_division' => $motorDetail->traffic_division ?? 'N/A',
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A'
        ];

        return view('admin.certificates.application', $data);
    }
}
