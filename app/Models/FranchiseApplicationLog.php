<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseApplicationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_application_id',
        'status_before',
        'status_after',
        'updated_by',
    ];

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
