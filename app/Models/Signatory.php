<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    // Table name (optional if follows convention)
    protected $table = 'signatories';

    // Mass assignable attributes
    protected $fillable = [
        'position_title',
        'name',
    ];
}
