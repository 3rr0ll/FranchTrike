<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorDetail extends Model
{
    protected $fillable = [
        'franchise_application_id',
        'unit_type',
        'unit_make_id',
        'motorno',
        'chasisno',
        'platenumber',
        'franchise_number',
        'sticker_number',
        'case_number',
        'or_number',
        'amount',
        'date_issued',
        'place_issued',
        'validity',
        'toda_president',
        'traffic_division',
        'pfuc_chairperson',
    ];

    public function unitMake()
    {
        return $this->belongsTo(UnitMake::class);
    }

    public function franchiseApplication()
    {
        return $this->belongsTo(FranchiseApplication::class);
    }
}
