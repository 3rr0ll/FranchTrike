<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'name',
        'applies_to',
    ];

    protected $casts = [
        'applies_to' => 'string',
    ];

    /**
     * Get operator documents of this type
     */
    public function operatorDocuments()
    {
        return $this->hasMany(OperatorDocument::class, 'document_type_id', 'document_id');
    }

    /**
     * Get driver documents of this type
     */
    public function driverDocuments()
    {
        return $this->hasMany(DriverDocument::class, 'document_type_id', 'document_id');
    }

    /**
     * Scope for operator document types
     */
    public function scopeForOperator($query)
    {
        return $query->where('applies_to', 'operator');
    }

    /**
     * Scope for driver document types
     */
    public function scopeForDriver($query)
    {
        return $query->where('applies_to', 'driver');
    }
}
