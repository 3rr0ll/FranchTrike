<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OperatorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'document_type_id',
        'document_name',
        'file_path',
        'file_url',
        'file_type',
        'file_size',
        'cloudinary_public_id',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];
    

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Get the operator that owns the document
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'operator_id');
    }

    /**
     * Get the document type
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'document_id');
    }

    /**
     * Get the user who verified the document
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the full file URL - RENAMED to avoid conflict with database column
     */
    public function getFullFileUrlAttribute()
    {
        // Priority: Cloudinary URL first, then local storage
        if ($this->attributes['file_url']) {
            return $this->attributes['file_url']; // Cloudinary link
        }
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute()
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if file is a PDF
     */
    public function getIsPdfAttribute()
    {
        return strtolower($this->file_type) === 'pdf';
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        return $this->formatBytes($this->file_size);
    }

    /**
     * Check if document is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get display name - shows "Physically Submitted" suffix if applicable
     */
    public function getDisplayNameAttribute()
    {
        $isPhysicallySubmitted = str_contains($this->document_name, 'Physically Submitted');
        return $isPhysicallySubmitted ? $this->document_name : ($this->documentType ? $this->documentType->name : 'N/A');
    }

    /**
     * Approve the document
     */
    public function approve($verifiedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject the document
     */
    public function reject($reason, $verifiedBy = null)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Scope for approved documents
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending documents
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected documents
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}