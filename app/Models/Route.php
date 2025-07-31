<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    // A route can have many franchise applications
    public function franchiseApplications()
    {
        return $this->hasMany(FranchiseApplication::class);
    }
}
