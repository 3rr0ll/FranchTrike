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
        'franchise_application_id',
        'document_type_id',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'verified_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relationships
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Methods
    public function approve($verifierId)
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject($verifierId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    public function deleteFile()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
