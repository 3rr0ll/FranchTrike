<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Driver;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

class DocumentController extends Controller
{
    public function index()
    {
        $driverDocs = DriverDocument::with(['driver', 'documentType'])
            ->get()
            ->map(function ($doc) {
                // Check if document is physically submitted by checking if document_name contains the pattern
                $isPhysicallySubmitted = str_contains($doc->document_name, 'Physically Submitted');
                $displayName = $isPhysicallySubmitted ? $doc->document_name : ($doc->documentType ? $doc->documentType->name : 'N/A');
                
                return [
                    'id' => $doc->id,
                    'user_name' => $doc->driver ? $doc->driver->first_name . ' ' . $doc->driver->last_name : 'N/A',
                    'user_type' => 'Driver',
                    'document_type' => $displayName,
                    'status' => $doc->status,
                    'created_at' => $doc->created_at,
                    'url' => $doc->file_url ?: $doc->full_file_url,
                    'is_physically_submitted' => $isPhysicallySubmitted,
                ];
            });

        $operatorDocs = OperatorDocument::with(['operator', 'documentType'])
            ->get()
            ->map(function ($doc) {
                // Check if document is physically submitted by checking if document_name contains the pattern
                $isPhysicallySubmitted = str_contains($doc->document_name, 'Physically Submitted');
                $displayName = $isPhysicallySubmitted ? $doc->document_name : ($doc->documentType ? $doc->documentType->name : 'N/A');
                
                return [
                    'id' => $doc->id,
                    'user_name' => $doc->operator ? ($doc->operator->full_name ?? ($doc->operator->first_name . ' ' . $doc->operator->last_name)) : 'N/A',
                    'user_type' => 'Operator',
                    'document_type' => $displayName,
                    'status' => $doc->status,
                    'created_at' => $doc->created_at,
                    'url' => $doc->file_url ?: $doc->full_file_url,
                    'is_physically_submitted' => $isPhysicallySubmitted,
                ];
            });

        // Merge into one collection
        $documents = $driverDocs->merge($operatorDocs);

        return view('admin.documents.index', compact('documents'));
    }

    public function viewOperatorDocuments($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $operator = Operator::where('operator_id', $id)->firstOrFail();

        $documents = OperatorDocument::where('operator_id', $operator->operator_id)
            ->with('documentType')
            ->get()
            ->map(function ($document) {
                $document->display_url = $document->file_url ?: $document->full_file_url;
                return $document;
            });

        return view('admin.documents.operators.show', compact('operator', 'documents'));
    }

    public function viewDriverDocuments($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $driver = Driver::where('driver_id', $id)->firstOrFail();

        $documents = DriverDocument::where('driver_id', $driver->driver_id)
            ->with('documentType')
            ->get()
            ->map(function ($document) {
                $document->display_url = $document->file_url ?: $document->full_file_url;
                return $document;
            });

        return view('admin.documents.drivers.show', compact('driver', 'documents'));
    }

    /**
     * View a specific operator document
     */
    public function viewOperatorDocument(OperatorDocument $document)
    {
        $document->load(['documentType', 'operator']);
        $displayUrl = $document->file_url ?: $document->full_file_url;

        return view('admin.documents.operators.view', compact('document', 'displayUrl'));
    }

    /**
     * View a specific driver document
     */
    public function viewDriverDocument(DriverDocument $document)
    {
        $document->load(['documentType', 'driver']);
        $displayUrl = $document->file_url ?: $document->full_file_url;

        return view('admin.documents.drivers.view', compact('document', 'displayUrl'));
    }

    /**
     * Get document for modal viewing (AJAX)
     */
    public function getOperatorDocumentForModal(OperatorDocument $document)
    {
        $displayUrl = $document->file_url ?: $document->full_file_url;

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'name' => $document->document_name,
                'type' => $document->documentType->name,
                'url' => $displayUrl,
                'file_type' => $document->file_type,
                'is_image' => $document->is_image,
                'is_pdf' => $document->is_pdf,
                'status' => $document->status,
                'rejection_reason' => $document->rejection_reason,
            ]
        ]);
    }

    /**
     * Get driver document for modal viewing (AJAX)
     */
    public function getDriverDocumentForModal(DriverDocument $document)
    {
        $displayUrl = $document->file_url ?: $document->full_file_url;

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'name' => $document->document_name,
                'type' => $document->documentType->name,
                'url' => $displayUrl,
                'file_type' => $document->file_type,
                'is_image' => $document->is_image,
                'is_pdf' => $document->is_pdf,
                'status' => $document->status,
                'rejection_reason' => $document->rejection_reason,
                'driver_name' => $document->driver ? $document->driver->first_name . ' ' . $document->driver->last_name : 'N/A',
            ]
        ]);
    }

    /**
     * Update operator document verification status
     */
    public function verifyOperatorDocument(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $document = OperatorDocument::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $document->status = $request->status;
        $document->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        // Activity log
        \App\Helpers\ActivityLogger::log(
            'operator document',
            'status updated',
            'Operator document status updated to "' . $request->status . '".',
            [
                'operator document id' => $document->id,
                'status' => $request->status,
                'verified_by' => Auth::user() ? Auth::user()->name : null,
                'rejection reason' => $request->rejection_reason ?? null,
            ]
        );

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Document has been ' . $request->status . ' successfully.',
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with('status', 'Document has been ' . $request->status . ' successfully.');
    }

    public function verifyDriverDocument(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $document = DriverDocument::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $document->status = $request->status;
        $document->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        // Activity log
        \App\Helpers\ActivityLogger::log(
            'driver document',
            'status updated',
            'Driver document status updated to "' . $request->status . '".',
            [
                'driver document id' => $document->id,
                'status' => $request->status,
                'verified by' => Auth::user() ? Auth::user()->name : null,
                'rejection reason' => $request->rejection_reason ?? null,
            ]
        );

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Document has been ' . $request->status . ' successfully.',
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with('status', 'Document has been ' . $request->status . ' successfully.');
    }

    /**
     * Download document (redirect to Cloudinary URL)
     */
    public function downloadOperatorDocument(OperatorDocument $document)
    {
        $displayUrl = $document->file_url ?: $document->full_file_url;

        if (!$displayUrl) {
            abort(404, 'Document not found');
        }

        return redirect($displayUrl);
    }

    /**
     * Download driver document (redirect to Cloudinary URL)
     */
    public function downloadDriverDocument(DriverDocument $document)
    {
        $displayUrl = $document->file_url ?: $document->full_file_url;

        if (!$displayUrl) {
            abort(404, 'Document not found');
        }

        return redirect($displayUrl);
    }
}