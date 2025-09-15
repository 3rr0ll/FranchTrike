<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\OperatorDocument;
use App\Models\DriverDocument;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentSubmissionController extends Controller
{
    /**
     * Get the operator record for the current user
     */
    private function getCurrentOperator()
    {
        return Operator::where('user_id', Auth::id())->first();
    }

    /**
     * Show operator document submission form
     */
    public function createOperatorDocuments()
    {
        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $documentTypes = DocumentType::forOperator()->get();
        $submittedDocuments = OperatorDocument::where('operator_id', $operator->operator_id)
            ->with('documentType')
            ->get()
            ->keyBy('document_type_id');

        return view('operator.documents.operator.create', compact('documentTypes', 'submittedDocuments'));
    }

    /**
     * Show driver document submission form
     */
    public function createDriverDocuments(Request $request)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        // Get drivers under the current operator
        $drivers = Driver::where('operator_id', $operator->operator_id)->get();

        $documentTypes = DocumentType::forDriver()->get();

        $selectedDriverId = $request->input('driver_id');
        $submittedDocuments = [];

        // Remove drivers who have already submitted all required documents
        $drivers = $drivers->filter(function ($driver) use ($documentTypes) {
            $submittedCount = DriverDocument::where('driver_id', $driver->driver_id)
                ->whereIn('document_type_id', $documentTypes->pluck('document_id'))
                ->count();
            return $submittedCount < $documentTypes->count();
        })->values();

        return view('operator.documents.driver.create', compact('documentTypes', 'submittedDocuments', 'drivers'));
    }

    /**
     * Store operator documents
     */
    public function storeOperatorDocuments(Request $request)
    {
        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $operatorId = $operator->operator_id; // Changed from Auth::id()

        foreach ($request->file('documents') as $documentTypeId => $file) {
            // Check if document already exists
            $existingDocument = OperatorDocument::where('operator_id', $operatorId)
                ->where('document_type_id', $documentTypeId)
                ->first();

            if ($existingDocument) {
                // Delete old file
                Storage::delete($existingDocument->file_path);
                $existingDocument->delete();
            }

            // Store new file
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('operator-documents/' . $operatorId, $fileName, 'public');

            // Create document record
            OperatorDocument::create([
                'operator_id' => $operatorId,
                'document_type_id' => $documentTypeId,
                'document_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $extension,
                'file_size' => $file->getSize(),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('operator.documents.driver.create')
            ->with('success', 'Documents uploaded successfully!');
    }

    /**
     * Store driver documents
     */
    public function storeDriverDocuments(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,driver_id',
            'documents' => 'required|array',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $driverId = $request->driver_id;

        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        // Check if operator exists
        if (!$operator) {
            return back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $driver = Driver::where('driver_id', $driverId)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();

        foreach ($request->file('documents') as $documentTypeId => $file) {
            // Check if document already exists
            $existingDocument = DriverDocument::where('driver_id', $driverId)
                ->where('document_type_id', $documentTypeId)
                ->first();

            if ($existingDocument) {
                // Delete old file
                Storage::delete($existingDocument->file_path);
                $existingDocument->delete();
            }

            // Store new file
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('driver-documents/' . $driverId, $fileName, 'public');

            // Create document record
            DriverDocument::create([
                'driver_id' => $driverId,
                'document_type_id' => $documentTypeId,
                'document_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $extension,
                'file_size' => $file->getSize(),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('operator.home', ['driver' => $driverId])
            ->with('success', 'Driver documents uploaded successfully!');
    }

    /**
     * View document status
     */
    public function viewDocumentStatus()
    {
        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $operatorDocuments = OperatorDocument::where('operator_id', $operator->operator_id) // Changed from Auth::id()
            ->with('documentType')
            ->get();

        $driverDocuments = DriverDocument::whereHas('driver', function ($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id); // Changed from Auth::id()
        })->with(['documentType', 'driver'])->get();

        return view('operator.documents.status', compact('operatorDocuments', 'driverDocuments'));
    }

    /**
     * Delete document
     */
    public function deleteDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|in:operator,driver',
            'id' => 'required|integer',
        ]);

        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        if ($request->type === 'operator') {
            $document = OperatorDocument::where('id', $request->id)
                ->where('operator_id', $operator->operator_id) // Changed from Auth::id()
                ->firstOrFail();
        } else {
            $document = DriverDocument::where('id', $request->id)
                ->whereHas('driver', function ($query) use ($operator) {
                    $query->where('operator_id', $operator->operator_id); // Changed from Auth::id()
                })
                ->firstOrFail();
        }

        // Delete file from storage
        Storage::delete($document->file_path);

        // Delete database record
        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Show the resubmit form for an operator document
     */
    public function showResubmitOperatorDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $document = OperatorDocument::where('id', $id)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();

        $documentType = $document->documentType;

        return view('operator.documents.operator.resubmit', compact('document', 'documentType'));
    }

    /**
     * Handle resubmission of an operator document
     */
    public function resubmitOperatorDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $document = OperatorDocument::where('id', $id)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Delete old file
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        // Store new file
        $file = $request->file('document');
        $fileName = Str::random(16) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('operator_documents', $fileName, 'public');

        $document->file_path = $filePath;
        $document->document_name = $file->getClientOriginalName();
        $document->status = 'pending';
        $document->rejection_reason = null;
        $document->save();

        return redirect()->route('operator.documents.status')->with('success', 'Document resubmitted successfully!');
    }

    /**
     * Show the resubmit form for a driver document
     * Route: GET /operator/documents/driver/resubmit/{document}
     */
    public function resubmitDriverDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $document = DriverDocument::where('id', $id)
            ->whereHas('driver', function ($query) use ($operator) {
                $query->where('operator_id', $operator->operator_id);
            })
            ->with(['driver', 'documentType'])
            ->firstOrFail();

        $documentType = $document->documentType;
        $driver = $document->driver;

        return view('operator.documents.driver.resubmit', compact('document', 'documentType', 'driver'));
    }

    /**
     * Handle resubmission of a driver document
     * Route: POST /operator/documents/driver/resubmit/{document}
     */
    public function processResubmitDriverDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $document = DriverDocument::where('id', $id)
            ->whereHas('driver', function ($query) use ($operator) {
                $query->where('operator_id', $operator->operator_id);
            })
            ->firstOrFail();

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Delete old file
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        // Store new file
        $file = $request->file('document');
        $fileName = Str::random(16) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('driver_documents', $fileName, 'public');

        $document->file_path = $filePath;
        $document->document_name = $file->getClientOriginalName();
        $document->status = 'pending';
        $document->rejection_reason = null;
        $document->save();

        return redirect()->route('operator.documents.status')->with('success', 'Driver document resubmitted successfully!');
    }
};