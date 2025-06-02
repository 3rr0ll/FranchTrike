<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'applies_to',
        'is_required',
        'max_file_size_mb',
        'allowed_extensions',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'max_file_size_mb' => 'integer',
        'allowed_extensions' => 'array',
    ];

    // Relationships
    public function operatorDocuments()
    {
        return $this->hasMany(OperatorDocument::class);
    }

    public function driverDocuments()
    {
        return $this->hasMany(DriverDocument::class);
    }

    // Scopes
    public function scopeForOperator($query)
    {
        return $query->whereIn('applies_to', ['operator', 'both']);
    }

    public function scopeForDriver($query)
    {
        return $query->whereIn('applies_to', ['driver', 'both']);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    // Methods
    public function isValidFileType($extension)
    {
        return in_array(strtolower($extension), $this->allowed_extensions ?? []);
    }

    public function isValidFileSize($sizeInBytes)
    {
        $maxSizeInBytes = ($this->max_file_size_mb ?? 5) * 1024 * 1024;
        return $sizeInBytes <= $maxSizeInBytes;
    }
}
