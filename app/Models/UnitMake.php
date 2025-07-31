<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitMake extends Model
{
    public function motorDetails()
    {
        return $this->hasMany(MotorDetail::class);
    }
}
