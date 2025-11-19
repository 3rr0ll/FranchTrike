<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = ['description', 'amount','year','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'decimal:2'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
