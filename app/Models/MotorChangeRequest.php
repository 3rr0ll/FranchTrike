<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorChangeRequest extends Model
{
    protected $fillable = [
        'franchise_application_id',
        'old_unit_type',
        'old_unit_make_id',
        'old_motorno',
        'old_chasisno',
        'old_platenumber',
        'new_unit_type',
        'new_unit_make_id',
        'new_motorno',
        'new_chasisno',
        'new_platenumber',
        'status',
    ];

    protected $casts = [
        'new_unit_make_id' => 'integer',
        'old_unit_make_id' => 'integer',
    ];

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id');
    }

    public function oldUnitMake()
    {
        return $this->belongsTo(UnitMake::class, 'old_unit_make_id');
    }

    public function newUnitMake()
    {
        return $this->belongsTo(UnitMake::class, 'new_unit_make_id');
    }
}
