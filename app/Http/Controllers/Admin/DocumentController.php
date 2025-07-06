<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function viewOperatorDocuments(Operator $operator)
    {
        $documents = OperatorDocument::where('operator_id', $operator->operator_id)
            ->with('documentType')
            ->get();

        return view('admin.documents.operators.show', compact('operator', 'documents'));
    }

    public function viewDriverDocuments(Driver $driver)
    {
        $documents = DriverDocument::where('driver_id', $driver->driver_id)
            ->with('documentType')
            ->get();

        return view('admin.documents.drivers.show', compact('driver', 'documents'));
    }

    /**
     * Update operator document verification status
     */
    public function updateOperatorDocumentStatus(Request $request, $documentId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string|max:500'
        ]);

        $document = OperatorDocument::findOrFail($documentId);
        $document->update([
            'status' => $request->status,
            'verified_by' => Auth::user()->id,
            'verified_at' => now()
        ]);

        return back()->with('success', 'Operator document verification status updated successfully.');
    }

    public function updateDriverDocumentStatus(Request $request, $documentId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string|max:500'
        ]);

        $document = DriverDocument::findOrFail($documentId);
        $document->update([
            'status' => $request->status,
            'verified_by' => Auth::user()->id,
            'verified_at' => now()
        ]);

        return back()->with('success', 'Driver document verification status updated successfully.');
    }

    /**
    
     */
    public function verifyOperatorDocument(Request $request, OperatorDocument $document)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $document->status = $request->status;
        $document->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $document->save();

        return redirect()->back()->with('status', 'Document has been ' . $request->status . ' successfully.');
    }

    public function verifyDriverDocument(Request $request, DriverDocument $document)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $document->status = $request->status;
        $document->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        return redirect()->back()->with('status', 'Document has been ' . $request->status . ' successfully.');
    }
}
