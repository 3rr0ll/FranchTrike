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
        $documents = DriverDocument::where('driver_id', $driver->id)
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

    /**
     * Update driver document verification status
     */
    public function verifyOperatorDocument(Request $request, OperatorDocument $document)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $document->status = $request->status;
        $document->save();

        return back()->with('success', 'Operator document status updated.');
    }

    public function verifyDriverDocument(Request $request, DriverDocument $document)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $document->status = $request->status;
        $document->save();

        return back()->with('success', 'Driver document status updated.');
    }
}
