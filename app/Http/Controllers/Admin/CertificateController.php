<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MotorDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function previewMTOP($motorDetailId, Request $request)
    {
        $motorDetail = MotorDetail::with([
            'franchiseApplication.operator',
            'franchiseApplication.driver',
            'franchiseApplication.route',
            'unitMake'
        ])->findOrFail($motorDetailId);

        // Get OR number from modal input
        $orNo = $request->query('or_no');
        $payment = $orNo ? Payment::where('or_no', $orNo)->first() : null;

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
            'or_no' => $payment?->or_no ?? $motorDetail->or_number ?? 'N/A',
            'amount' => $payment?->amount_paid ?? $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y')
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA',
            'validity' => $motorDetail->validity ?? $franchiseApplication->franchise_end_date ?? now()->addYear()->format('Y-m-d')
        ];

        return view('admin.certificates.MTOP', $data);
    }

    public function previewMayorsPermit($motorDetailId, Request $request)
    {
        $motorDetail = MotorDetail::with([
            'franchiseApplication.operator',
            'franchiseApplication.driver',
            'franchiseApplication.route',
            'unitMake'
        ])->findOrFail($motorDetailId);

        $orNo = $request->query('or_no');
        $payment = $orNo ? Payment::where('or_no', $orNo)->first() : null;

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
            'or_no' => $payment?->or_no ?? $motorDetail->or_number ?? 'N/A',
            'amount' => $payment?->amount_paid ?? $franchiseApplication->franchise_fee ?? $motorDetail->amount ?? 'N/A',
            'ctc_no' => $franchiseApplication->ctc_no ?? 'N/A',
            'date_issued' => $motorDetail->date_issued
                ? \Carbon\Carbon::parse($motorDetail->date_issued)->format('F d, Y')
                : now()->format('F d, Y'),
            'place_issued' => $motorDetail->place_issued ?? 'PADRE GARCIA'
        ];

        return view('admin.certificates.mayors_permit', $data);
    }

    public function previewApplication($motorDetailId, Request $request)
    {
        $motorDetail = MotorDetail::with([
            'franchiseApplication.operator',
            'franchiseApplication.driver',
            'franchiseApplication.route',
            'unitMake'
        ])->findOrFail($motorDetailId);

        $orNo = $request->query('or_no');
        $payment = $orNo ? Payment::where('or_no', $orNo)->first() : null;

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
            'pfuc_chairperson' => $motorDetail->pfuc_chairperson ?? 'N/A',
            'or_no' => $payment?->or_no ?? 'N/A',
            'amount' => $payment?->amount_paid ?? 'N/A'
        ];

        return view('admin.certificates.application', $data);
    }

    public function logPrint(Request $request, $motorDetailId)
    {
        $motorDetail = MotorDetail::with('franchiseApplication.operator')->findOrFail($motorDetailId);

        $franchiseApplication = $motorDetail->franchiseApplication;
        $operator = $franchiseApplication?->operator;

        ActivityLogger::log(
            'certificate',
            'printed',
            'Certificate printed by Admin',
            [
                'motor detail id' => $motorDetail->id,
                'franchise application id' => $franchiseApplication?->id,
                'operator id' => $operator?->operator_id,
                'printed by admin name' => Auth::user()->name,
            ]
        );

        return response()->json(['status' => 'success']);
    }
}
