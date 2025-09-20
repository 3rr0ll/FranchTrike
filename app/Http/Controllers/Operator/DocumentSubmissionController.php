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
use Cloudinary\Uploader;

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
    
        $operatorId = $operator->operator_id;
    
        // Initialize Cloudinary
        try {
            \Log::info('Cloudinary config check:', [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key') ? 'SET' : 'NOT SET',
                'api_secret' => config('cloudinary.api_secret') ? 'SET' : 'NOT SET',
            ]);
    
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
    
            \Log::info('Cloudinary instance created successfully');
    
        } catch (\Exception $e) {
            \Log::error('Cloudinary initialization failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Cloudinary configuration error: ' . $e->getMessage()]);
        }
    
        foreach ($request->file('documents') as $documentTypeId => $file) {
            try {
                // Check if document already exists
                $existingDocument = OperatorDocument::where('operator_id', $operatorId)
                    ->where('document_type_id', $documentTypeId)
                    ->first();
    
                if ($existingDocument) {
                    // If stored in Cloudinary, delete from there
                    if ($existingDocument->cloudinary_public_id) {
                        try {
                            $cloudinary->uploadApi()->destroy(
                                $existingDocument->cloudinary_public_id,
                                ['resource_type' => 'auto']
                            );
                        } catch (\Exception $e) {
                            // Log error but continue with upload
                            \Log::error('Failed to delete existing Cloudinary file: ' . $e->getMessage());
                        }
                    }
    
                    $existingDocument->delete();
                }
    
                // Upload new file to Cloudinary
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
    
                // Create document record
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
                \Log::error('Cloudinary upload failed: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return back()->withErrors(['error' => 'Failed to upload document: ' . $file->getClientOriginalName() . '. Error: ' . $e->getMessage()]);
            }
        }
    
        \App\Helpers\ActivityLogger::log(
            'operator_document',
            'uploaded',
            'Operator uploaded documents.',
            [
                'operator_id' => $operatorId,
                'uploaded document names' => collect($request->file('documents'))->map(function ($file) {
                    return $file->getClientOriginalName();
                })->values()->all(),
                'uploaded_by' => auth()->user() ? auth()->user()->name : null,
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
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $driverId = $request->driver_id;

        // Get the operator record for current user
        $operator = $this->getCurrentOperator();

        if (!$operator) {
            return back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $driver = Driver::where('driver_id', $driverId)
            ->where('operator_id', $operator->operator_id)
            ->firstOrFail();

        // Initialize Cloudinary
        try {
            // Debug configuration
            \Log::info('Cloudinary config check:', [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key') ? 'SET' : 'NOT SET',
                'api_secret' => config('cloudinary.api_secret') ? 'SET' : 'NOT SET',
            ]);

            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            \Log::info('Cloudinary instance created successfully');

        } catch (\Exception $e) {
            \Log::error('Cloudinary initialization failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Cloudinary configuration error: ' . $e->getMessage()]);
        }

        foreach ($request->file('documents') as $documentTypeId => $file) {
            try {
                // Check if document already exists
                $existingDocument = DriverDocument::where('driver_id', $driverId)
                    ->where('document_type_id', $documentTypeId)
                    ->first();

                if ($existingDocument) {
                    // If stored in Cloudinary, delete from there
                    if ($existingDocument->cloudinary_public_id) {
                        try {
                            $cloudinary->uploadApi()->destroy(
                                $existingDocument->cloudinary_public_id,
                                ['resource_type' => 'auto']
                            );
                        } catch (\Exception $e) {
                            // Log error but continue with upload
                            \Log::error('Failed to delete existing Cloudinary file: ' . $e->getMessage());
                        }
                    }

                    $existingDocument->delete();
                }

                // Upload new file to Cloudinary
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

                // Create document record
                DriverDocument::create([
                    'driver_id'            => $driverId,
                    'document_type_id'     => $documentTypeId,
                    'document_name'        => $file->getClientOriginalName(),
                    'file_url'             => $fileUrl,
                    'file_type'            => $file->getClientOriginalExtension(),
                    'file_size'            => $file->getSize(),
                    'cloudinary_public_id' => $cloudinaryPublicId,
                    'status'               => 'pending',
                ]);
            } catch (\Exception $e) {
                \Log::error('Cloudinary upload failed: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return back()->withErrors(['error' => 'Failed to upload document: ' . $file->getClientOriginalName() . '. Error: ' . $e->getMessage()]);
            }
        }

        \App\Helpers\ActivityLogger::log(
            'driver_document',
            'uploaded',
            'Driver documents uploaded by operator.',
            [
                'operator_id' => $operator->operator_id,
                'driver_id'   => $driverId,
                'uploaded document names' => collect($request->file('documents'))->map(function ($file) {
                    return $file->getClientOriginalName();
                })->values()->all(),
                'uploaded_by' => auth()->user() ? auth()->user()->name : null,
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
        // Get the operator record for current user
        $operator = $this->getCurrentOperator();
    
        if (!$operator) {
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
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
        // Check if it's a Cloudinary URL
        if (strpos($originalUrl, 'cloudinary.com') === false) {
            return $originalUrl; // Return original if not Cloudinary
        }
        
        // Extract the public_id and other parts from the URL
        // Example URL: https://res.cloudinary.com/your-cloud/image/upload/v1234567890/folder/filename.jpg
        
        $urlParts = parse_url($originalUrl);
        $pathParts = explode('/', trim($urlParts['path'], '/'));
        
        // Find 'upload' in the path
        $uploadIndex = array_search('upload', $pathParts);
        if ($uploadIndex === false) {
            return $originalUrl; // Return original if 'upload' not found
        }
        
        // Insert transformation parameters after 'upload'
        $transformation = "c_{$crop},w_{$width},h_{$height},q_auto,f_auto";
        
        // Rebuild the path with transformation
        array_splice($pathParts, $uploadIndex + 1, 0, $transformation);
        
        $newPath = '/' . implode('/', $pathParts);
        
        return $urlParts['scheme'] . '://' . $urlParts['host'] . $newPath;
    }
    
    /**
     * Download document (for PDFs or full-size images)
     */
    public function downloadDocument($id, $type = 'operator')
    {
        if ($type === 'operator') {
            $document = OperatorDocument::findOrFail($id);
            
            // Check if user has permission to view this document
            $operator = $this->getCurrentOperator();
            if (!$operator || $document->operator_id !== $operator->operator_id) {
                abort(403, 'Unauthorized access to document');
            }
        } else {
            $document = DriverDocument::findOrFail($id);
            
            // Check if user has permission to view this document
            $operator = $this->getCurrentOperator();
            if (!$operator || $document->driver->operator_id !== $operator->operator_id) {
                abort(403, 'Unauthorized access to document');
            }
        }
        
        // For Cloudinary files, redirect to the secure URL
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
            ->with('documentType')
            ->firstOrFail();

        return view('operator.documents.operator.resubmit', compact('document'));
    }


    public function processResubmitOperatorDocument(Request $request, $id)
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

        $file = $request->file('document');

        //  Delete old Cloudinary file if exists
        if ($document->cloudinary_public_id) {
            Cloudinary::destroy($document->cloudinary_public_id);
        }

        //  Upload new file to Cloudinary
        $upload = Cloudinary::upload(
            $file->getRealPath(),
            [
                'folder' => 'operator-documents/' . $operator->operator_id,
                'public_id' => \Str::random(16),
            ]
        );

        //  Update document record
        $document->update([
            'file_url' => $upload->getSecurePath(),
            'cloudinary_public_id' => $upload->getPublicId(),
            'document_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        \App\Helpers\ActivityLogger::log(
            'operator_document',
            'resubmitted',
            'Operator resubmitted a document.',
            [
                'operator_id' => $operator->operator_id,
                'document_id' => $document->id,
                'document_name' => $document->document_name,
                'uploaded_by' => auth()->user()?->name,
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
            return redirect()->back()->withErrors(['error' => 'Please complete your operator profile first']);
        }

        $document = DriverDocument::where('id', $id)
            ->whereHas('driver', function ($query) use ($operator) {
                $query->where('operator_id', $operator->operator_id);
            })
            ->with(['driver', 'documentType'])
            ->firstOrFail();

        return view('operator.documents.driver.resubmit', compact('document'));
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

        $file = $request->file('document');

        //  Delete old Cloudinary file if exists
        if ($document->cloudinary_public_id) {
            Cloudinary::destroy($document->cloudinary_public_id);
        }

        //  Upload new file to Cloudinary
        $upload = Cloudinary::upload(
            $file->getRealPath(),
            [
                'folder' => 'driver-documents/' . $document->driver_id,
                'public_id' => \Str::random(16),
            ]
        );

        // 🔹 Update document record
        $document->update([
            'file_url' => $upload->getSecurePath(),
            'cloudinary_public_id' => $upload->getPublicId(),
            'document_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        \App\Helpers\ActivityLogger::log(
            'driver_document',
            'resubmitted',
            'Operator resubmitted a Driver document.',
            [
                'operator_id' => $operator->operator_id,
                'driver_id' => $document->driver_id,
                'document_id' => $document->id,
                'document_name' => $document->document_name,
                'uploaded_by' => auth()->user()?->name,
            ]
        );

        return redirect()->route('operator.documents.status')
            ->with('success', 'Driver document resubmitted successfully!');
    }
};
