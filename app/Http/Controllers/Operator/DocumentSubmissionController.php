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
use Cloudinary\Cloudinary;

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
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
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
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $drivers = Driver::where('operator_id', $operator->operator_id)->get();
        $documentTypes = DocumentType::forDriver()->get();
        $selectedDriverId = $request->input('driver_id');
        $submittedDocuments = [];

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
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->with('error', 'Please complete your operator profile first');
        }

        $operatorId = $operator->operator_id;
        $userId = Auth::check() ? Auth::id() : null;

        try {
            if (!config('cloudinary.cloud_name') || !config('cloudinary.api_key') || !config('cloudinary.api_secret')) {
                return back()->with('error', 'Cloudinary configuration is missing.');
            }

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Cloudinary configuration error: ' . $e->getMessage());
        }

        foreach ($request->file('documents') as $documentTypeId => $file) {
            try {
                $existingDocument = OperatorDocument::where('operator_id', $operatorId)
                    ->where('document_type_id', $documentTypeId)
                    ->first();

                if ($existingDocument) {
                    if ($existingDocument->cloudinary_public_id) {
                        try {
                            $cloudinary->uploadApi()->destroy(
                                $existingDocument->cloudinary_public_id,
                                ['resource_type' => 'auto']
                            );
                        } catch (\Exception $e) {
                            // Continue even if Cloudinary delete fails
                        }
                    }
                    $existingDocument->delete();
                }

                $publicId = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time();

                $upload = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'operator_documents/' . $operatorId,
                        'public_id' => $publicId,
                        'resource_type' => 'auto'
                    ]
                );

                $fileUrl = $upload['secure_url'];
                $cloudinaryPublicId = $upload['public_id'];

                OperatorDocument::create([
                    'operator_id'          => $operatorId,
                    'document_type_id'     => $documentTypeId,
                    'document_name'        => $file->getClientOriginalName(),
                    'file_url'             => $fileUrl,
                    'file_type'            => $file->getClientOriginalExtension(),
                    'file_size'            => $file->getSize(),
                    'cloudinary_public_id' => $cloudinaryPublicId,
                    'status'               => 'pending',
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload document: ' . $file->getClientOriginalName() . '. Error: ' . $e->getMessage());
            }
        }

        \App\Helpers\ActivityLogger::log(
            'operator document',
            'uploaded',
            'Operator uploaded documents.',
            [
                'operator_id' => $operatorId,
                'uploaded document names' => collect($request->file('documents'))->map(function ($file) {
                    return $file->getClientOriginalName();
                })->values()->all(),
                'uploaded_by' => Auth::user() ? Auth::user()->name : null,
                'user_id' => $userId,
            ]
        );

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
        $userId = Auth::check() ? Auth::id() : null;

        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->with('error', 'Please complete your operator profile first');
        }

        $driver = Driver::where('driver_id', $driverId)
            ->where('operator_id', $operator->operator_id)
            ->first();

        if (!$driver) {
            return back()->with('error', 'Driver not found or does not belong to your operator account.');
        }

        try {
            if (!config('cloudinary.cloud_name') || !config('cloudinary.api_key') || !config('cloudinary.api_secret')) {
                return back()->with('error', 'Cloudinary configuration is missing.');
            }

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Cloudinary configuration error: ' . $e->getMessage());
        }

        foreach ($request->file('documents') as $documentTypeId => $file) {
            try {
                $existingDocument = DriverDocument::where('driver_id', $driverId)
                    ->where('document_type_id', $documentTypeId)
                    ->first();

                if ($existingDocument) {
                    if ($existingDocument->cloudinary_public_id) {
                        try {
                            $cloudinary->uploadApi()->destroy(
                                $existingDocument->cloudinary_public_id,
                                ['resource_type' => 'auto']
                            );
                        } catch (\Exception $e) {
                            // Continue even if Cloudinary delete fails
                        }
                    }
                    $existingDocument->delete();
                }

                $publicId = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time();

                $upload = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'driver_documents/' . $driverId,
                        'public_id' => $publicId,
                        'resource_type' => 'auto'
                    ]
                );

                $fileUrl = $upload['secure_url'];
                $cloudinaryPublicId = $upload['public_id'];

                DriverDocument::create([
                    'driver_id'            => $driverId,
                    'document_type_id'     => $documentTypeId,
                    'document_name'        => $file->getClientOriginalName(),
                    'file_url'             => $fileUrl,
                    'file_type'            => $file->getClientOriginalExtension(),
                    'file_size'            => $file->getSize(),
                    'cloudinary_public_id' => $cloudinaryPublicId,
                    'status'               => 'pending',
                    'user_id'              => $userId,
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload document: ' . $file->getClientOriginalName() . '. Error: ' . $e->getMessage());
            }
        }

        \App\Helpers\ActivityLogger::log(
            'driver document',
            'uploaded',
            'Driver documents uploaded by operator.',
            [
                'operator_id' => $operator->operator_id,
                'driver_id'   => $driverId,
                'uploaded document names' => collect($request->file('documents'))->map(function ($file) {
                    return $file->getClientOriginalName();
                })->values()->all(),
                'uploaded_by' => Auth::user() ? Auth::user()->name : null,
            ]
        );

        return redirect()->route('operator.home', ['driver' => $driverId])
            ->with('success', 'Driver documents uploaded successfully!');
    }

    /**
     * View document status
     */
    public function viewDocumentStatus()
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $operatorDocuments = OperatorDocument::where('operator_id', $operator->operator_id)
            ->with('documentType')
            ->get();

        $driverDocuments = DriverDocument::whereHas('driver', function ($query) use ($operator) {
            $query->where('operator_id', $operator->operator_id);
        })->with(['documentType', 'driver'])->get();

        return view('operator.documents.status', compact('operatorDocuments', 'driverDocuments'));
    }

    /**
     * Generate Cloudinary thumbnail URL with transformations
     */
    private function getCloudinaryThumbnail($originalUrl, $width, $height, $crop = 'fill')
    {
        if (strpos($originalUrl, 'cloudinary.com') === false) {
            return $originalUrl;
        }

        $urlParts = parse_url($originalUrl);
        $pathParts = explode('/', trim($urlParts['path'], '/'));
        $uploadIndex = array_search('upload', $pathParts);
        if ($uploadIndex === false) {
            return $originalUrl;
        }
        $transformation = "c_{$crop},w_{$width},h_{$height},q_auto,f_auto";
        array_splice($pathParts, $uploadIndex + 1, 0, $transformation);
        $newPath = '/' . implode('/', $pathParts);
        return $urlParts['scheme'] . '://' . $urlParts['host'] . $newPath;
    }

    /**
     * Download document (for PDFs or full-size images)
     */
    public function downloadDocument($id, $type = 'operator')
    {
        $operator = $this->getCurrentOperator();

        if ($type === 'operator') {
            $document = OperatorDocument::find($id);
            if (!$document || !$operator || $document->operator_id !== $operator->operator_id) {
                abort(403, 'Unauthorized access to document');
            }
        } else {
            $document = DriverDocument::find($id);
            if (
                !$document ||
                !$operator ||
                !$document->driver ||
                $document->driver->operator_id !== $operator->operator_id
            ) {
                abort(403, 'Unauthorized access to document');
            }
        }

        if ($document->file_url) {
            return redirect($document->file_url);
        }

        abort(404, 'Document not found');
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

        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->with('error', 'Please complete your operator profile first');
        }

        if ($request->type === 'operator') {
            $document = OperatorDocument::where('id', $request->id)
                ->where('operator_id', $operator->operator_id)
                ->first();
        } else {
            $document = DriverDocument::where('id', $request->id)
                ->whereHas('driver', function ($query) use ($operator) {
                    $query->where('operator_id', $operator->operator_id);
                })
                ->first();
        }

        if (!$document) {
            return back()->with('error', 'Document not found or you do not have permission to delete it.');
        }

        // Delete file from storage if file_path exists
        if (!empty($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Handle resubmission of an operator document
     */
    public function resubmitOperatorDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $document = OperatorDocument::where('id', $id)
            ->where('operator_id', $operator->operator_id)
            ->with('documentType')
            ->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found or you do not have permission.');
        }

        return view('operator.documents.operator.resubmit', compact('document'));
    }

    public function processResubmitOperatorDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $document = OperatorDocument::where('id', $id)
            ->where('operator_id', $operator->operator_id)
            ->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found or you do not have permission.');
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('document');
        $userId = Auth::check() ? Auth::id() : null;

        try {
            // Use Cloudinary PHP SDK instance methods instead of static
            $cloudinary = new Cloudinary();

            if ($document->cloudinary_public_id) {
                $cloudinary->uploadApi()->destroy($document->cloudinary_public_id);
            }

            $upload = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'operator_documents/' . $operator->operator_id,
                    'public_id' => Str::random(16),
                    'resource_type' => 'auto',
                ]
            );

            $document->update([
                'file_url' => $upload['secure_url'] ?? null,
                'cloudinary_public_id' => $upload['public_id'] ?? null,
                'document_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
                'rejection_reason' => null,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resubmit document: ' . $e->getMessage());
        }

        \App\Helpers\ActivityLogger::log(
            'operator document',
            'resubmitted',
            'Operator resubmitted a document.',
            [
                'operator_id' => $operator->operator_id,
                'document_id' => $document->id,
                'document_name' => $document->document_name,
                'uploaded_by' => Auth::user() ? Auth::user()->name : null,
                'user_id' => $userId,
            ]
        );

        return redirect()->route('operator.documents.status')
            ->with('success', 'Operator document resubmitted successfully!');
    }

    /**
     * Show the resubmit form for a driver document
     * Route: GET /operator/documents/driver/resubmit/{document}
     */
    public function resubmitDriverDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $document = DriverDocument::where('id', $id)
            ->whereHas('driver', function ($query) use ($operator) {
                $query->where('operator_id', $operator->operator_id);
            })
            ->with(['driver', 'documentType'])
            ->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found or you do not have permission.');
        }

        return view('operator.documents.driver.resubmit', compact('document'));
    }

    /**
     * Handle resubmission of a driver document
     */
    public function processResubmitDriverDocument(Request $request, $id)
    {
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return redirect()->back()->with('error', 'Please complete your operator profile first');
        }

        $document = DriverDocument::where('id', $id)
            ->whereHas('driver', function ($query) use ($operator) {
                $query->where('operator_id', $operator->operator_id);
            })
            ->first();

        if (!$document) {
            return redirect()->back()->with('error', 'Document not found or you do not have permission.');
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('document');
        $userId = Auth::check() ? Auth::id() : null;

        try {
            $cloudinary = new Cloudinary();

            if ($document->cloudinary_public_id) {
                $cloudinary->uploadApi()->destroy($document->cloudinary_public_id);
            }

            $upload = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'driver_documents/' . $document->driver_id,
                    'public_id' => Str::random(16),
                    'resource_type' => 'auto',
                ]
            );

            $document->update([
                'file_url' => $upload['secure_url'] ?? null,
                'cloudinary_public_id' => $upload['public_id'] ?? null,
                'document_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
                'rejection_reason' => null,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resubmit document: ' . $e->getMessage());
        }

        \App\Helpers\ActivityLogger::log(
            'driver document',
            'resubmitted',
            'Operator resubmitted a Driver document.',
            [
                'operator_id' => $operator->operator_id,
                'driver_id' => $document->driver_id,
                'document_id' => $document->id,
                'document_name' => $document->document_name,
                'uploaded_by' => Auth::user() ? Auth::user()->name : null,
                'user_id' => $userId,
            ]
        );

        return redirect()->route('operator.documents.status')
            ->with('success', 'Driver document resubmitted successfully!');
    }
}
