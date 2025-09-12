<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorDetail;
use App\Models\FranchiseApplication;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\Route;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function generateMTOP($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $motorDetail->amount ?? 'N/A',
            'ctc_no' => $motorDetail->ctc_number ?? 'N/A',
            'date_issued' => $motorDetail->date_issued ?? now()->format('Y-m-d'),
            'place_issued' => $motorDetail->place_issued ?? 'City of Tagbilaran',
            'validity' => $motorDetail->validity ?? now()->addYear()->format('Y-m-d')
        ];

        $pdf = Pdf::loadView('admin.certificates.MTOP', $data);
        return $pdf->download('MTOP_' . ($motorDetail->franchise_number ?? 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateMayorsPermit($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $motorDetail->amount ?? 'N/A',
            'ctc_no' => $motorDetail->ctc_number ?? 'N/A',
            'date_issued' => $motorDetail->date_issued ?? now()->format('Y-m-d'),
            'place_issued' => $motorDetail->place_issued ?? 'City of Tagbilaran'
        ];

        $pdf = Pdf::loadView('admin.certificates.mayors_permit', $data);
        return $pdf->download('Mayors_Permit_' . ($motorDetail->franchise_number ?? 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateApplication($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $motorDetail->sticker_number ?? 'N/A',
            'toda_president' => $motorDetail->toda_president ?? 'N/A',
            'traffic_division' => $motorDetail->traffic_division ?? 'N/A',
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A'
        ];

        $pdf = Pdf::loadView('admin.certificates.application', $data);
        return $pdf->download('Application_' . ($motorDetail->franchise_number ?? 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function generateAllCertificates($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $motorDetail->amount ?? 'N/A',
            'ctc_no' => $motorDetail->ctc_number ?? 'N/A',
            'date_issued' => $motorDetail->date_issued ?? now()->format('Y-m-d'),
            'place_issued' => $motorDetail->place_issued ?? 'City of Tagbilaran',
            'validity' => $motorDetail->validity ?? now()->addYear()->format('Y-m-d'),
            'toda_president' => $motorDetail->toda_president ?? 'N/A',
            'traffic_division' => $motorDetail->traffic_division ?? 'N/A',
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A'
        ];

        $pdf = Pdf::loadView('admin.certificates.all_certificates', $data);
        return $pdf->download('All_Certificates_' . ($motorDetail->franchise_number ?? 'Motor_' . $motorDetail->id) . '.pdf');
    }

    public function previewMTOP($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'sticker_no' => $motorDetail->sticker_number ?? 'N/A',
            'case_no' => $motorDetail->case_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $motorDetail->amount ?? 'N/A',
            'ctc_no' => $motorDetail->ctc_number ?? 'N/A',
            'date_issued' => $motorDetail->date_issued ?? now()->format('Y-m-d'),
            'place_issued' => $motorDetail->place_issued ?? 'City of Tagbilaran',
            'validity' => $motorDetail->validity ?? now()->addYear()->format('Y-m-d')
        ];

        return view('admin.certificates.MTOP', $data);
    }

    public function previewMayorsPermit($motorDetailId)
    {
        $motorDetail = MotorDetail::with(['franchiseApplication.operator', 'franchiseApplication.driver', 'franchiseApplication.route'])->findOrFail($motorDetailId);
        
        $data = [
            'motorDetail' => $motorDetail,
            'franchiseApplication' => $motorDetail->franchiseApplication,
            'operator' => $motorDetail->franchiseApplication->operator,
            'driver' => $motorDetail->franchiseApplication->driver,
            'route' => $motorDetail->franchiseApplication->route,
            'franchise_no' => $motorDetail->franchise_number ?? 'N/A',
            'or_no' => $motorDetail->or_number ?? 'N/A',
            'amount' => $motorDetail->amount ?? 'N/A',
            'ctc_no' => $motorDetail->ctc_number ?? 'N/A',
            'certificate' => $certificate,
            'tricycle' => $certificate->tricycle,
            'driver' => $certificate->tricycle->driver,
            'route' => $certificate->tricycle->route,
            'franchise_no' => $certificate->franchise_number,
            'or_no' => $certificate->or_number,
            'amount' => $certificate->amount,
            'ctc_no' => $certificate->ctc_number,
            'date_issued' => $certificate->date_issued,
            'place_issued' => $certificate->place_issued
        ];

        return view('admin.certificates.mayors_permit', $data);
    }

    public function previewApplication($certificateId)
    {
        $certificate = Certificate::with(['tricycle.driver', 'tricycle.route'])->findOrFail($certificateId);
        
        $data = [
            'certificate' => $certificate,
            'tricycle' => $certificate->tricycle,
            'driver' => $certificate->tricycle->driver,
            'route' => $certificate->tricycle->route,
            'franchise_no' => $certificate->franchise_number,
            'sticker_no' => $certificate->sticker_number,
            'toda_president' => $certificate->toda_president,
            'traffic_division' => $certificate->traffic_division,
            'pfuc_chairperson' => $certificate->pfuc_chairperson
        ];

        return view('admin.certificates.application', $data);
    }
}
